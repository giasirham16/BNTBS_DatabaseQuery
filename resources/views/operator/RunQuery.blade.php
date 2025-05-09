@extends('admin.layouts.app')

@section('title')
    Run Query | Bank NTB Syariah
@endsection

@section('content')
    {{-- {{ dd($data) }} --}}
    <div class="pagetitle">
        <h1>Run Query</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        {{-- <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('admin/v1/dashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Run Query</li>
        </ol>
    </nav> --}}
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="container-fluid">
            <form action="{{ route('executeQuery') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Query</h5>
                                <textarea class="form-control" rows="13" placeholder="Write your query here" name="query" id="query"></textarea>
                                <div class="row mt-3">
                                    <div class="col text-end">
                                        <button type=submit class="btn btn-secondary">Execute</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="row">
                                <div class="card-body">
                                    <label for="floatingSelect" class="form-label">Driver</label>
                                    <div class="form-floating">
                                        <select class="form-select" name="driver" id="driver">
                                            @foreach ($data as $key => $value)
                                                @if ($loop->first)
                                                    <option selected value={{ $value->driver }}>{{ $value->driver }}
                                                    </option>
                                                @else
                                                    <option value={{ $value->driver }}>{{ $value->driver }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <label for="floatingSelect" class="form-label">Host DB</label>
                                    <div class="form-floating">
                                        <select class="form-select" name="ipHost" id="ipHost">
                                            @foreach ($data as $key => $value)
                                                @if ($loop->first)
                                                    <option selected value={{ $value->ipHost }}>{{ $value->ipHost }}
                                                    </option>
                                                @else
                                                    <option value={{ $value->ipHost }}>{{ $value->ipHost }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <label for="floatingSelect" class="form-label">Port</label>
                                    <div class="form-floating">
                                        <select class="form-select" name="port" id="port">
                                            @foreach ($data as $key => $value)
                                                @if ($loop->first)
                                                    <option selected value={{ $value->port }}>{{ $value->port }}
                                                    </option>
                                                @else
                                                    <option value={{ $value->port }}>{{ $value->port }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <label for="floatingSelect" class="form-label">Database Name</label>
                                    <div class="form-floating">
                                        <select class="form-select" name="namaDB" id="namaDB">
                                            @foreach ($data as $key => $value)
                                                @if ($loop->first)
                                                    <option selected value={{ $value->namaDB }}>{{ $value->namaDB }}
                                                    </option>
                                                @else
                                                    <option value={{ $value->namaDB }}>{{ $value->namaDB }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-body">
                                        <label for="usernameDB" class="form-label">Username</label>
                                        <input type="text" class="form-query" name="usernameDB" id="usernameDB" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="card-body">
                                        <label for="passwordDB" class="form-label">Password</label>
                                        <input type="password" class="form-query" name="passwordDB" id="passwordDB">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body-table">
                            <h5 class="card-title">Result</h5>
                            <table id="resultTable" class="table table-hover table-bordered">
                                <thead class="table-head-custom">
                                    <tr>
                                        @if (isset($queryResult[0]))
                                            @foreach (array_keys((array) $queryResult[0]) as $header)
                                                <th>{{ ucfirst($header) }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($queryResult as $row)
                                        <tr>
                                            @foreach ((array) $row as $cell)
                                                <td>
                                                    @if (is_array($cell) || is_object($cell))
                                                        <pre>{{ json_encode($cell, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        {{ $cell ?? '-' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Set timeout hilangkan notif
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000); // hilang dalam 5 detik

        // Aktifkan orderby, pagination dan search
        $(document).ready(function() {
            $('#resultTable').DataTable({
                scrollX: true,
                "ordering": true,
                "paging": true,
                "searching": true,
            });
        });
    </script>
@endsection
