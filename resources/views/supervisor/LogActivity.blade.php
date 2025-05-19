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
                            </div>

                            {{-- Badan table --}}
                            <div class="table-responsive">
                                <table id='dbTable' class="table table-hover table-border w-100">
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
                                                            data-bs-target="#detailQueryModal" data-id="{{ $value->id }}"
                                                            data-namadb="{{ $value->namaDB }}"
                                                            data-iphost="{{ $value->ipHost }}"
                                                            data-port="{{ $value->port }}"
                                                            data-driver="{{ $value->driver }}"
                                                            data-reason="{{ $value->reason }}"
                                                            data-operator="{{ $value->operator }}"
                                                            data-checker="{{ $value->checker ?? '-' }}"
                                                            data-deskripsi="{{ $value->deskripsi }}"
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
            $('#dbTable').DataTable({
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
            var table = $('#dbTable').DataTable();
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
