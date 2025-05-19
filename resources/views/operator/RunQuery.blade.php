@extends('admin.layouts.app')
@include('operator.modal.view')

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
                                <div class="row">
                                    <h5 class="card-title">Query</h5>
                                    <textarea class="form-control" rows="7" placeholder="Tuliskan query disini" name="query" id="query"
                                        required></textarea>
                                </div>
                                <div class="row">
                                    <h5 class="card-title">Deskripsi</h5>
                                    <textarea class="form-control" rows="5" placeholder="Deskripsi/penjelasan query" name="deskripsi" id="deskripsi"
                                        required></textarea>
                                </div>
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
            </form>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body-table">
                            <h5 class="card-title">Result</h5>
                            <table id="resultTable" class="table table-hover table-bordered w-100">
                                <thead class="table-head-custom">
                                    <tr>
                                        <th>No</th>
                                        <th>IP Host DB</th>
                                        <th>Query Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Reason</th>
                                        <th>Status Approval</th>
                                        <th>Tanggal Request</th>
                                        <th>Tanggal Approval</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approval as $key => $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->ipHost }}</td>
                                            <td>
                                                @if (str_starts_with(strtolower($item->queryRequest), 'select'))
                                                    Select
                                                @elseif (str_starts_with(strtolower($item->queryRequest), 'insert'))
                                                    Insert
                                                @elseif (str_starts_with(strtolower($item->queryRequest), 'update'))
                                                    Update
                                                @elseif (str_starts_with(strtolower($item->queryRequest), 'delete'))
                                                    Delete
                                                @endif
                                            </td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td>{{ $item->reason ?? '-' }}</td>
                                            <td>
                                                @if ($item->statusApproval == 0)
                                                    <label class="badge bg-light-warning">Menunggu approval
                                                        checker</label>
                                                @elseif ($item->statusApproval == 1)
                                                    <label class="badge bg-light-warning">Menunggu approval
                                                        supervisor</label>
                                                @elseif ($item->statusApproval == 2)
                                                    <label class="badge bg-light-success">Approved</label>
                                                @elseif ($item->statusApproval == 3)
                                                    <label class="badge bg-light-danger">Direject checker</label>
                                                @elseif ($item->statusApproval == 4)
                                                    <label class="badge bg-light-danger">Direject supervisor</label>
                                                @endif
                                            </td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->updated_at == $item->created_at ? '-' :  $item->updated_at }}</td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#viewApprovalModal" data-id="{{ $item->id }}"
                                                    data-namadb="{{ $item->namaDB }}" data-iphost="{{ $item->ipHost }}"
                                                    data-port="{{ $item->port }}" data-driver="{{ $item->driver }}"
                                                    data-checker="{{ $item->checker ?? '-' }}"
                                                    data-supervisor="{{ $item->supervisor ?? '-' }}"
                                                    data-reason="{{ $item->reason ?? '-' }}"
                                                    data-deskripsi="{{ $item->deskripsi }}"
                                                    data-statusApproval="{{ $item->statusApproval }}"
                                                    data-queryRequest="{{ $item->queryRequest }}"
                                                    data-query-result="{{ $item->queryResult }}"><i
                                                        class="bi bi-eye-fill text-dark"
                                                        style="font-size: 18px;"></i></button>
                                            </td>
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
            $('#resultTable').DataTable({
                scrollX: true,
                "ordering": true,
                "paging": true,
                "searching": true,
                columnDefs: [{
                        orderable: false,
                        targets: [5]
                    } // index kolom mulai dari 0
                ]
            });
        });

        // Get value ke modal view query
        document.addEventListener('DOMContentLoaded', function() {
            const viewModal = document.getElementById('viewApprovalModal');

            viewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil nilai dari atribut data-*
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-namadb');
                const ipHost = button.getAttribute('data-iphost');
                const port = button.getAttribute('data-port');
                const driver = button.getAttribute('data-driver');
                const statusApproval = button.getAttribute('data-statusApproval');
                const checker = button.getAttribute('data-checker');
                const supervisor = button.getAttribute('data-supervisor');
                const reason = button.getAttribute('data-reason');
                const deskripsi = button.getAttribute('data-deskripsi');
                const queryRequest = button.getAttribute('data-queryRequest');
                const queryResultRaw = button.getAttribute('data-query-result');

                // Konversi status approval
                let status = "Tidak diketahui";
                if (statusApproval == 0) status = "Menunggu approval checker";
                else if (statusApproval == 1) status = "Menunggu approval supervisor";
                else if (statusApproval == 2) status = "Approved";
                else if (statusApproval == 3) status = "Direject checker";
                else if (statusApproval == 4) status = "Direject =supervisor";

                // Set nilai input di modal
                document.getElementById('view-dataId').value = id;
                document.getElementById('view-namaDB').value = nama;
                document.getElementById('view-ipHost').value = ipHost;
                document.getElementById('view-port').value = port;
                document.getElementById('view-driver').value = driver;
                document.getElementById('view-statusApproval').value = status;
                document.getElementById('view-checker').value = checker;
                document.getElementById('view-supervisor').value = supervisor;
                document.getElementById('view-reason').value = reason;
                document.getElementById('view-deskripsi').value = deskripsi;
                document.getElementById('view-queryRequest').value = queryRequest;

                // Set table
                const tableHead = document.querySelector('#queryResultTable thead');
                const tableBody = document.querySelector('#queryResultTable tbody');

                // Kosongkan isi table
                tableHead.innerHTML = '';
                tableBody.innerHTML = '';
                if (!queryResultRaw || queryResultRaw.length === 0) {
                    tableHead.innerHTML = '<tr><th>Data Kosong</th></tr>';
                    return;
                }

                try {
                    const queryResult = JSON.parse(queryResultRaw);
                    if (!queryResult || queryResult.length === 0) {
                        tableHead.innerHTML = '<tr><th>Data Kosong</th></tr>';
                        return;
                    }

                    // Ambil keys dari object pertama sebagai header
                    const headers = Object.keys(queryResult[0]);
                    const headerRow = headers.map(key => `<th>${key}</th>`).join('');
                    tableHead.innerHTML = `<tr>${headerRow}</tr>`;

                    // Isi data baris
                    queryResult.forEach(item => {
                        const row = headers.map(key => `<td>${item[key]}</td>`).join('');
                        tableBody.insertAdjacentHTML('beforeend', `<tr>${row}</tr>`);
                    });

                } catch (err) {
                    console.error('Gagal parse queryResult JSON:', err);
                    tableHead.innerHTML = '<tr><th>Data tidak valid</th></tr>';
                }
            });
        });
    </script>
@endsection
