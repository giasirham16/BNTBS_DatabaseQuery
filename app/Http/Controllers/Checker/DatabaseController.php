<?php

namespace App\Http\Controllers\Checker;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public function index()
    {
        $data = DatabaseParameter::all();
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
                return redirect()->route('chkViewDatabase')->with('success', 'Data berhasil diapprove!');
            } else {
                return redirect()->route('chkViewDatabase')->with('success', 'Data berhasil direject!');
            }
        } catch (\Exception $e) {
            return redirect()->route('chkViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
