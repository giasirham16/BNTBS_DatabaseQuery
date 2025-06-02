@extends('admin.layouts.app')

@section('title')
    Query Approval | Bank NTB Syariah
@endsection

@section('content')
    @include('supervisor.modal.query')
    @include('supervisor.modal.view')

    <div class="pagetitle">
        <h1>Query Approval</h1>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="container-fluid">

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
                                <table id='queryTable' class="table table-hover table-border w-100">
                                    <thead class="table-head-custom">
                                        <tr>
                                            <th>No</th>
                                            <th class="text-nowrap">IP Host DB</th>
                                            <th>Query Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Status Approval</th>
                                            <th>Requested By</th>
                                            <th>Checker</th>
                                            <th>Supervisor</th>
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
                                                <td>{{ $item->operator }}</td>
                                                <td>{{ $item->checker ?? '-' }}</td>
                                                <td>{{ $item->supervisor ?? '-' }}</td>
                                                <td>{{ $item->created_at }}</td>
                                                <td>{{ $item->updated_at == $item->created_at ? '-' : $item->updated_at }}
                                                </td>
                                                @if ($item->statusApproval == 1)
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                            data-bs-target="#approvalQueryModal"
                                                            data-id="{{ $item->id }}"
                                                            data-namadb="{{ $item->namaDB }}"
                                                            data-iphost="{{ $item->ipHost }}"
                                                            data-port="{{ $item->port }}"
                                                            data-driver="{{ $item->driver }}"
                                                            data-reason="{{ $item->reason }}"
                                                            data-operator="{{ $item->operator }}"
                                                            data-checker="{{ $item->checker ?? '-' }}"
                                                            data-deskripsi="{{ $item->deskripsi }}"
                                                            data-statusApproval="{{ $item->statusApproval }}"
                                                            data-queryRequest="{{ $item->queryRequest }}"
                                                            data-before="{{ $item->updateBefore }}"
                                                            data-after="{{ $item->updateAfter }}"><i
                                                                class="bi bi-check-square-fill text-success"
                                                                style="font-size: 18px;"></i></button>
                                                    </td>
                                                @else
                                                    <td class="text-center align-middle">
                                                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                                            data-bs-target="#detailQueryModal"
                                                            data-id="{{ $item->id }}"
                                                            data-namadb="{{ $item->namaDB }}"
                                                            data-iphost="{{ $item->ipHost }}"
                                                            data-port="{{ $item->port }}"
                                                            data-driver="{{ $item->driver }}"
                                                            data-reason="{{ $item->reason }}"
                                                            data-operator="{{ $item->operator }}"
                                                            data-checker="{{ $item->checker ?? '-' }}"
                                                            data-deskripsi="{{ $item->deskripsi }}"
                                                            data-statusApproval="{{ $item->statusApproval }}"
                                                            data-tanggalRequest="{{ $item->created_at }}"
                                                            data-tanggalApproval="{{ $item->updated_at }}"
                                                            data-queryRequest="{{ $item->queryRequest }}"
                                                            data-query-result="{{ $item->queryResult }}"><i
                                                                class="bi bi-eye-fill text-dark"
                                                                style="font-size: 18px;"></i></button>
                                                    </td>
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
        // }, 4000); // hilang dalam 4 detik

        // Aktifkan orderby, pagination dan search
        $(document).ready(function() {
            $('#queryTable').DataTable({
                scrollX: true,
                "ordering": true,
                "paging": true,
                "searching": true,
                columnDefs: [{
                        orderable: false,
                        targets: [10]
                    } // index kolom mulai dari 0
                ]
            });

            // Custom filter untuk tanggal
            var table = $('#queryTable').DataTable();
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    let filterType = $('#filter-type').val(); // 'request' atau 'approval'
                    let minDate = $('#min-date').val();
                    let maxDate = $('#max-date').val();

                    // Ambil kolom sesuai filter type
                    let targetDate = filterType === 'request' ? data[8] : data[9]; // Index kolom

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

        // Fungsi load data ke table
        function renderTable(tableId, jsonData) {
            const tableHead = document.querySelector(`#${tableId} thead`);
            const tableBody = document.querySelector(`#${tableId} tbody`);

            // Kosongkan isi table
            tableHead.innerHTML = '';
            tableBody.innerHTML = '';

            if (!jsonData || jsonData.length === 0) {
                tableHead.innerHTML = '<tr><th>Data Kosong</th></tr>';
                return;
            }

            try {
                const data = typeof jsonData === 'string' ? JSON.parse(jsonData) : jsonData;

                if (!data || data.length === 0) {
                    tableHead.innerHTML = '<tr><th>Data Kosong</th></tr>';
                    return;
                }

                const headers = Object.keys(data[0]);
                const headerRow = headers.map(key => `<th>${key}</th>`).join('');
                tableHead.innerHTML = `<tr>${headerRow}</tr>`;

                data.forEach(item => {
                    const row = headers.map(key => `<td>${item[key]}</td>`).join('');
                    tableBody.insertAdjacentHTML('beforeend', `<tr>${row}</tr>`);
                });

            } catch (err) {
                console.error(`Gagal parse data untuk table #${tableId}:`, err);
                tableHead.innerHTML = '<tr><th>Data tidak valid</th></tr>';
            }
        }

        // Get value ke modal approval query dan detail query
        document.addEventListener('DOMContentLoaded', function() {
            const approvalModal = document.getElementById('approvalQueryModal');
            const detailModal = document.getElementById('detailQueryModal');

            // Approval Query View Modal
            approvalModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil nilai dari atribut data-*
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-namadb');
                const ipHost = button.getAttribute('data-iphost');
                const port = button.getAttribute('data-port');
                const driver = button.getAttribute('data-driver');
                const operator = button.getAttribute('data-operator');
                const checker = button.getAttribute('data-checker');
                const deskripsi = button.getAttribute('data-deskripsi');
                const reason = button.getAttribute('data-reason');
                const statusApproval = button.getAttribute('data-statusApproval');
                const queryRequest = button.getAttribute('data-queryRequest');
                const dataBeforeRaw = button.getAttribute('data-before');
                const dataAfterRaw = button.getAttribute('data-after');

                // Konversi status approval
                let status = "Tidak diketahui";
                if (statusApproval == 0) status = "Menunggu approval checker";
                else if (statusApproval == 1) status = "Menunggu approval supervisor";
                else if (statusApproval == 2) status = "Approved";
                else if (statusApproval == 3) status = "Reject by checker";
                else if (statusApproval == 4) status = "Reject by supervisor";

                // Set nilai input di modal
                document.getElementById('query-dataId').value = id;
                document.getElementById('query-namaDB').value = nama;
                document.getElementById('query-ipHost').value = ipHost;
                document.getElementById('query-port').value = port;
                document.getElementById('query-driver').value = driver;
                document.getElementById('query-operator').value = operator;
                document.getElementById('query-checker').value = checker;
                document.getElementById('query-deskripsi').value = deskripsi;
                document.getElementById('query-statusApproval').value = status;
                document.getElementById('query-queryRequest').value = queryRequest;

                // Deteksi apakah query adalah update
                const isUpdateQuery = queryRequest.toUpperCase().startsWith('UPDATE');

                // Ambil elemen wrapper
                const beforeWrapper = document.getElementById('dataBeforeWrapper');
                const afterWrapper = document.getElementById('dataAfterWrapper');

                // Render table before update
                renderTable('dataBeforeTable', dataBeforeRaw);
                renderTable('dataAfterTable', dataAfterRaw);

                // Tampilkan atau sembunyikan
                beforeWrapper.style.display = isUpdateQuery ? 'block' : 'none';
                afterWrapper.style.display = isUpdateQuery ? 'block' : 'none';

            })

            // Detail Query View Modal
            detailModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Ambil nilai dari atribut data-*
                const id = button.getAttribute('data-id');
                const nama = button.getAttribute('data-namadb');
                const ipHost = button.getAttribute('data-iphost');
                const port = button.getAttribute('data-port');
                const driver = button.getAttribute('data-driver');
                const operator = button.getAttribute('data-operator');
                const checker = button.getAttribute('data-checker');
                const reason = button.getAttribute('data-reason');
                const tanggalRequest = button.getAttribute('data-tanggalRequest');
                const tanggalApproval = button.getAttribute('data-tanggalApproval');
                const deskripsi = button.getAttribute('data-deskripsi');
                const statusApproval = button.getAttribute('data-statusApproval');
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
                document.getElementById('detail-namaDB').value = nama;
                document.getElementById('detail-ipHost').value = ipHost;
                document.getElementById('detail-port').value = port;
                document.getElementById('detail-driver').value = driver;
                document.getElementById('detail-operator').value = operator;
                document.getElementById('detail-checker').value = checker;
                document.getElementById('detail-reason').value = reason;
                document.getElementById('detail-deskripsi').value = deskripsi;
                document.getElementById('detail-tanggalRequest').value = tanggalRequest;
                document.getElementById('detail-tanggalApproval').value = tanggalApproval;
                document.getElementById('detail-statusApproval').value = status;
                document.getElementById('detail-queryRequest').value = queryRequest;

                // Render table query result
                renderTable('queryResultTable', queryResultRaw);
            })
        });
    </script>
@endsection
