<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\ApprovalQuery;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryController extends Controller
{
    public function index()
    {
        $approval = ApprovalQuery::whereRaw("LOWER(queryRequest) NOT LIKE 'select%'")
            ->where(function ($query) {
                $query->where('checker', Auth::user()->username)
                    ->orWhere('checker', null);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        return view('checker.ApprovalQuery')->with('approval', $approval);
    }

    public function approveQuery(Request $request)
    {
        try {
            $data = ApprovalQuery::find($request->id);
            $data->reason = $request->reason ?? '-';

            if ($request->approval == 1) {
                $data->statusApproval = 1;
            } else {
                $data->statusApproval = 3;
            }
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
                    'queryResult' => json_encode($data->queryResult),
                    'deskripsi' => $data->deskripsi,
                    'reason' => $data->reason,
                    'menu' => "Query",
                    'statusApproval' => $data->statusApproval,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => $request->approval == 1 ? "Approve Query" : "Reject Query"
                ]);
                return redirect()->route('chkViewQuery')->with('success', 'Data berhasil diproses!');
            } else {
                return redirect()->route('chkViewQuery')->with('error', 'Data gagal diproses!');
            }
        } catch (\Exception $e) {
            return redirect()->route('chkViewQuery')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
