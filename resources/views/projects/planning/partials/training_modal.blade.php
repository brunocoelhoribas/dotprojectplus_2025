<div class="modal fade" id="trainingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('projects.training.store', $project) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('planning/partials.training.title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('planning/partials.training.description_label') }}</label>
                        <textarea name="description" class="form-control" rows="5">{{ $training->description ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('planning/partials.common.cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('planning/partials.common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
