<div class="modal fade" id="approveDBModal" tabindex="-1" aria-labelledby="approveDBModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-warning">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="approveConfirmLabel"><i
                        class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Approve</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <form action="{{ route('chkApproveDatabase') }}" method="POST" id="approveForm">
                @csrf
                <div class="modal-body">
                    <p>Mohon isi alasan approve/reject permintaan.</p>
                    <div>
                        <label for="reasonApproval" class="col-form-label">Reason</label>
                        <input type="text" class="form-control bg-light" name="reasonApproval" id="reasonApproval" required>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <!-- Tombol Close di kiri -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <!-- Tombol Approve dan Reject di kanan -->
                    <div>
                        <input type="hidden" name="id" id="approve-dataId">
                        <button type="submit" class="btn btn-primary" name="approval" value="1">Approve</button>
                        <button type="submit" class="btn btn-danger" name="approval" value="0">Reject</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
