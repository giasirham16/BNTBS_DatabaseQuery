<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteDBModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-danger">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteConfirmLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin <strong>menghapus data ini?</strong> Permintaan akan dilaksanakan setelah diapprove oleh atasan.</p>
        </div>
        <div class="modal-footer">
          <form method="POST" id="deleteForm">
            @csrf
            @method('PUT')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  