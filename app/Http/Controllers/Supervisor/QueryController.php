<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ApprovalQuery;
use App\Models\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class QueryController extends Controller
{
    public function index()
    {
        $approval = ApprovalQuery::get();
        return view('supervisor.ApprovalQuery')->with('approval', $approval);
    }

    public function approveQuery(Request $request)
    {
        try {
            $data = ApprovalQuery::find($request->id);
            $data->reason = $request->reason ?? '-';
            //Jika Approve
            if ($request->approval == 1) {
                $data->statusApproval = 2;

                // Ambil parameter config dari table
                $driver   = $data->driver;
                $host     = $data->ipHost;
                $port     = $data->port;
                $database = $data->namaDB;
                $username = $data->username;
                $password = Crypt::decryptString($data->password);
                $query    = $data->queryRequest; // SQL query

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

                $queryResult = $this->runDynamicQuery($config, $query);
                // dd($queryResult);
                if ($queryResult === true) {
                    $data->queryResult = '[{"Status":"Success"}]';
                } else if ($queryResult === false) {
                    return redirect()->route('spvViewQuery')->with('error', 'Data gagal diproses, query salah!');
                } else {
                    // Jika returnnya array error
                    if (is_array($queryResult)) {
                        if (isset($queryResult['error'])) {
                            // dd($queryResult);
                            return redirect()->route('spvViewQuery')->with('error', 'Data gagal diapprove, query salah!');
                        }
                    }
                    $data->queryResult = $queryResult;
                }
            }
            // Jika reject
            else {
                $data->statusApproval = 4;
            }
            $data->supervisor = Auth::user()->username;
            $status = $data->save();

            if ($status) {
                // Simpan ke log activity
                LogActivity::create([
                    'namaDB' => $data->namaDB,
                    'ipHost' => $data->ipHost,
                    'port' => $data->port,
                    'driver' => $data->driver,
                    'queryRequest' => $data->queryRequest,
                    'queryResult' => json_encode($data->queryResult),
                    'deskripsi' => $data->deskripsi,
                    'reason' => $data->reason,
                    'menu' => "Query",
                    'statusApproval' => $data->statusApproval,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => $request->approval == 1 ? "Approve Query" : "Reject Query"
                ]);
                return redirect()->route('spvViewQuery')->with('success', 'Data berhasil diproses!');
            } else {
                return redirect()->route('spvViewQuery')->with('error', 'Data gagal diproses!');
            }
        } catch (\Exception $e) {
            return redirect()->route('spvViewQuery')->with('error', 'Error: ' . $e->getMessage());
        }
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
}
