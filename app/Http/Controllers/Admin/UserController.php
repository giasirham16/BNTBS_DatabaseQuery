<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $data = User::orderBy('created_at', 'desc')->get();
        return view('superadmin.ManageUser')->with('data', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&]).+$/', // At least one uppercase letter, one digit, and one special character
            ],
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung huruf besar, angka, dan simbol.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('show_modal', true);
        }

        $data = User::where('username', $request->username)
            ->where('statusApproval', 2)
            ->first();

        if ($data) {
            return redirect()->route('viewUser')->with('error', 'Username sudah digunakan!');
        } else {
            try {
                $data = $request->only(['username', 'password', 'role', 'email', 'statusApproval']);
                $saved = User::create($data);

                if ($saved) {
                    return redirect()->route('viewUser')->with('success', 'Data menunggu approval untuk dibuat!');
                } else {
                    return redirect()->route('viewUser')->with('error', 'Gagal membuat data!');
                }
            } catch (\Exception $e) {
                return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
            }
        }
    }

    public function unblock($id)
    {
        try {
            $data = User::find($id);
            $data->statusApproval = 2;

            $status = $data->save();

            if ($status) {
                return redirect()->route('viewUser')->with('success', 'User berhasil di unblock!');
            } else {
                return redirect()->route('viewUser')->with('error', 'User gagal di unblock');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $data = User::find($request->id);
            // dd($request->id);
            $admin = Auth::user()->username;
            if ($admin == 'superadmin1') {
                $data->statusApproval = 5;
            } else if ($admin == 'superadmin2') {
                $data->statusApproval = 6;
            } else {
                return redirect()->route('viewUser')->with('error', 'Invalid role!');
            }
            $data->reasonApproval = $request->reason;

            $status = $data->save();

            if ($status) {
                return redirect()->route('viewUser')->with('success', 'Data berhasil diproses!');
            } else {
                return redirect()->route('viewUser')->with('error', 'Data gagal diproses!');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {

            $data = User::find($request->id);

            // dd($request->id);
            $admin = Auth::user()->username;

            if ($admin == 'superadmin1') {
                $data->statusApproval = 3;
            } else if ($admin == 'superadmin2') {
                $data->statusApproval = 4;
            } else {
                return redirect()->route('viewUser')->with('error', 'Invalid role!');
            }
            // Simpan perubahan yang diajukan di pendingChanges
            $data->pendingChanges = json_encode([
                'email' => $request->email,
            ]);

            $status = $data->save();

            if ($status) {
                return redirect()->route('viewUser')->with('success', 'Data menunggu approval untuk diupdate!');
            } else {
                return redirect()->route('viewUser')->with('error', 'Data gagal diproses!');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function approve(Request $request)
    {
        try {
            $data = User::find($request->id);
            // Jika Approve
            if ($request->approval == 1) {
                $users = User::where('username', $data->username)
                    ->where('statusApproval', 2)
                    ->first();
                // Cek apakah username sudah ada yang digunakan
                if ($users) {
                    return redirect()->route('viewUser')->with('error', 'Gagal Approve, username sudah digunakan!');
                }
                /// Jika belum ada yang menggunakan username
                else {
                    // Jika statusApproval 0 atau 1, set ke 2 (aktif)
                    if ($data->statusApproval == 0 || $data->statusApproval == 1) {
                        $data->statusApproval = 2;
                    } else if ($data->statusApproval == 3 || $data->statusApproval == 4) {
                        // Jika statusApproval 3 atau 4, terapkan perubahan dari pendingChanges
                        $pending = json_decode($data->pendingChanges, true);
                        $data->email = $pending['email'] ?? $data->email;
                        $data->pendingChanges = null; // Hapus pendingChanges setelah diterapkan
                        $data->statusApproval = 2; // Set status ke aktif
                    } else {
                        $data->statusApproval = 99;
                    }
                }
            } else {
                // Jika reject add dan yang login adalah superadmin1
                if ($data->statusApproval == 1 && strtolower(Auth::user()->username) == 'superadmin1') {
                    $data->statusApproval = 8;
                }
                // Jika reject add dan yang login adalah superadmin2
                else if ($data->statusApproval == 1 && strtolower(Auth::user()->username) == 'superadmin2') {
                    $data->statusApproval = 7;
                }
                // Jika update delete direject kembalikan status menjadi approve
                else {
                    $data->statusApproval = 2;
                }
            }
            $data->reasonApproval = $request->reasonApproval;

            $status = $data->save();

            if ($status) {
                return redirect()->route('viewUser')->with('success', 'Data berhasil diproses!');
            } else {
                return redirect()->route('viewUser')->with('error', 'Data gagal diproses!');
            }
        } catch (\Exception $e) {
            return redirect()->route('viewUser')->with('error', 'Error: ' . $e->getMessage());
        }
    }


    // public function ubahPassword()
    // {
    //     $pengguna = Auth::user();
    //     return view('admin.pages.pengguna.ubahPasswordPengguna', compact('pengguna'));
    // }

    // public function updatePassword(Request $request, $id)
    // {
    //     $request->validate([
    //         'currentPassword' => 'required',
    //         'password' => 'required|min:8',
    //         'passwordConfirmation' => 'required|same:password'
    //     ], [
    //         'password.min' => "Password minimal 8",
    //         'passwordConfirmation.same' => "Password tidak sama"
    //     ]);

    //     $user = User::findOrFail($id);

    //     if (!Hash::check($request->currentPassword, $user->password)) {
    //         return back()->with('error', 'Password lama tidak cocok');
    //     }

    //     $user->update([
    //         'password' => Hash::make($request->password)
    //     ]);

    //     return redirect()->route('pengguna.ubahPassword', ['id' => $id])->with('success', 'Password berhasil diubah');
    // }
}
