<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\ApprovalQuery;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;

class ApprovalQueryController extends Controller
{
    public function checkQuery()
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

    public function checkDatabase()
    {
        $data = DatabaseParameter::all();
        return view('checker.ApprovalDatabase')->with('data', $data);
    }

    public function approveQuery(Request $Request)
    {
        try {
            // dd($Request->reason);
            $data = ApprovalQuery::find($Request->id);
            $data->reason = $Request->reason ?? '-';
            $data->statusApproval = $Request->approval;
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

    public function approveDatabase(Request $Request)
    {
        try {
            // dd($Request->reason);
            $data = DatabaseParameter::find($Request->id);
            // $data->reason = $Request->reason ?? '-';
            $data->statusApproval = $Request->approval;
            $status = $data->save();

            if ($status) {
                return redirect()->route('chkViewDatabase')->with('success', 'Data berhasil diapprove!');
            } else {
                return redirect()->route('chkViewDatabase')->with('success', 'Data berhasil direject!');
            }
        } catch (\Exception $e) {
            return redirect()->route('chkViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
