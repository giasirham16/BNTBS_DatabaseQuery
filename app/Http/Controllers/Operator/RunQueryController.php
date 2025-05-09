<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\ApprovalQuery;
use App\Models\DatabaseParameter;
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
        $this->data = DatabaseParameter::where('statusApproval', 2)->get();
        $this->approval = ApprovalQuery::select(
            'id',
            'namaDB',
            'ipHost',
            'port',
            'driver',
            'queryRequest',
            'queryResult',
            'reason',
            'statusApproval'
        )->where('executedBy', Auth::user()->username)->get();
    }

    public function index()
    {
        // dd($this->data);
        // dd($this->approval);
        session()->forget('success');
        return view('operator.RunQuery')
            ->with('data', $this->data)
            ->with('approval', $this->approval)
            ->with('queryResult', $queryResult ?? []);
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

        // Cek koneksi ke server
        try {
            $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password, [
                \PDO::ATTR_TIMEOUT => 10, // 10 detik timeout
            ]);
        } catch (\PDOException $e) {
            return redirect()->route('viewQuery')->with('error', 'Error: Connection Failed, Reason: ' . $e->getMessage());
        }

        // Daftarkan koneksi dinamis
        DB::purge('dynamic_connection');
        config(['database.connections.dynamic_connection' => $config]);

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
                    'username' => $username,
                    'statusApproval' => 0,
                    'password' => Crypt::encryptString($password),
                    'executedBy' => Auth::user()->username,
                    'executedRole' => Auth::user()->role,
                ]);
                // $queryResult = DB::connection('dynamic_connection')->select($query);
 
                // $approval = ApprovalQuery::find(1);

                // $approval->queryResult = $queryResult;
                // $approval->save();
                // session()->flash('success', 'Query menunggu approval untuk dieksekusi!');
                return view('operator.RunQuery')
                    ->with('data', $this->data)
                    ->with('approval', $this->approval);
            }
            // Jika query insert, update, delete
            else if ((str_starts_with($lowerQuery, 'insert')) || (str_starts_with($lowerQuery, 'update')) || (str_starts_with($lowerQuery, 'delete'))) {
                $queryResult = DB::connection('dynamic_connection')->statement($query);

                // session()->flash('success', 'Query menunggu approval untuk dieksekusi!');
                return view('operator.RunQuery')
                    ->with('data', $this->data)
                    ->with('queryResult', []);
            }
            // Selain query select, insert, update, delete
            else {
                return redirect()->route('viewQuery')->with('error', 'Query yang anda masukkan salah.');
            }
        } catch (\Throwable $e) {
            return redirect()->route('viewQuery')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
