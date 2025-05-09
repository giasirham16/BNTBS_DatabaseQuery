<!-- Modal -->
<div class="modal fade" id="viewApprovalModal" tabindex="-1" aria-labelledby="viewApprovalModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Query Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="view-dataId">
                <div class="row">
                    <div class="col">
                        <label for="view-namaDB" class="col-form-label">Nama DB</label>
                        <input type="text" class="form-control bg-light" name="namaDB" id="view-namaDB" readonly>
                    </div>
                    <div class="col">
                        <label for="view-ipHost" class="col-form-label">Source</label>
                        <input type="text" class="form-control bg-light" name="ipHost" id="view-ipHost" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="view-port" class="col-form-label">Port</label>
                        <input type="text" class="form-control bg-light" name="port" id="view-port" readonly>
                    </div>
                    <div class="col">
                        <label for="view-driver" class="col-form-label">Driver</label>
                        <input type="text" class="form-control bg-light" name="driver" id="view-driver" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="view-statusApproval" class="col-form-label">Status Approval</label>
                        <input type="text" class="form-control bg-light" name="statusApproval"
                            id="view-statusApproval" readonly>
                    </div>
                    <div class="col">
                        <label for="view-reason" class="col-form-label">Reason</label>
                        <input type="text" class="form-control bg-light" name="reason" id="view-reason" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="view-queryRequest" class="col-form-label">Query Request</label>
                        <input type="text-area" class="form-control bg-light" id="view-queryRequest" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="queryResultTable" class="col-form-label">Query Result</label>
                        <table id="queryResultTable" class="table table-hover table-bordered">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
