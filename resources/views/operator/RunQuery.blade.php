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
                                    <label for="ipHostDBDropdown" class="form-label">IP Host</label>
                                    <div class="form-floating">
                                        <select class="form-select" name="ipHost" id="ipHostDBDropdown" required>
                                            <option selected value="" disabled>--Select IP Host DB--</option>
                                            @foreach ($data as $key => $value)
                                                <option value={{ $value->ipHost }}>{{ $value->ipHost }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <label for="driverInput" class="form-label">Driver</label>
                                    <input type="text" placeholder="Autofill if IP Host Selected"
                                        class="form-query bg-light" name="driver" id="driverInput" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <label for="portInput" class="form-label">Port</label>
                                    <input type="text" placeholder="Autofill if IP Host Selected"
                                        class="form-query bg-light" name="port" id="portInput" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="card-body">
                                    <label for="namaDBDropdown" class="form-label">Nama Database</label>
                                    <div class="form-floating">
                                        <select class="form-select" name="namaDB" id="namaDBDropdown" required>
                                            <option selected value="">--Select IP Host DB First--</option>
                                        </select>
                                    </div>
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
        // // Set timeout hilangkan notif
        // setTimeout(() => {
        //     const alerts = document.querySelectorAll('.alert');
        //     alerts.forEach(alert => {
        //         const bsAlert = new bootstrap.Alert(alert);
        //         bsAlert.close();
        //     });
        // }, 5000); // hilang dalam 5 detik


        // Map data dari IP ke port & driver
        const ipMap = @json(collect($data)->keyBy('ipHost'));
        const dbData = @json($data);
        document.getElementById('ipHostDBDropdown').addEventListener('change', function() {
            const selectedIp = this.value;
            if (ipMap[selectedIp]) {
                document.getElementById('portInput').value = ipMap[selectedIp].port;
                document.getElementById('driverInput').value = ipMap[selectedIp].driver;

                const matchedData = dbData.filter(entry => entry.ipHost === selectedIp);

                let options = '<option value="" disabled>--Pilih Nama Database--</option>';
                matchedData.forEach(entry => {
                    options += `<option value="${entry.namaDB}">${entry.namaDB}</option>`;
                });
                // Isi dropdown namaDB
                document.getElementById('namaDBDropdown').innerHTML = options;

            } else {
                document.getElementById('portInput').value = '';
                document.getElementById('driverInput').value = '';
            }
        });


        // Aktifkan orderby, pagination dan search
        $(document).ready(function() {
            try {
                $('#resultTable').DataTable({
                    scrollX: true,
                    "ordering": true,
                    "paging": true,
                    "searching": true,
                });
            } catch (e) {
                console.error("DataTable error ignored");
                // Bisa juga mengabaikan error ini tanpa mengambil tindakan lebih lanjut
            }
        });
    </script>
@endsection
