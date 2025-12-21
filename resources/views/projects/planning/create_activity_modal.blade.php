<div class="modal fade" id="createActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createActivityForm" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">{{ __('planning/modals.activity.create_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('planning/modals.activity.name_label') }} *</label>
                        <input type="text" name="task_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('planning/modals.common.start') }}</label>
                            <input type="date" name="task_start_date" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">{{ __('planning/modals.common.end') }}</label>
                            <input type="date" name="task_end_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('planning/modals.common.duration') }}</label>
                        <input type="number" name="task_duration" class="form-control" value="1">
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
