<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // 🔹 MENAMPILKAN FORM LOGIN
    public function showLoginForm()
    {
        return view('admin.pages.login.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha_match'
        ]);

        $user = User::whereRaw('BINARY username = ?', [$request->username])
            ->whereIn('statusApproval', [2, 3, 4])
            ->first();

        Auth::logout();

        if ($user && Hash::check($request->password, $user->password)) {
            // 🔹 Generate OTP
            $otp = random_int(100000, 999999);
            $expired = now()->addMinutes(5);

            // 🔹 Kirim OTP lewat API
            // Get Token
            $requestToken = json_decode($this->generateToken());
            $auth = 'Authorization:' . (string) $requestToken->Authorization;
            $checksum = strtoupper(hash('sha256', '01NTB$2019user' . $user->email));

            // 🔹 Siapkan data untuk request
            $body = array(
                "type" => "01",
                "username" => "user",
                "email" => $user->email,
                "subject" => "Kode OTP",
                "pesan" => "KODE OTP ANDA $otp JANGAN BERIKAN KODE RAHASIA INI KEPADA SIAPAPUN TERMASUK PIHAK YANG MENGAKU DARI BANK NTB SYARIAH.",
                "checksum" => (string) $checksum
            );

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'http://103.145.128.179:65434/v2/other/Email',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    $auth
                ),
            ));

            $response = curl_exec($curl);

            // Cek respons
            // Decode response JSON
            $data = json_decode($response, true);

            // Cek jika rcode tidak 00
            if (!isset($data['rcode']) || $data['rcode'] !== '00') {
                return back()->withErrors(['username' => 'Gagal mengirim OTP. Silakan coba lagi.']);
            }

            // 🔹 Simpan OTP ke DB
            Otp::create([
                'username' => $user->username,
                'otp' => $otp,
                'expired_at' => $expired
            ]);

            // Simpan waktu terakhir OTP dikirim
            session(['otp_last_sent' => now()]);
            // 🔹 Simpan username ke session untuk proses verifikasi OTP
            session(['otp_username' => $user->username]);

            return redirect()->route('otp.verify.form');
        } else {
            if ($user) {
                $user->loginAttempts += 1;
                if ($user->loginAttempts >= 3) {
                    $user->statusApproval = 7;
                }
                $user->save();
            }
            return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
        }
    }


    // 🔹 LOGOUT & HAPUS SESSION
    public function logout(Request $request)
    {
        Auth::logout(); // 🔹 Logout user dari sistem

        $request->session()->invalidate(); // 🔹 Hapus semua sesi
        $request->session()->regenerateToken(); // 🔹 Regenerasi CSRF token untuk keamanan

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    // 🔹 VERIFIKASI OTP
    public function showOtpForm()
    {
        return view('admin.pages.login.otp');
    }

    // 🔹 PROSES VERIFIKASI OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $otpRecord = Otp::where('username', session('otp_username'))
            ->where('otp', $request->otp)
            ->where('expired_at', '>', now())
            ->first();

        if ($otpRecord) {
            $user = User::where('username', $otpRecord->username)->first();
            Auth::login($user);
            $request->session()->regenerate();
            Otp::where('username', $user->username)->delete(); // Hapus OTP setelah login

            if ($user->role === 'superadmin') {
                return redirect()->route('viewUser');
            } elseif ($user->role === 'operator') {
                return redirect()->route('viewQuery');
            } elseif ($user->role === 'supervisor') {
                return redirect()->route('spvViewQuery');
            } elseif ($user->role === 'checker') {
                return redirect()->route('chkViewQuery');
            }
        }

        return back()->withErrors(['otp' => 'OTP tidak valid atau kadaluarsa.']);
    }

    // 🔹 PROSES KIRIM ULANG OTP
    public function resendOtp(Request $request)
    {
        $username = session('otp_username');
        $lastSent = session('otp_last_sent');
   
        if (!$username || !$lastSent || Carbon::parse($lastSent)->diffInMinutes(now()) < 2) {
            return back()->withErrors(['otp' => 'Anda hanya dapat meminta OTP setiap 2 menit.']);
        }

        $user = User::where('username', $username)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Sesi berakhir. Silakan login ulang.');
        }

        $otp = random_int(100000, 999999);
        $expired = now()->addMinutes(5);

        // Get Token
        $requestToken = json_decode($this->generateToken());
        $auth = 'Authorization:' . (string) $requestToken->Authorization;
        $checksum = strtoupper(hash('sha256', '01NTB$2019user' . $user->email));

        // 🔹 Siapkan data untuk request
        $body = array(
            "type" => "01",
            "username" => "user",
            "email" => $user->email,
            "subject" => "Kode OTP",
            "pesan" => "KODE OTP ANDA $otp JANGAN BERIKAN KODE RAHASIA INI KEPADA SIAPAPUN TERMASUK PIHAK YANG MENGAKU DARI BANK NTB SYARIAH.",
            "checksum" => (string) $checksum
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://103.145.128.179:65434/v2/other/Email',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($body),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                $auth
            ),
        ));

        $response = curl_exec($curl);

        // Cek respons
        // Decode response JSON
        $data = json_decode($response, true);

        // Cek jika rcode tidak 00
        if (!isset($data['rcode']) || $data['rcode'] !== '00') {
            return back()->withErrors(['otp' => 'Gagal mengirim OTP. Silakan coba lagi.']);
        }

        Otp::create([
            'username' => $username,
            'otp' => $otp,
            'expired_at' => $expired
        ]);

        session(['otp_last_sent' => now()]);

        return back()->with('status', 'OTP berhasil dikirim ulang.');
    }

    // Generate Token
    function generateToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://103.145.128.179:65434/v2/Login',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'username=user&password=12345',
            CURLOPT_HTTPHEADER => array(
                'token: 12345',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
