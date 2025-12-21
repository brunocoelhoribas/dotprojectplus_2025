<div class="modal fade" id="deleteWbsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">{{ __('planning/modals.wbs.delete_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">{{ __('planning/modals.wbs.delete_confirm') }}</p>

                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                    <div>
                        <strong>{{ __('planning/modals.wbs.delete_warning') }}</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('planning/modals.common.cancel') }}</button>

                <form id="deleteWbsForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('planning/modals.wbs.confirm_btn') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
