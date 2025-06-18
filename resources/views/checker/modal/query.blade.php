<!-- Modal -->
<form action="{{ route('chkApproveQuery') }}" method="POST" class="d-flex gap-2">
    @csrf
    <div class="modal fade" id="approvalQueryModal" tabindex="-1" aria-labelledby="approvalQueryModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">

            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Query Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label for="query-namaDB" class="col-form-label">Nama DB</label>
                            <input type="text" class="form-control bg-light" id="query-namaDB" readonly>
                        </div>
                        <div class="col">
                            <label for="query-ipHost" class="col-form-label">Source</label>
                            <input type="text" class="form-control bg-light" id="query-ipHost" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-port" class="col-form-label">Port</label>
                            <input type="text" class="form-control bg-light" id="query-port" readonly>
                        </div>
                        <div class="col">
                            <label for="query-driver" class="col-form-label">Driver</label>
                            <input type="text" class="form-control bg-light" id="query-driver" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-statusApproval" class="col-form-label">Status Approval</label>
                            <input type="text" class="form-control bg-light" id="query-statusApproval" readonly>
                        </div>
                        <div class="col">
                            <label for="query-reason" class="col-form-label">Reason</label>
                            <input type="text" class="form-control" name="reason" id="query-reason" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-supervisor" class="col-form-label">Requested By</label>
                            <input type="text" class="form-control bg-light" id="query-operator" readonly>
                        </div>
                        <div class="col">
                            <label for="query-tanggalRequest" class="col-form-label">Tanggal Request</label>
                            <input type="text" class="form-control bg-light" id="query-tanggalRequest" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-queryRequest" class="col-form-label">Query Request</label>
                            <textarea rows="3" style="resize: vertical; overflow-y: auto;" class="form-control bg-light"
                                id="query-queryRequest" readonly></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-deskripsi" class="col-form-label">Deskripsi</label>
                            <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light" id="query-deskripsi"
                                readonly></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label id="query-namaKolomLabel" for="query-namaKolom" class="col-form-label">List Nama Kolom</label>
                            <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light" id="query-namaKolom"
                                readonly></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label id="dataBeforeLabel" for="dataBeforeTable" class="col-form-label">Data Before
                                Update</label>
                            <table id="dataBeforeTable" class="table table-hover table-bordered">
                                <thead class="table-head-custom">
                                    <tr>
                                        <!-- Akan diisi oleh JavaScript -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- Akan diisi oleh JavaScript -->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <!-- Tombol Close di kiri -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <!-- Tombol Approve dan Reject di kanan -->
                    <div>
                        <input type="hidden" name="id" id="query-dataId">
                        <button type="submit" class="btn btn-primary" name="approval"
                            value="1">Approve</button>
                        <button type="submit" class="btn btn-danger" name="approval" value="0">Reject</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
