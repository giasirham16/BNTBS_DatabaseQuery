@extends('admin.layouts.login')
@section('title', 'Verifikasi OTP')

@section('content')
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px">
            <div class="card-body">
                <h5 class="text-center mb-3">Verifikasi OTP</h5>

                <form action="{{ route('otp.verify') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="otp" class="form-label">Kode OTP</label>
                        <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror"
                            required>
                        @error('otp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button class="btn btn-success w-100 mb-2">Verifikasi</button>
                </form>

                <!-- Tombol resend dan kembali -->
                <div class="d-flex justify-content-between mt-2">
                    <form id="resendForm" action="{{ route('otp.resend') }}" method="POST">
                        @csrf
                        <button type="submit" id="resendButton" class="btn btn-warning btn-sm" disabled>Kirim Ulang
                            OTP</button>
                    </form>
                    <a href="{{ route('login') }}" class="btn bi-house-fill btn-success btn-sm"></a>
                </div>

                <div class="text-muted text-center mt-2" id="resendTimerInfo"></div>
            </div>
        </div>
    </div>

    @php
        $lastSent = session('otp_last_sent');
        $availableAt = $lastSent ? \Carbon\Carbon::parse($lastSent)->addMinutes(2)->timestamp : now()->timestamp;
    @endphp

    <script>
        const resendButton = document.getElementById('resendButton');
        const timerInfo = document.getElementById('resendTimerInfo');
        let resendAvailableAt = {{ $availableAt }};

        function updateTimer() {
            const remaining = resendAvailableAt - Math.floor(Date.now() / 1000);
            if (remaining > 0) {
                resendButton.disabled = true;
                const minutes = Math.floor(remaining / 60);
                const seconds = remaining % 60;
                timerInfo.innerText = `Kirim ulang OTP tersedia dalam ${minutes}:${seconds.toString().padStart(2, '0')}`;
            } else {
                resendButton.disabled = false;
                timerInfo.innerText = '';
            }
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    </script>
@endsection
