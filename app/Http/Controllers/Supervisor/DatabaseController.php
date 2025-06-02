<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogActivity;

class DatabaseController extends Controller
{
    public function index()
    {
        $data = DatabaseParameter::orderBy('created_at', 'desc')->get();
        return view('supervisor.ApprovalDatabase')->with('data', $data);
    }


    public function approveDatabase(Request $request)
    {
        try {
            $rejectMap = [
                1 => 8, // Add reject
                4 => 2, // Update reject
                6 => 2 // Delete reject
            ];

            $data = DatabaseParameter::find($request->id);

            // Approve Delete
            if ($request->approval == 1 && $data->statusApproval == 6) {
                $data->statusApproval = 99;
            }
            // Approve Add 
            else if ($request->approval == 1 && $data->statusApproval == 1) {
                $data->statusApproval = 2;
            }
            //Approve Update
            else if ($request->approval == 1 && $data->statusApproval == 4) {
                $pending = json_decode($data->pendingChanges, true);

                // Terapkan perubahan ke kolom utama
                $data->fill($pending);
                $data->pendingChanges = null;
                $data->statusApproval = 2;
                $data->save();
            }
            // Reject Based on reject map
            else if ($request->approval == 0) {
                $data->statusApproval = $rejectMap[$data->statusApproval];
            }

            $data->supervisor = Auth::user()->username;
            $data->reason = $request->reasonApproval;

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
                    'reason' => $data->reason,
                    'menu' => "Database",
                    'statusApproval' => $data->statusApproval,
                    'performedBy' => Auth::user()->username,
                    'role' => Auth::user()->role,
                    'action' => $request->approval == 1 ? "Approve Database Parameter" : "Reject Database Parameter"
                ]);

                return redirect()->route('spvViewDatabase')->with('success', 'Data berhasil diapprove!');
            } else {
                return redirect()->route('spvViewDatabase')->with('success', 'Data berhasil direject!');
            }
        } catch (\Exception $e) {
            return redirect()->route('spvViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $update = ([
                "namaDB" => $request->namaDB,
                "ipHost" => $request->ipHost,
                "port" => $request->port,
                "driver" => $request->driver,
                "reason" => $request->reason,
            ]);

            $data = DatabaseParameter::find($request->id);
            $status = $data->update($update);

            if ($status) {
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
                    'action' => "Update Database Parameter"
                ]);
                return redirect()->route('spvViewDatabase')->with('success', 'Update data berhasil!');
            } else {
                return redirect()->route('spvViewDatabase')->with('error', 'Update data gagal!');
            }
        } catch (\Exception $e) {
            return redirect()->route('spvViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = DatabaseParameter::find($request->id);
            $data->statusApproval = 99;
            $data->reason = $request->reason;
            $status = $data->save();

            if ($status) {
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
                    'action' => "Delete Database Parameter"
                ]);
                return redirect()->route('spvViewDatabase')->with('success', 'Delete data berhasil!');
            } else {
                return redirect()->route('spvViewDatabase')->with('error', 'Delete data gagal!');
            }
        } catch (\Exception $e) {
            return redirect()->route('spvViewDatabase')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
