@extends('admin.layouts.app')

@section('title')
    Manage User | Bank NTB Syariah
@endsection

@section('content')
    @include('superadmin.modal.create')
    @include('superadmin.modal.approve')
    @include('superadmin.modal.delete')

    <div class="pagetitle">
        <h1>Manage User</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="container-fluid">
            <!-- Button trigger modal -->
            <button type="button" style="margin-bottom: 10px" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#addDBModal">
                Create Data
            </button>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body-table">
                            <div class="table-responsive">
                                <table id='dbTable' class="table table-hover table-border">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Username</th>
                                            <th>Reason</th>
                                            <th>Status Approval</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $value)
                                            @if ($value->statusApproval != 99)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $value->username }}</td>
                                                    <td>{{ $value->reason ?? '-' }}</td>
                                                    <td>
                                                        @if ($value->statusApproval == 0)
                                                            <label class="badge bg-light-warning">(Add) Menunggu approval
                                                                superadmin2</label>
                                                        @elseif ($value->statusApproval == 1)
                                                            <label class="badge bg-light-danger">(Add) Menunggu approval
                                                                superadmin1</label>
                                                        @elseif ($value->statusApproval == 2)
                                                            <label class="badge bg-light-success">Approved</label>
                                                        @elseif ($value->statusApproval == 3)
                                                            <label class="badge bg-light-danger">(Delete) Menunggu approval
                                                                superadmin2</label>
                                                        @elseif ($value->statusApproval == 4)
                                                            <label class="badge bg-light-danger">(Delete) Menunggu approval
                                                                superadmin1</label>
                                                        @elseif ($value->statusApproval == 5)
                                                            <label class="badge bg-light-warning">Direject
                                                                superadmin2</label>
                                                        @elseif ($value->statusApproval == 6)
                                                            <label class="badge bg-light-danger">Direject
                                                                superadmin1</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div style="display: flex; gap: 10px;">
                                                            @if ($value->statusApproval != 2)
                                                                <button class="btn btn-outline-primary"
                                                                    data-bs-toggle="modal" data-bs-target="#approveDBModal"
                                                                    data-id="{{ $value->id }}"><i
                                                                        class="bi bi-pencil-square text-success"
                                                                        style="font-size: 18px;"></i></button>
                                                            @endif
                                                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                                data-bs-target="#deleteDBModal"
                                                                data-id="{{ $value->id }}"
                                                                data-action2="{{ route('deleteUser', ['id' => '__ID__']) }}"><i
                                                                    class="bi bi-trash text-danger"
                                                                    style="font-size: 18px;"></i></button>
                                                        </div>
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
                "ordering": true,
                "paging": true,
                "searching": true,
                columnDefs: [{
                        orderable: false,
                        targets: [4]
                    } // index kolom mulai dari 0
                ]
            });
        });

        // Get value ke modal edit
        document.addEventListener('DOMContentLoaded', function() {
            const approveModal = document.getElementById('approveDBModal');
            approveModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Get nilai dari button
                const id = button.getAttribute('data-id');

                // Set nilai input di modal
                document.getElementById('approve-dataId').value = id;
            });
        });

        // Get value ke modal delete
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteDBModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                // Ganti action form
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                let actionTemplate = button.getAttribute('data-action2');
                actionTemplate = actionTemplate.replace('__ID__', id);
                const form = document.getElementById('deleteForm');
                // console.log(actionTemplate);
                form.action = actionTemplate;
            });
        });
    </script>
@endsection
