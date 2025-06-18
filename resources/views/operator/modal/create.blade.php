<!-- Modal -->
<div class="modal fade" id="addDBModal" tabindex="-1" aria-labelledby="addDBModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-success">
            <div class="modal-header bg-success text-white">
                <h1 class="modal-title fs-5" id="addDBModal">Create Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('addDatabase') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="driver" class="form-label">Driver</label>
                        <div class="form-floating">
                            <select class="form-select" name="driver" id="driver" required>
                                <option selected value="" disabled>--Select Driver--</option>
                                <option name="driver" value="mysql">mysql</option>
                                <option name="driver" value="pgsql">pgsql</option>
                                <option name="driver" value="sqlite">sqlite</option>
                                <option name="driver" value="sqlsrv">sqlsrv</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="namaDB" class="col-form-label">Nama DB</label>
                        <input type="text" class="form-control" name="namaDB" id="namaDB" required>
                    </div>
                    <div class="mb-3">
                        <label for="ipHost" class="col-form-label">Source</label>
                        <input type="text" class="form-control" name="ipHost" id="ipHost" required>
                    </div>
                    <div class="mb-3">
                        <label for="port" class="col-form-label">Port</label>
                        <input type="text" class="form-control" name="port" id="port" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>
