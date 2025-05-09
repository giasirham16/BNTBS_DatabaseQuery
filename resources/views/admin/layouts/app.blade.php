<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title> @yield('title') </title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicons -->
    @include('admin.includes.style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- Table widget --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="main-wrapper main-wrapper-1">

        @include('admin.includes.navbar')

        @include('admin.includes.sidebar')

        <main id="main" class="main">

            @yield('content')

        </main><!-- End #main -->


        @include('admin.includes.footer')
        @include('admin.includes.script')

        @yield('scripts')
        @stack('scripttambahan')


    </div>
    {{-- Table widget --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

</body>

</html>
