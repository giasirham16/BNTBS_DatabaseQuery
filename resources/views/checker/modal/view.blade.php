<!-- Modal -->
<div class="modal fade" id="detailQueryModal" tabindex="-1" aria-labelledby="detailQueryModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Query Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="detail-dataId">
                <div class="row">
                    <div class="col">
                        <label for="detail-namaDB" class="col-form-label">Nama DB</label>
                        <input type="text" class="form-control bg-light" name="namaDB" id="detail-namaDB" readonly>
                    </div>
                    <div class="col">
                        <label for="detail-ipHost" class="col-form-label">Source</label>
                        <input type="text" class="form-control bg-light" name="ipHost" id="detail-ipHost" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="detail-port" class="col-form-label">Port</label>
                        <input type="text" class="form-control bg-light" name="port" id="detail-port" readonly>
                    </div>
                    <div class="col">
                        <label for="detail-driver" class="col-form-label">Driver</label>
                        <input type="text" class="form-control bg-light" name="driver" id="detail-driver" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="detail-statusApproval" class="col-form-label">Status Approval</label>
                        <input type="text" class="form-control bg-light" name="statusApproval"
                            id="detail-statusApproval" readonly>
                    </div>
                    <div class="col">
                        <label for="detail-reason" class="col-form-label">Reason</label>
                        <input type="text" class="form-control bg-light" name="reason" id="detail-reason" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="detail-supervisor" class="col-form-label">Requested By</label>
                        <input type="text" class="form-control bg-light" name="operator" id="detail-operator"
                            readonly>
                    </div>
                    <div class="col">
                        <label for="detail-checker" class="col-form-label">Checker</label>
                        <input type="text" class="form-control bg-light" name="checker" id="detail-checker" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="detail-queryRequest" class="col-form-label">Query Request</label>
                        <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light"
                            id="detail-queryRequest" readonly></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="detail-deskripsi" class="col-form-label">Deskripsi</label>
                        <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light" id="detail-deskripsi"
                            readonly></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="queryResultTable" class="col-form-label">Query Result</label>
                        <table id="queryResultTable" class="table table-hover table-bordered">
                            <thead class="table-head-custom">
                                    <!-- Akan diisi oleh JavaScript -->
                            </thead>
                            <tbody>
                                    <!-- Akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
