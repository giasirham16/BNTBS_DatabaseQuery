@extends('admin.layouts.app')

@section('title')
    Log Activity | Bank NTB Syariah
@endsection

@section('content')
    @include('supervisor.modal.log')

    <div class="pagetitle">
        <h1>Log Activity</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body-table">
                            {{-- Filter menu --}}
                            <div class="row mb-4 align-items-end">
                                <div class="col-md-3">
                                    <label class="col-form-label" for="min-date">Tanggal Dari:</label>
                                    <input type="date" id="min-date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="col-form-label" for="max-date">Tanggal Sampai:</label>
                                    <input type="date" id="max-date" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button class="btn btn-primary mt-4" id="apply-filter">Terapkan</button>
                                    <button class="btn btn-secondary mt-4" id="clear-filter">Clear</button>
                                </div>
                                {{-- Export CSV button --}}
                                <div class="col-md-3 text-end">
                                    <a href="{{ route('log.export') }}" class="btn btn-primary mt-4">Export to CSV</a>
                                </div>
                            </div>

                            {{-- Badan table --}}
                            <div class="table-responsive">
                                <table id='logTable' class="table table-hover table-border w-100">
                                    <thead class="table-head-custom">
                                        <tr>
                                            <th>Id</th>
                                            <th>Source IP</th>
                                            <th>Menu</th>
                                            <th>Deskripsi</th>
                                            <th>Status Approval</th>
                                            <th>Performed By</th>
                                            <th>Role</th>
                                            <th>Aksi</th>
                                            <th>Tanggal</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $value)
                                            @if ($value->statusApproval != 99)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $value->ipHost }}</td>
                                                    <td>{{ $value->menu }}</td>
                                                    <td>{{ $value->deskripsi }}</td>
                                                    {{-- Status Approval --}}
                                                    <td>
                                                        @if ($value->menu == 'Database')
                                                            @if ($value->statusApproval == 0)
                                                                <label class="badge bg-light-warning">(Add) Menunggu
                                                                    approval
                                                                    checker</label>
                                                            @elseif ($value->statusApproval == 1)
                                                                <label class="badge bg-light-warning">(Add) Menunggu
                                                                    approval
                                                                    supervisor</label>
                                                            @elseif ($value->statusApproval == 2)
                                                                <label class="badge bg-light-success">Approved</label>
                                                            @elseif ($value->statusApproval == 3)
                                                                <label class="badge bg-light-warning">(Update) Menunggu
                                                                    approval
                                                                    checker</label>
                                                            @elseif ($value->statusApproval == 4)
                                                                <label class="badge bg-light-warning">(Update) Menunggu
                                                                    approval
                                                                    supervisor</label>
                                                            @elseif ($value->statusApproval == 5)
                                                                <label class="badge bg-light-warning">(Delete) Menunggu
                                                                    approval
                                                                    checker</label>
                                                            @elseif ($value->statusApproval == 6)
                                                                <label class="badge bg-light-warning">(Delete) Menunggu
                                                                    approval
                                                                    supervisor</label>
                                                            @elseif ($value->statusApproval == 7)
                                                                <label class="badge bg-light-danger">Direject
                                                                    checker</label>
                                                            @elseif ($value->statusApproval == 8)
                                                                <label class="badge bg-light-danger">Direject
                                                                    supervisor</label>
                                                            @endif
                                                        @else
                                                            @if ($value->statusApproval == 0)
                                                                <label class="badge bg-light-warning">Menunggu approval
                                                                    checker</label>
                                                            @elseif ($value->statusApproval == 1)
                                                                <label class="badge bg-light-warning">Menunggu approval
                                                                    supervisor</label>
                                                            @elseif ($value->statusApproval == 2)
                                                                <label class="badge bg-light-success">Approved</label>
                                                            @elseif ($value->statusApproval == 3)
                                                                <label class="badge bg-light-danger">Direject
                                                                    checker</label>
                                                            @elseif ($value->statusApproval == 4)
                                                                <label class="badge bg-light-danger">Direject
                                                                    supervisor</label>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->performedBy }}</td>
                                                    <td>{{ $value->role }}</td>
                                                    <td>{{ $value->action }}</td>
                                                    <td>{{ $value->created_at }}</td>
                                                    {{-- Aksi --}}
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                            data-bs-target="#logActivityModal" data-id="{{ $value->id }}"
                                                            data-namadb="{{ $value->namaDB }}"
                                                            data-iphost="{{ $value->ipHost }}"
                                                            data-port="{{ $value->port }}"
                                                            data-driver="{{ $value->driver }}"
                                                            data-reason="{{ $value->reason }}"
                                                            data-deskripsi="{{ $value->deskripsi }}"
                                                            data-menu="{{ $value->menu }}"
                                                            data-performedBy="{{ $value->performedBy }}"
                                                            data-role="{{ $value->role }}"
                                                            data-action="{{ $value->action }}"
                                                            data-statusApproval="{{ $value->statusApproval }}"
                                                            data-queryRequest="{{ $value->queryRequest }}"
                                                            data-query-result="{{ $value->queryResult }}"><i
                                                                class="bi bi-eye-fill text-dark"
                                                                style="font-size: 18px;"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


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
        }, 4000); // hilang dalam 4 detik

        // Aktifkan orderby, pagination dan search
        $(document).ready(function() {
            $('#logTable').DataTable({
                scrollX: true,
                "ordering": true,
                "paging": true,
                "searching": true,
                columnDefs: [{
                        orderable: false,
                        targets: [9]
                    } // index kolom mulai dari 0
                ]
            });

            // Custom filter untuk tanggal
            var table = $('#logTable').DataTable();
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    let minDate = $('#min-date').val();
                    let maxDate = $('#max-date').val();

                    // Ambil kolom Tanggal Request (misalnya index ke-9)
                    let targetDate = data[8];

                    const extractDate = str => {
                        if (!str || str.trim() === '-' || str.trim() === '') return null;
                        return str.trim().slice(0, 10);
                    };

                    targetDate = extractDate(targetDate);

                    const inRange = (!minDate || targetDate >= minDate) &&
                        (!maxDate || targetDate <= maxDate);

                    return inRange;
                }
            );

            // Apply filter on button click
            $('#apply-filter').on('click', function() {
                table.draw();
            });

            // Clear filter
            $('#clear-filter').on('click', function() {
                $('#min-date').val('');
                $('#max-date').val('');
                table.draw();
            });
        });

        // Get value ke modal log activity
        document.addEventListener('DOMContentLoaded', function() {
            const logModal = document.getElementById('logActivityModal');

            // Detail Query View Modal
            logModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil nilai dari atribut data-*

                const nama = button.getAttribute('data-namadb');
                const ipHost = button.getAttribute('data-iphost');
                const port = button.getAttribute('data-port');
                const driver = button.getAttribute('data-driver');
                const reason = button.getAttribute('data-reason');
                const deskripsi = button.getAttribute('data-deskripsi');
                const menu = button.getAttribute('data-menu');
                const performedBy = button.getAttribute('data-performedBy');
                const role = button.getAttribute('data-role');
                const action = button.getAttribute('data-action');
                const statusApproval = button.getAttribute('data-statusApproval');
                const queryRequest = button.getAttribute('data-queryRequest');
                const queryResultRaw = button.getAttribute('data-query-result');

                // Konversi status approval
                let status = "Tidak diketahui";
                if (menu == 'Query') {
                    if (statusApproval == 0) status = "Menunggu approval checker";
                    else if (statusApproval == 1) status = "Menunggu approval supervisor";
                    else if (statusApproval == 2) status = "Approved";
                    else if (statusApproval == 3) status = "Reject by checker";
                    else if (statusApproval == 4) status = "Reject by supervisor";
                } else {
                    if (statusApproval == 0) status = "(Add) Menunggu approval checker";
                    else if (statusApproval == 1) status = "(Add) Menunggu approval supervisor";
                    else if (statusApproval == 2) status = "Approved";
                    else if (statusApproval == 3) status = "(Update) Menunggu approval checker";
                    else if (statusApproval == 4) status = "(Update) Menunggu approval supervisor";
                    else if (statusApproval == 5) status = "(Delete) Menunggu approval checker";
                    else if (statusApproval == 6) status = "(Delete) Menunggu approval supervisor";
                    else if (statusApproval == 7) status = "Direject checker";
                    else if (statusApproval == 8) status = "Direject supervisor";

                }

                // Set nilai input di modal
                document.getElementById('log-namaDB').value = nama;
                document.getElementById('log-ipHost').value = ipHost;
                document.getElementById('log-port').value = port;
                document.getElementById('log-driver').value = driver;
                document.getElementById('log-menu').value = menu;
                document.getElementById('log-performedBy').value = performedBy;
                document.getElementById('log-role').value = role;
                document.getElementById('log-action').value = action;
                document.getElementById('log-reason').value = reason;
                document.getElementById('log-deskripsi').value = deskripsi;
                document.getElementById('log-statusApproval').value = status;
                document.getElementById('log-queryRequest').value = queryRequest;

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
            })
        });
    </script>
@endsection
