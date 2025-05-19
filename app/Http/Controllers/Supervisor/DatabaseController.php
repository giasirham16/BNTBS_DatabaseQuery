<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public function index()
    {
        $data = DatabaseParameter::all();
        return view('supervisor.ApprovalDatabase')->with('data', $data);
    }


    public function approveDatabase(Request $request)
    {
        try {
            $rejectMap = [
                1 => 8,
                4 => 2,
                6 => 2
            ];

            $data = DatabaseParameter::find($request->id);

            if ($request->approval == 1 && $data->statusApproval == 6) {
                $data->statusApproval = 99;
            } 
            else if ($request->approval == 1 && $data->statusApproval != 6) {
                $data->statusApproval = 2;
            }
            else if ($request->approval == 0){
                $data->statusApproval = $rejectMap[$data->statusApproval];
            }
            
            $data->supervisor = Auth::user()->username;
            $data->reason = $request->reasonApproval;

            $status = $data->save();

            if ($status) {
                return redirect()->route('spvViewDatabase')->with('success', 'Data berhasil diapprove!');
            } else {
                return redirect()->route('spvViewDatabase')->with('success', 'Data berhasil direject!');
            }
        } catch (\Exception $e) {
            return redirect()->route('spvViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
