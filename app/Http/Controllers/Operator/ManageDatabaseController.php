<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageDatabaseController extends Controller
{
  public function index()
  {
    // session()->forget('success');
    $data = DatabaseParameter::where('operator', Auth::user()->username)->get();
    return view('operator.ManageDatabase')->with('data', $data);
  }

  public function store(Request $request)
  {
    $data = DatabaseParameter::where('ipHost', $request->ipHost)
      ->where('port', $request->port)
      ->where('namaDB', $request->namaDB)
      ->first();

    if ($data) {
      return redirect()->route('viewDatabase')->with('error', 'Data sudah ada!');
    } else {
      try {
        $data = ([
          "namaDB" => $request->namaDB,
          "ipHost" => $request->ipHost,
          "port" => $request->port,
          "driver" => $request->driver,
          "operator" => Auth::user()->username,
          "statusApproval" => 0
        ]);

        $saved = DatabaseParameter::create($data);

        if ($saved) {
          return redirect()->route('viewDatabase')->with('success', 'Data saved successfully!');
        } else {
          return redirect()->route('viewDatabase')->with('error', 'Failed to save data.');
        }
      } catch (\Exception $e) {
        return redirect()->route('viewDatabase')->with('error', 'Error: ' . $e->getMessage());
      }
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
        "statusApproval" => 3
      ]);

      $data = DatabaseParameter::find($request->id);
      $data->created_at = now();
      $status = $data->update($update);

      if ($status) {
        return redirect()->route('viewDatabase')->with('success', 'Update data menunggu approval atasan!');
      } else {
        return redirect()->route('viewDatabase')->with('error', 'Gagal membuat permintaan update data!');
      }
    } catch (\Exception $e) {
      return redirect()->route('viewDatabase')->with('error', 'Error: ' . $e->getMessage());
    }
  }

  public function destroy(Request $request)
  {
    try {
      $data = DatabaseParameter::find($request->id);
      $data->statusApproval = 5;
      $data->created_at = now();
      $data->reason = $request->reason;
      $status = $data->save();

      if ($status) {
        return redirect()->route('viewDatabase')->with('success', 'Delete data menunggu approval atasan!');
      } else {
        return redirect()->route('viewDatabase')->with('error', 'Gagal membuat permintaan delete data!');
      }
    } catch (\Exception $e) {
      return redirect()->route('viewDatabase')->with('error', 'Error: ' . $e->getMessage());
    }
  }
}
