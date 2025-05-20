<!-- Modal -->
<div class="modal fade" id="logActivityModal" tabindex="-1" aria-labelledby="logActivityModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Query Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <label for="log-namaDB" class="col-form-label">Nama DB</label>
                        <input type="text" class="form-control bg-light" id="log-namaDB" readonly>
                    </div>
                    <div class="col">
                        <label for="log-ipHost" class="col-form-label">Source</label>
                        <input type="text" class="form-control bg-light" id="log-ipHost" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="log-port" class="col-form-label">Port</label>
                        <input type="text" class="form-control bg-light" id="log-port" readonly>
                    </div>
                    <div class="col">
                        <label for="log-driver" class="col-form-label">Driver</label>
                        <input type="text" class="form-control bg-light" id="log-driver" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="log-statusApproval" class="col-form-label">Status Approval</label>
                        <input type="text" class="form-control bg-light"
                            id="log-statusApproval" readonly>
                    </div>
                    <div class="col">
                        <label for="log-reason" class="col-form-label">Reason</label>
                        <input type="text" class="form-control bg-light" id="log-reason" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="log-performedBy" class="col-form-label">Performed By</label>
                        <input type="text" class="form-control bg-light" id="log-performedBy"
                            readonly>
                    </div>
                    <div class="col">
                        <label for="log-role" class="col-form-label">Role</label>
                        <input type="text" class="form-control bg-light" id="log-role" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="log-menu" class="col-form-label">Menu</label>
                        <input type="text" class="form-control bg-light" id="log-menu"
                            readonly>
                    </div>
                    <div class="col">
                        <label for="log-action" class="col-form-label">Aksi</label>
                        <input type="text" class="form-control bg-light" id="log-action" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="log-queryRequest" class="col-form-label">Query Request</label>
                        <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light" id="log-queryRequest"
                            readonly></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="log-deskripsi" class="col-form-label">Deskripsi</label>
                        <textarea rows="2" style="resize: vertical; overflow-y: auto;" class="form-control bg-light"
                            id="log-deskripsi" readonly></textarea>
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
