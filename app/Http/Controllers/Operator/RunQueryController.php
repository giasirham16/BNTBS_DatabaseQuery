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
        //Ambil data yang tidak direject
        $this->data = DatabaseParameter::where('statusApproval', 2)
            ->orWhere('statusApproval', 3)
            ->orWhere('statusApproval', 4)
            ->orWhere('statusApproval', 5)
            ->orWhere('statusApproval', 6)
            ->get();

        $this->approval = ApprovalQuery::select(
            'id',
            'namaDB',
            'ipHost',
            'port',
            'driver',
            'queryRequest',
            'queryResult',
            'deskripsi',
            'reason',
            'statusApproval'
        )->where('executedBy', Auth::user()->username)->get();
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
        try {
            $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password, [
                \PDO::ATTR_TIMEOUT => 10, // 10 detik timeout
            ]);
        } catch (\PDOException $e) {
            return redirect()->route('viewQuery')->with('error', 'Error: Connection Failed, Reason: ' . $e->getMessage());
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
                    'statusApproval' => 1,
                    'password' => Crypt::encryptString($password),
                    'executedBy' => Auth::user()->username,
                    'executedRole' => Auth::user()->role,
                ]);

                return redirect()->route('viewQuery')->with('success', 'Query menunggu approval untuk dieksekusi!');
            }
            // Jika query insert, update, delete
            else if ((str_starts_with($lowerQuery, 'insert')) || (str_starts_with($lowerQuery, 'update')) || (str_starts_with($lowerQuery, 'delete'))) {
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
                    'executedBy' => Auth::user()->username,
                    'executedRole' => Auth::user()->role,
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
}
