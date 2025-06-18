<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <title> @yield('title') </title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Style -->
    @include('admin.includes.style')

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
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>


</body>

</html>
