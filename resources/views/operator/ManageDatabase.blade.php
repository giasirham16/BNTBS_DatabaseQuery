@extends('admin.layouts.app')

@section('title')
    Run Query | Bank NTB Syariah
@endsection

@section('content')
    @include('operator.modal.create')
    @include('operator.modal.edit')
    @include('operator.modal.delete')
    <div class="pagetitle">
        <h1>Manage Database</h1>
        {{-- <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('admin/v1/dashboard')}}">Home</a></li>
            <li class="breadcrumb-item active">Run Query</li>
        </ol>
    </nav> --}}
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
                                            <th>Id</th>
                                            <th>Nama</th>
                                            <th>Source</th>
                                            <th>Port</th>
                                            <th>Driver</th>
                                            <th>Status</th>
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
                                                            <label class="badge bg-light-danger">(Add) Menunggu approval
                                                                supervisor</label>
                                                        @elseif ($value->statusApproval == 2)
                                                            <label class="badge bg-light-success">Approved</label>
                                                        @elseif ($value->statusApproval == 3)
                                                            <label class="badge bg-light-warning">(Update) Menunggu approval
                                                                checker</label>
                                                        @elseif ($value->statusApproval == 4)
                                                            <label class="badge bg-light-danger">(Update) Menunggu approval
                                                                supervisor</label>
                                                        @elseif ($value->statusApproval == 4)
                                                            <label class="badge bg-light-warning">(Delete) Menunggu approval
                                                                checker</label>
                                                        @elseif ($value->statusApproval == 4)
                                                            <label class="badge bg-light-danger">(Delete) Menunggu approval
                                                                supervisor</label>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div style="display: flex; gap: 10px;">
                                                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                                data-bs-target="#updateDBModal"
                                                                data-id="{{ $value->id }}"
                                                                data-namadb="{{ $value->namaDB }}"
                                                                data-iphost="{{ $value->ipHost }}"
                                                                data-port="{{ $value->port }}"
                                                                data-driver="{{ $value->driver }}"
                                                                data-statusApproval="{{ $value->statusApproval }}"
                                                                data-action="{{ route('editDatabase', ['id' => '__ID__']) }}"><i
                                                                    class="bi bi-pencil-square text-success"
                                                                    style="font-size: 18px;"></i></button>
                                                            <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                                data-bs-target="#deleteDBModal"
                                                                data-id="{{ $value->id }}"
                                                                data-action2="{{ route('deleteDatabase', ['id' => '__ID__']) }}"><i
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

                // Ganti action form
                let actionTemplate = button.getAttribute('data-action');
                actionTemplate = actionTemplate.replace('__ID__', id);
                const form = document.getElementById('updateForm');
                form.action = actionTemplate;
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
