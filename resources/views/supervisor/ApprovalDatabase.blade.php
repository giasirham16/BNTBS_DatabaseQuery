@extends('admin.layouts.app')

@section('title')
    Database Approval | Bank NTB Syariah
@endsection

@section('content')
    @include('supervisor.modal.database')
    @include('supervisor.modal.edit')
    @include('supervisor.modal.delete')

    <div class="pagetitle">
        <h1>Database Approval</h1>
        {{-- <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('admin/v1/dashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Run Query</li>
        </ol>
    </nav> --}}
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
                                    <label class="col-form-label" for="filter-type">Filter Berdasarkan:</label>
                                    <select class="form-select" id="filter-type">
                                        <option value="request" selected>Tanggal Request</option>
                                        <option value="approval">Tanggal Approval</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="col-form-label" for="min-date">Dari:</label>
                                    <input type="date" id="min-date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="col-form-label" for="max-date">Sampai:</label>
                                    <input type="date" id="max-date" class="form-control">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button class="btn btn-primary mt-4" id="apply-filter">Terapkan</button>
                                    <button class="btn btn-secondary mt-4" id="clear-filter">Clear</button>
                                </div>
                            </div>

                            {{-- Badan table --}}
                            <div class="table-responsive">
                                <table id='dbTable' class="table table-hover table-border w-100">
                                    <thead class="table-head-custom">
                                        <tr>
                                            <th>Id</th>
                                            <th>Nama</th>
                                            <th>Source</th>
                                            <th>Port</th>
                                            <th>Driver</th>
                                            <th>Status Approval</th>
                                            <th>Reason</th>
                                            <th>Requested By</th>
                                            <th>Checker</th>
                                            <th>Supervisor</th>
                                            <th>Tanggal Request</th>
                                            <th>Tanggal Approval</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $value)
                                            @if ($value->statusApproval != 99)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $value->namaDB }}</td>
                                                    <td>{{ $value->ipHost }}</td>
                                                    <td>{{ $value->port }}</td>
                                                    <td>{{ $value->driver }}</td>
                                                    <td>
                                                        @if ($value->statusApproval == 0)
                                                            <label class="badge bg-light-warning">(Add) Menunggu approval
                                                                checker</label>
                                                        @elseif ($value->statusApproval == 1)
                                                            <label class="badge bg-light-warning">(Add) Menunggu approval
                                                                supervisor</label>
                                                        @elseif ($value->statusApproval == 2)
                                                            <label class="badge bg-light-success">Approved</label>
                                                        @elseif ($value->statusApproval == 3)
                                                            <label class="badge bg-light-warning">(Update) Menunggu approval
                                                                checker</label>
                                                        @elseif ($value->statusApproval == 4)
                                                            <label class="badge bg-light-warning">(Update) Menunggu approval
                                                                supervisor</label>
                                                        @elseif ($value->statusApproval == 5)
                                                            <label class="badge bg-light-warning">(Delete) Menunggu approval
                                                                checker</label>
                                                        @elseif ($value->statusApproval == 6)
                                                            <label class="badge bg-light-warning">(Delete) Menunggu approval
                                                                supervisor</label>
                                                        @elseif ($value->statusApproval == 7)
                                                            <label class="badge bg-light-danger">Direject checker</label>
                                                        @elseif ($value->statusApproval == 8)
                                                            <label class="badge bg-light-danger">Direject supervisor</label>
                                                        @endif
                                                    </td>
                                                    <td>{{ $value->reason ?? '-' }}</td>
                                                    <td>{{ $value->operator ?? '-' }}</td>
                                                    <td>{{ $value->checker ?? '-' }}</td>
                                                    <td>{{ $value->supervisor ?? '-' }}</td>
                                                    <td>{{ $value->created_at }}</td>
                                                    <td>{{ $value->updated_at == $value->created_at ? '-' : $value->updated_at }}
                                                    </td>
                                                    @if ($value->statusApproval == 1 || $value->statusApproval == 4 || $value->statusApproval == 6)
                                                        <td class="text-center align-middle">
                                                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                                data-bs-target="#approveDBModal"
                                                                data-id="{{ $value->id }}"><i
                                                                    class="bi bi-check-square-fill text-success"
                                                                    style="font-size: 18px;"></i></button>
                                                        </td>
                                                    @elseif ($value->statusApproval == 2)
                                                        <td>
                                                            <div style="display: flex; gap: 10px;">
                                                                <button class="btn btn-outline-primary"
                                                                    data-bs-toggle="modal" data-bs-target="#updateDBModal"
                                                                    data-id="{{ $value->id }}"
                                                                    data-namadb="{{ $value->namaDB }}"
                                                                    data-iphost="{{ $value->ipHost }}"
                                                                    data-port="{{ $value->port }}"
                                                                    data-driver="{{ $value->driver }}"
                                                                    data-statusApproval="{{ $value->statusApproval }}"><i
                                                                        class="bi bi-pencil-square text-success"
                                                                        style="font-size: 18px;"></i></button>
                                                                <button class="btn btn-outline-primary"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteDBModal"
                                                                    data-id="{{ $value->id }}"><i
                                                                        class="bi bi-trash text-danger"
                                                                        style="font-size: 18px;"></i></button>
                                                            </div>
                                                        </td>
                                                    @else
                                                        <td>

                                                        </td>
                                                    @endif
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
            $('#dbTable').DataTable({
                scrollX: true,
                "ordering": true,
                "paging": true,
                "searching": true,
                columnDefs: [{
                        orderable: false,
                        targets: [12]
                    } // index kolom mulai dari 0
                ]
            });

            // Custom filter untuk tanggal
            var table = $('#dbTable').DataTable();
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    let filterType = $('#filter-type').val(); // 'request' atau 'approval'
                    let minDate = $('#min-date').val();
                    let maxDate = $('#max-date').val();

                    // Ambil kolom sesuai filter type
                    let targetDate = filterType === 'request' ? data[10] : data[11]; // Index kolom

                    const extractDate = str => {
                        if (!str || str.trim() === '-' || str.trim() === '') return null;
                        return str.trim().slice(0, 10); // Ambil Y-m-d dari timestamp
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

        // Get value ke modal approveDBModal
        document.addEventListener('DOMContentLoaded', function() {
            const updateModal = document.getElementById('approveDBModal');
            updateModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Get nilai dari button
                const id = button.getAttribute('data-id');

                // Set nilai input di modal
                document.getElementById('approve-dataId').value = id;
            });
        });

        // Get value ke modal edit
        document.addEventListener('DOMContentLoaded', function() {
            const updateModal = document.getElementById('updateDBModal');
            updateModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Get nilai dari button
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-namadb');
                const ipHost = button.getAttribute('data-iphost');
                const port = button.getAttribute('data-port');
                const driver = button.getAttribute('data-driver');

                // Set nilai input di modal
                document.getElementById('edit-dataId').value = id;
                document.getElementById('edit-namaDB').value = nama;
                document.getElementById('edit-ipHost').value = ipHost;
                document.getElementById('edit-port').value = port;
                document.getElementById('edit-driver').value = driver;
            });
        });

        // Get value ke modal delete
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteDBModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                // Ganti action form
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                document.getElementById('delete-dataId').value = id;
            });
        });
    </script>
@endsection
