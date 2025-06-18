<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\ApprovalQuery;
use App\Models\DatabaseParameter;
use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class RunQueryController extends Controller
{
    protected $data, $approval;

    public function __construct()
    {
        // Inisialisasi di constructor
        //Ambil data yang tidak direject
        $this->data = DatabaseParameter::where('statusApproval', 2)
            ->orWhere('statusApproval', 3)
            ->orWhere('statusApproval', 4)
            ->orWhere('statusApproval', 5)
            ->orWhere('statusApproval', 6)
            ->orderBy('created_at', 'desc')
            ->get();

        $this->approval = ApprovalQuery::where('operator', Auth::user()->username)->get();
    }

    public function index()
    {
        // dd($this->data);
        // dd($this->approval);
        // session()->forget('success');
        return view('operator.RunQuery')
            ->with('data', $this->data)
            ->with('approval', $this->approval);
        // ->with('queryResult', $queryResult ?? []);
    }

    public function executeQuery(Request $request)
    {
        // Ambil parameter dari request JSON
        $driver   = $request->input('driver');
        $host     = $request->input('ipHost');
        $port     = $request->input('port');
        $database = $request->input('namaDB');
        $username = $request->input('usernameDB');
        $password = $request->input('passwordDB');
        $query    = $request->input('query'); // SQL query

        // Cek koneksi ke server
        $connectionStatus = $this->testDatabaseConnection($driver, $host, $port, $database, $username, $password);
        // dd($connectionStatus);
        if ($connectionStatus !== true) {
            return redirect()->route('viewQuery')->with('error', $connectionStatus);
        }

        try {
            // Deteksi tipe query
            $lowerQuery = strtolower(ltrim($query));
            // Jika query select
            if (str_starts_with($lowerQuery, 'select')) {
                ApprovalQuery::create([
                    'namaDB' => $database,
                    'ipHost' => $host,
                    'port' => $port,
                    'driver' => $driver,
                    'queryRequest' => $query,
                    'deskripsi' => $request->deskripsi,
                    'username' => $username,
                    'password' => Crypt::encryptString($password),
                    'statusApproval' => 1,
                    'operator' => Auth::user()->username
                ]);

                // Simpan ke log activity
                LogActivity::create([
                    'namaDB' => $database,
                    'ipHost' => $host,
                    'port' => $port,
                    'driver' => $driver,
                    'queryRequest' => $query,
                    'queryResult' => null,
                    'deskripsi' => $request->deskripsi,
                    'reason' => null,
                    'menu' => "Query",
                    'statusApproval' => 1,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => "Request Query"
                ]);

                return redirect()->route('viewQuery')->with('success', 'Query menunggu approval untuk dieksekusi!');
            }
            // Jika query insert, delete
            else if ((str_starts_with($lowerQuery, 'insert')) || (str_starts_with($lowerQuery, 'delete'))) {
                if (str_starts_with($lowerQuery, 'insert')) {
                    // Konversi query insert menjadi select
                    $querySelect = $this->convertInsertToSelect($query);

                    // Konfigurasi koneksi
                    $config = [
                        'driver'    => $driver,
                        'host'      => $host,
                        'port'      => $port,
                        'database'  => $database,
                        'username'  => $username,
                        'password'  => $password,
                        'charset'   => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                        'prefix'    => '',
                        'strict'    => true,
                    ];

                    $queryResult = $this->runDynamicQuery($config, $querySelect);

                    if ($queryResult === false) {
                        return redirect()->route('viewQuery')->with('error', 'Data gagal diproses, query salah!');
                    } else if (is_array($queryResult)) {
                        // Jika returnnya array error
                        if (isset($queryResult['error'])) {
                            // dd($queryResult);
                            return redirect()->route('viewQuery')->with('error', 'Data gagal diproses, query error!');
                        }
                    }
                }

                $resultBefore = '';
                // Jika query insert, simpan hasil select nama kolomnya
                foreach ($queryResult as $item) {
                    $keys = array_keys(get_object_vars($item));

                    foreach ($keys as $index => $key) {
                        $resultBefore .= $key;

                        // Cek kalau ini BUKAN key terakhir
                        if ($index !== array_key_last($keys)) {
                            $resultBefore .= ', ';
                        }
                    }
                }

                ApprovalQuery::create([
                    'namaDB' => $database,
                    'ipHost' => $host,
                    'port' => $port,
                    'driver' => $driver,
                    'queryRequest' => $query,
                    'updateBefore' => isset($resultBefore) ? $resultBefore : null,
                    'deskripsi' => $request->deskripsi,
                    'username' => $username,
                    'statusApproval' => 0,
                    'password' => Crypt::encryptString($password),
                    'operator' => Auth::user()->username,
                ]);

                // Simpan ke log activity
                LogActivity::create([
                    'namaDB' => $database,
                    'ipHost' => $host,
                    'port' => $port,
                    'driver' => $driver,
                    'queryRequest' => $query,
                    'queryResult' => null,
                    'deskripsi' => $request->deskripsi,
                    'reason' => null,
                    'menu' => "Query",
                    'statusApproval' => 0,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => "Request Query"
                ]);
                return redirect()->route('viewQuery')->with('success', 'Query menunggu approval untuk dieksekusi!');
            }
            // Jika query update
            else if (str_starts_with($lowerQuery, 'update')) {
                // Ambil data sebelum update

                $querySelect = $this->convertUpdateToSelect($query);
                // dd($querySelect);

                // Konfigurasi koneksi
                $config = [
                    'driver'    => $driver,
                    'host'      => $host,
                    'port'      => $port,
                    'database'  => $database,
                    'username'  => $username,
                    'password'  => $password,
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                    'prefix'    => '',
                    'strict'    => true,
                ];

                $resultBefore = $this->runDynamicQuery($config, $querySelect);

                if ($resultBefore === false) {
                    return redirect()->route('viewQuery')->with('error', 'Data gagal diproses, query salah!');
                } else if (is_array($resultBefore)) {
                    // Jika returnnya array error
                    if (isset($resultBefore['error'])) {
                        // dd($queryResult);
                        return redirect()->route('viewQuery')->with('error', 'Data gagal diproses, query error!');
                    }
                }

                $resultAfter = $this->applyUpdateToSelectResult($query, $resultBefore);

                // Simpan data sebelum update ke database
                ApprovalQuery::create([
                    'namaDB' => $database,
                    'ipHost' => $host,
                    'port' => $port,
                    'driver' => $driver,
                    'queryRequest' => $query,
                    'deskripsi' => $request->deskripsi,
                    'username' => $username,
                    'statusApproval' => 0,
                    'password' => Crypt::encryptString($password),
                    'operator' => Auth::user()->username,
                    'updateBefore' => json_encode($resultBefore),
                    'updateAfter' => json_encode($resultAfter),
                ]);

                // Simpan ke log activity
                LogActivity::create([
                    'namaDB' => $database,
                    'ipHost' => $host,
                    'port' => $port,
                    'driver' => $driver,
                    'queryRequest' => $query,
                    'queryResult' => null,
                    'deskripsi' => $request->deskripsi,
                    'reason' => null,
                    'menu' => "Query",
                    'statusApproval' => 0,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => "Request Query"
                ]);
                return redirect()->route('viewQuery')->with('success', 'Query menunggu approval untuk dieksekusi!');
            }
            // Selain query select, insert, update, delete
            else {
                return redirect()->route('viewQuery')->with('error', 'Query yang anda masukkan salah.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('viewQuery')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Fungsi untuk mengkonversi query INSERT menjadi SELECT
    function convertInsertToSelect($query)
    {
        // Cari nama tabel
        $pattern = "/INSERT\s+INTO\s+([^\s(]+)/i";

        if (preg_match($pattern, $query, $matches)) {
            $tableName = $matches[1]; // Ambil nama tabel
            return "SELECT * FROM {$tableName};";
        } else {
            return '-- INVALID QUERY --';
        }
    }

    // Fungsi untuk mengkonversi query UPDATE menjadi SELECT
    public function convertUpdateToSelect($updateQuery)
    {
        // Normalisasi spasi
        $updateQuery = trim(preg_replace('/\s+/', ' ', $updateQuery));

        // Cek apakah format valid
        if (!preg_match('/^UPDATE\s+(\w+)\s+SET\s+.+\s+WHERE\s+(.+);?$/i', $updateQuery, $matches)) {
            throw new \Exception("Query UPDATE tidak valid.");
        }

        $table = $matches[1]; // nama tabel
        $whereClause = $matches[2]; // kondisi WHERE

        // Gunakan DB::select untuk SELECT
        $selectQuery = "SELECT * FROM {$table} WHERE {$whereClause}";

        return $selectQuery;
    }

    // Fungsi untuk menerapkan update ke hasil select
    public function applyUpdateToSelectResult($updateQuery, $queryResult)
    {
        // Normalisasi spasi
        $updateQuery = trim(preg_replace('/\s+/', ' ', $updateQuery));

        // Cek format dasar query
        if (!preg_match('/^UPDATE\s+(\w+)\s+SET\s+(.+?)\s+WHERE\s+(.+);?$/i', $updateQuery, $matches)) {
            throw new \Exception("Query UPDATE tidak valid.");
        }

        // Ambil bagian SET
        $setClause = $matches[2];

        // Parsing bagian SET jadi array
        $updates = [];
        $setParts = explode(',', $setClause);
        foreach ($setParts as $part) {
            [$column, $value] = explode('=', $part, 2);
            $column = trim($column);
            $value = trim($value);

            // Hilangkan tanda kutip jika ada
            $value = trim($value, "'\"");

            $updates[$column] = $value;
        }

        // Terapkan update ke setiap baris dari hasil select
        $updatedResults = [];
        foreach ($queryResult as $row) {
            $rowArray = (array) $row;
            foreach ($updates as $col => $val) {
                if (array_key_exists($col, $rowArray)) {
                    $rowArray[$col] = $val;
                }
            }
            $updatedResults[] = $rowArray;
        }

        return $updatedResults;
    }

    //Fungsi dinamic connection
    public function runDynamicQuery(array $params, string $query, string $connectionName = 'dynamic_connection')
    {
        $commonConfig = [
            'driver'    => $params['driver'] ?? 'mysql',
            'host'      => $params['host'] ?? '127.0.0.1',
            'port'      => $params['port'] ?? ($params['driver'] === 'pgsql' ? 5432 : 3306),
            'database'  => $params['database'],
            'username'  => $params['username'],
            'password'  => $params['password'],
            'prefix'    => '',
            'strict'    => true,
        ];

        switch ($params['driver']) {
            case 'mysql':
                $config = array_merge($commonConfig, [
                    'charset'   => 'utf8mb4',
                    'collation' => 'utf8mb4_unicode_ci',
                ]);
                break;
            case 'pgsql':
                $config = array_merge($commonConfig, [
                    'charset' => 'utf8',
                    'schema'  => $params['schema'] ?? 'public',
                    'sslmode' => $params['sslmode'] ?? 'prefer',
                ]);
                break;
            case 'sqlite':
                $config = [
                    'driver'   => 'sqlite',
                    'database' => $params['database'] ?? database_path('database.sqlite'),
                    'prefix'   => '',
                    'foreign_key_constraints' => true,
                ];
                break;
            case 'sqlsrv':
                $config = array_merge($commonConfig, [
                    'charset' => 'utf8',
                ]);
                break;
            default:
                throw new \InvalidArgumentException("Unsupported driver: {$params['driver']}");
        }

        // Set koneksi
        config(["database.connections.{$connectionName}" => $config]);
        DB::purge($connectionName);
        $db = DB::connection($connectionName);

        // Deteksi query type
        $queryType = strtolower(strtok(trim($query), ' '));

        try {
            switch ($queryType) {
                case 'select':
                    return $db->select($query);
                case 'insert':
                case 'update':
                case 'delete':
                    return $db->statement($query); // true/false
                default:
                    throw new \InvalidArgumentException("Unsupported query type: $queryType");
            }
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    function testDatabaseConnection($driver, $host, $port, $database, $username, $password)
    {
        try {
            switch (strtolower($driver)) {
                case 'mysql':
                    $dsn = "mysql:host=$host;port=$port;dbname=$database";
                    break;
                case 'pgsql':
                    $dsn = "pgsql:host=$host;port=$port;dbname=$database";
                    break;
                case 'sqlite':
                    $dsn = "sqlite:$database"; // Untuk SQLite, $database adalah path ke file
                    break;
                case 'sqlsrv':
                    $dsn = "sqlsrv:Server=$host,$port;Database=$database";
                    break;
                default:
                    throw new \Exception("Unsupported database driver: $driver");
            }

            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_TIMEOUT => 10,
            ]);

            return true; // Koneksi berhasil
        } catch (\PDOException $e) {
            return 'Error: Connection Failed, Reason: ' . $e->getMessage();
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
