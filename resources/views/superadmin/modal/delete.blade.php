<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModal"><i
                        class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <form action="{{ route('deleteUser') }}" method="POST" id="deleteForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <p>Apakah Anda yakin ingin <strong>menghapus data ini?</strong> Permintaan akan
                            dilaksanakan setelah
                            diapprove oleh superadmin.</p>
                        <input type="hidden" name="id" id="delete-dataId">
                    </div>
                    <div class="row">
                        <p>Mohon isi alasan delete data.</p>
                        <div>
                            <label for="reason" class="col-form-label">Reason</label>
                            <input type="text" class="form-control bg-light" name="reason"
                                id="reason" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>

                </div>
            </form>
        </div>
    </div>
</div>
