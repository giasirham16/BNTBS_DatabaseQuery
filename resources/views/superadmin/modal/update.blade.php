<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="updateUserLabel"><i
                        ></i>Konfirmasi Update</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <form action="{{ route('updateUser') }}" method="POST" id="updateUserForm">
                @csrf
                <div class="modal-body">
                    <div>
                        <label for="email" class="col-form-label">Email Baru</label>
                        <input type="email" class="form-control bg-light" name="email" id="email" required>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <!-- Tombol Close di kiri -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <!-- Tombol Update di kanan -->
                    <div>
                        <input type="hidden" name="id" id="update-dataId">
                        <button type="submit" class="btn btn-primary" name="update" value="1">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
