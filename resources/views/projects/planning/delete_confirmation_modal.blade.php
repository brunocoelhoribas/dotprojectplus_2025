<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('planning/modals.activity.delete_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">{{ __('planning/modals.activity.delete_text') }}</p>
                <p class="text-muted small mb-0">{{ __('planning/modals.activity.delete_hint') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('planning/modals.common.cancel') }}</button>
                <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">{{ __('planning/modals.common.confirm_delete') }}</button>
            </div>
        </div>
    </div>
</div>

<form id="deleteActivityForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
