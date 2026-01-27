<div class="modal fade" id="createWbsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- Note: A rota aqui depende da variável $project estar disponível na view --}}
            <form action="{{ route('projects.wbs.store', $project) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" id="wbs_parent_id_input">

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('planning/modals.wbs.create_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('planning/modals.wbs.name_label') }}</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="alert alert-info small">
                        {{ __('planning/modals.wbs.child_hint') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('planning/modals.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('planning/modals.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
