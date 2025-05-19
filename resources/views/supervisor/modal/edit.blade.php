<!-- Modal -->
<div class="modal fade" id="updateDBModal" tabindex="-1" aria-labelledby="updateDBModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Update Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('spvEditDatabase') }}" method="POST" id="updateForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-dataId">
                    <div class="mb-3">
                        <label for="namaDB" class="col-form-label">Nama DB</label>
                        <input type="text" class="form-control" name="namaDB" id="edit-namaDB" required>
                    </div>
                    <div class="mb-3">
                        <label for="ipHost" class="col-form-label">Source</label>
                        <input type="text" class="form-control" name="ipHost" id="edit-ipHost" required>
                    </div>
                    <div class="mb-3">
                        <label for="port" class="col-form-label">Port</label>
                        <input type="text" class="form-control" name="port" id="edit-port" required>
                    </div>
                    <div class="mb-3">
                        <label for="driver" class="col-form-label">Driver</label>
                        <input type="text" class="form-control" name="driver" id="edit-driver" required>
                    </div>
                    <div class="mb-3">
                        <label for="driver" class="col-form-label">Reason</label>
                        <input type="text" class="form-control" name="reason" id="edit-reason" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
