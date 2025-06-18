<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bank NTB Syariah')</title>
    <!-- Favicons -->
    @include('admin.includes.style')
>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('/assets/img/bankNTB.jpg') no-repeat center center;
            background-size: cover;
        }

        .login-form {
            width: 100%;
            padding: 50px;
            background: linear-gradient(to right, #ffffff, #f8f8f8);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .form-control:focus {
            border-color: #006A3B;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px #006A3B;
        }

        label {
            color: #005C3C
        }
    </style>
    <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">

</head>

<body>
    @yield('content')
</body>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>


</html>
