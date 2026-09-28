<div class="modal fade" id="revisionsModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Revision History</h5>
        <button type="button" id="revisionsModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="revisions-list">
          <div class="text-muted">Loading revisions...</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
  // Ensure the modal can be closed via the close button even if Bootstrap JS is not fully initialized
  document.addEventListener('DOMContentLoaded', function () {
    var closeBtn = document.getElementById('revisionsModalClose');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        if (window.$ && typeof $.fn.modal === 'function') {
          $('#revisionsModal').modal('hide');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
          var modalEl = document.getElementById('revisionsModal');
          var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
          modal.hide();
        }
      });
    }
  });
</script>
