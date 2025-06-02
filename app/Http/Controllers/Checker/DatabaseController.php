<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public function index()
    {
        $data = DatabaseParameter::where('checker', Auth::user()->username)
            ->orWhere('checker', null)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('checker.ApprovalDatabase')->with('data', $data);
    }


    public function approveDatabase(Request $request)
    {
        try {
            $data = DatabaseParameter::find($request->id);
            $statusMap = [
                0 => 1, //Add
                3 => 4, //Update
                5 => 6, //Delete
            ];

            $rejectMap = [
                0 => 7,
                3 => 2,
                5 => 2
            ];

            if ($request->approval == 1) {
                $data->statusApproval = $statusMap[$data->statusApproval];
            } else {
                $data->statusApproval = $rejectMap[$data->statusApproval];
            }
            $data->reason = $request->reasonApproval;
            $data->checker = Auth::user()->username;

            $status = $data->save();

            if ($status) {
                // Simpan ke log activity
                LogActivity::create([
                    'namaDB' => $data->namaDB,
                    'ipHost' => $data->ipHost,
                    'port' => $data->port,
                    'driver' => $data->driver,
                    'queryRequest' => $data->queryRequest,
                    'queryResult' => $data->queryResult,
                    'deskripsi' => $data->deskripsi,
                    'reason' => $data->reasonApproval,
                    'menu' => "Database",
                    'statusApproval' => $data->statusApproval,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => $request->approval == 1 ? "Approve Database Parameter" : "Reject Database Parameter"
                ]);
                return redirect()->route('chkViewDatabase')->with('success', 'Data berhasil diapprove!');
            } else {
                return redirect()->route('chkViewDatabase')->with('success', 'Data berhasil direject!');
            }
        } catch (\Exception $e) {
            return redirect()->route('chkViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
