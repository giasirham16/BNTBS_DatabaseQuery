<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\DatabaseParameter;
use Illuminate\Http\Request;

class ManageDatabaseController extends Controller
{
  public function index()
  {
    // session()->forget('success');
    $data = DatabaseParameter::all();
    return view('operator.ManageDatabase')->with('data', $data);
  }

  public function store(Request $request)
  {
    try {
      $data = $request->only(['namaDB', 'ipHost', 'port', 'driver', 'statusApproval']);
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

  public function update(Request $request, $id)
  {
    try {
      $update = $request->only(['namaDB', 'ipHost', 'port', 'driver', 'reason', 'statusApproval']);
      $data = DatabaseParameter::find($id);
      $status = $data->update($update);

      if ($status) {
        return redirect()->route('viewDatabase')->with('success', 'Data updated successfully!');
      } else {
        return redirect()->route('viewDatabase')->with('error', 'Failed to update data.');
      }
    } catch (\Exception $e) {
      return redirect()->route('viewDatabase')->with('error', 'Error: ' . $e->getMessage());
    }
  }

  public function destroy($id)
  {
    try {
      $data = DatabaseParameter::find($id);
      $data->statusApproval = 99;
      $status = $data->save();

      if ($status) {
        return redirect()->route('viewDatabase')->with('success', 'Data deleted successfully!');
      } else {
        return redirect()->route('viewDatabase')->with('error', 'Failed to deleted data.');
      }
    } catch (\Exception $e) {
      return redirect()->route('viewDatabase')->with('error', 'Error: ' . $e->getMessage());
    }
  }
}
