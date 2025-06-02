@extends('admin.layouts.login')

@section('title', 'Login - Bank NTB Syariah')

@section('content')
    <style>
        .position-relative {
            position: relative;
        }

        #togglePassword {
            position: absolute;
            right: 15px;
            top: 75%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }
    </style>
    <div class="container-fluid d-flex justify-content-center align-items-center login-from">
        <div class="w-100 mx-3 p-4 bg-white position-relative"
            style="max-width: 500px; border-radius:20px; background: url('/assets/img/bg.png') no-repeat center center;background-size: fit;">
            <div class="text-center">
                <img src="/assets/img/logo.png" alt="Bank NTB Syariah" class="mb-3 img-fluid" width="300">
                <h6 style="color: #005C3C">Welcome to Database Query</h6>
            </div>

            <!-- 🔹 MENAMPILKAN PESAN ERROR -->
            @if (session('error'))
                <div class="alert alert-danger text-center" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" autocomplete="off" method="POST" class="pb-2">
                @csrf
                <div class="mb-3 mt-5">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        name="username" required value="{{ old('username') }}">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" required>
                    <i id="togglePassword" class="fa fa-eye"></i>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Captcha Image and Input --}}
                <div class="mb-3">
                    <label for="captcha" class="form-label">Captcha</label>
                    <div class="d-flex align-items-center mb-2">
                        <img src="{{ route('captcha.image') }}?r={{ rand() }}" id="captcha-image" class="me-2"
                            alt="captcha">
                        <button type="button" class="btn btn-sm btn-primary" onclick="refreshCaptcha()">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <input type="text" class="form-control w-50 @error('captcha') is-invalid @enderror" id="captcha"
                        name="captcha" placeholder="Masukkan captcha" required>
                    @error('captcha')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn w-100 btn-login">Login</button>
            </form>
            <!-- 🔽 FOOTER VERSI -->
            <div style="position: absolute; bottom: 10px; right: 15px; font-size: 12px; color: #666;">
                v1.0.0
            </div>
        </div>
    </div>

    <script>
        // JavaScript to toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye-slash'); // Toggle between eye and eye-slash
        });

        //Refresh Captcha
        function refreshCaptcha() {
            const img = document.getElementById('captcha-image');
            img.src = "{{ route('captcha.image') }}?r=" + Date.now();
        }
    </script>
@endsection
