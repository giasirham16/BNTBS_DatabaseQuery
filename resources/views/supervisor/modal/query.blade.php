<!-- Modal -->
<form action="{{ route('spvApproveQuery') }}" method="POST" class="d-flex gap-2">
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
                            <input type="text" class="form-control bg-light" name="namaDB" id="query-namaDB"
                                readonly>
                        </div>
                        <div class="col">
                            <label for="query-ipHost" class="col-form-label">Source</label>
                            <input type="text" class="form-control bg-light" name="ipHost" id="query-ipHost"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-port" class="col-form-label">Port</label>
                            <input type="text" class="form-control bg-light" name="port" id="query-port" readonly>
                        </div>
                        <div class="col">
                            <label for="query-driver" class="col-form-label">Driver</label>
                            <input type="text" class="form-control bg-light" name="driver" id="query-driver"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-statusApproval" class="col-form-label">Status Approval</label>
                            <input type="text" class="form-control bg-light" name="statusApproval"
                                id="query-statusApproval" readonly>
                        </div>
                        <div class="col">
                            <label for="query-reason" class="col-form-label">Reason</label>
                            <input type="text" class="form-control" name="reason" id="query-reason" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-supervisor" class="col-form-label">Requested By</label>
                            <input type="text" class="form-control bg-light" name="operator" id="query-operator"
                                readonly>
                        </div>
                        <div class="col">
                            <label for="query-checker" class="col-form-label">Checker</label>
                            <input type="text" class="form-control bg-light" name="checker" id="query-checker"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="query-queryRequest" class="col-form-label">Query Request</label>
                            <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light"
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
