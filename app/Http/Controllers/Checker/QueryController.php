<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\ApprovalQuery;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function index()
    {
        $approval = ApprovalQuery::select(
            'id',
            'namaDB',
            'ipHost',
            'port',
            'driver',
            'queryRequest',
            'queryResult',
            'reason',
            'executedBy',
            'statusApproval'
        )->where('statusApproval', 0)->get();
        return view('checker.ApprovalQuery')->with('approval', $approval);
    }

    public function approveQuery(Request $request)
    {
        try {
            // dd($Request->reason);
            $data = ApprovalQuery::find($request->id);
            $data->reason = $request->reason ?? '-';
            $data->statusApproval = $request->approval;
            $status = $data->save();

            if ($status) {
                return redirect()->route('chkViewQuery')->with('success', 'Data berhasil diapprove!');
            } else {
                return redirect()->route('chkViewQuery')->with('success', 'Data berhasil direject!');
            }
        } catch (\Exception $e) {
            return redirect()->route('chkViewQuery')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
