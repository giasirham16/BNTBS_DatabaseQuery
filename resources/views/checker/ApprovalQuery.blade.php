@extends('admin.layouts.app')

@section('title')
    Run Query | Bank NTB Syariah
@endsection

@section('content')
    @include('checker.modal.query')

    <div class="pagetitle">
        <h1>Query Approval</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body-table">
                            <div class="table-responsive">
                                <table id='queryTable' class="table table-hover table-border">
                                    <thead class="table-head-custom">
                                        <tr>
                                            <th class="text-nowrap">No</th>
                                            <th class="text-nowrap">Nama DB</th>
                                            <th class="text-nowrap">IP Host DB</th>
                                            <th class="text-nowrap">Port</th>
                                            <th class="text-nowrap">Driver</th>
                                            <th class="text-nowrap">Query Kategori</th>
                                            <th class="text-nowrap">Reason</th>
                                            <th class="text-nowrap">Status Approval</th>
                                            <th class="text-nowrap">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($approval as $key => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->namaDB }}</td>
                                                <td>{{ $item->ipHost }}</td>
                                                <td>{{ $item->port }}</td>
                                                <td>{{ $item->driver }}</td>
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
                                                        <label class="badge bg-light-danger">Reject by checker</label>
                                                    @elseif ($item->statusApproval == 4)
                                                        <label class="badge bg-light-danger">Reject by supervisor</label>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                        data-bs-target="#approvalQueryModal" data-id="{{ $item->id }}"
                                                        data-namadb="{{ $item->namaDB }}" data-iphost="{{ $item->ipHost }}"
                                                        data-port="{{ $item->port }}" data-driver="{{ $item->driver }}"
                                                        data-reason="{{ $item->reason }}"
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
            $('#queryTable').DataTable({
                scrollX: true,
                "ordering": true,
                "paging": true,
                "searching": true,
                columnDefs: [{
                        orderable: false,
                        targets: [8]
                    } // index kolom mulai dari 0
                ]
            });
        });

        // Get value ke modal view query
        document.addEventListener('DOMContentLoaded', function() {
            const viewModal = document.getElementById('approvalQueryModal');

            viewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil nilai dari atribut data-*
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-namadb');
                const ipHost = button.getAttribute('data-iphost');
                const port = button.getAttribute('data-port');
                const driver = button.getAttribute('data-driver');
                const statusApproval = button.getAttribute('data-statusApproval');
                const reason = button.getAttribute('data-reason');
                const queryRequest = button.getAttribute('data-queryRequest');
                const queryResultRaw = button.getAttribute('data-query-result');

                // Konversi status approval
                let status = "Tidak diketahui";
                if (statusApproval == 0) status = "Menunggu approval checker";
                else if (statusApproval == 1) status = "Menunggu approval supervisor";
                else if (statusApproval == 2) status = "Approved";
                else if (statusApproval == 3) status = "Reject by checker";
                else if (statusApproval == 4) status = "Reject by supervisor";

                // Set nilai input di modal
                document.getElementById('view-dataId').value = id;
                document.getElementById('view-namaDB').value = nama;
                document.getElementById('view-ipHost').value = ipHost;
                document.getElementById('view-port').value = port;
                document.getElementById('view-driver').value = driver;
                document.getElementById('view-statusApproval').value = status;
                document.getElementById('view-reason').value = reason;
                document.getElementById('view-queryRequest').value = queryRequest;
            })
        });
    </script>
@endsection
