<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;

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
            $data = DatabaseParameter::find($request->id);

            if ($request->approval == 1 && $data->statusApproval == 6) {
                $data->statusApproval = 99;
            } 
            else if ($request->approval == 1 && $data->statusApproval != 6) {
                $data->statusApproval = 2;
            }
            else if ($request->approval == 0){
                $data->statusApproval = 8;
            }
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
