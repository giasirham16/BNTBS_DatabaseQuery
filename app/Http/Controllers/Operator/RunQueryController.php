<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RunQueryController extends Controller
{
    protected $data;

    public function __construct()
    {
        // Inisialisasi di constructor
        $this->data = DatabaseParameter::where('statusApproval', 0)->get();
    }

    public function index()
    {
        session()->forget('success');
        return view('operator.RunQuery')
            ->with('data', $this->data)
            ->with('queryResult', $queryResult ?? []);
    }

    public function executeQuery(Request $request)
    {
        // dd($request->all());
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
            // return response()->json(['error' => $e->getMessage()], 500);
        }

        // Daftarkan koneksi dinamis
        DB::purge('dynamic_connection');
        config(['database.connections.dynamic_connection' => $config]);

        try {
            // Deteksi tipe query
            $lowerQuery = strtolower(ltrim($query));
            // Jika query select
            if (str_starts_with($lowerQuery, 'select')) {
                $queryResult = DB::connection('dynamic_connection')->select($query);
                session()->flash('success', 'Query berhasil dieksekusi!');
                return view('operator.RunQuery')
                    ->with('data', $this->data)
                    ->with('queryResult', $queryResult);
            } 
            // Jika query insert, update, delete
            else if ((str_starts_with($lowerQuery, 'insert')) || (str_starts_with($lowerQuery, 'update')) || (str_starts_with($lowerQuery, 'delete'))) {
                $queryResult = DB::connection('dynamic_connection')->statement($query);
                session()->flash('success', 'Query berhasil dieksekusi!');
                return view('operator.RunQuery')
                    ->with('data', $this->data)
                    ->with('queryResult', $queryResult);
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
