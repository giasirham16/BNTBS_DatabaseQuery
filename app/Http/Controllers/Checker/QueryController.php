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
            'deskripsi',
            'reason',
            'executedBy',
            'created_at',
            'statusApproval'
        )->whereRaw("LOWER(queryRequest) NOT LIKE 'select%'")->get();
        return view('checker.ApprovalQuery')->with('approval', $approval);
    }

    public function approveQuery(Request $request)
    {
        try {
            $data = ApprovalQuery::find($request->id);
            $data->reason = $request->reason ?? '-';

            if ($request->approval == 1) {
                $data->statusApproval = 1;
            }
            else {
                $data->statusApproval = 3;
            }
            
            $status = $data->save();

            if ($status) {
                return redirect()->route('chkViewQuery')->with('success', 'Data berhasil diproses!');
            } else {
                return redirect()->route('chkViewQuery')->with('error', 'Data gagal diproses!');
            }
        } catch (\Exception $e) {
            return redirect()->route('chkViewQuery')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
