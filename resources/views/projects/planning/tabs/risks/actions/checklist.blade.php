<div class="modal-header bg-warning">
    <h5 class="modal-title text-dark">{{ __('planning/view.risks.checklist.title') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form action="{{ route('projects.risks.checklist.import', $project->project_id) }}" method="POST">
    @csrf
    <div class="modal-body p-0">
        <div class="table-responsive" style="max-height: 60vh; overflow-y: auto;">
            <table class="table table-bordered table-sm table-hover mb-0 align-middle">
                <thead class="bg-warning text-dark sticky-top">
                <tr class="small fw-bold text-center">
                    <th style="width: 30px;"></th>
                    <th style="width: 40px;">{{ __('planning/view.risks.checklist.table.id') }}</th>
                    <th>{{ __('planning/view.risks.checklist.table.title') }}</th>
                    <th>{{ __('planning/view.risks.checklist.table.description') }}</th>
                    <th style="width: 120px;">{{ __('planning/view.risks.checklist.table.exposure_factor') }}</th>
                    <th style="width: 100px;">{{ __('planning/view.risks.checklist.table.strategy') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($templates as $risk)
                    <tr>
                        <td class="text-center">
                            <input class="form-check-input" type="checkbox" name="selected_risks[]" value="{{ $risk->risk_id }}">
                        </td>
                        <td class="text-center small">{{ $risk->risk_id }}</td>
                        <td class="fw-bold small">{{ $risk->risk_name }}</td>
                        <td class="small text-muted">{{ Str::limit($risk->risk_description, 60) }}</td>

                        <td class="text-center small">
                                <span class="badge {{ $risk->exposure_factor_level }} text-uppercase" style="font-size: 0.7rem;">
                                    {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                                </span>
                        </td>

                        <td class="text-center small">
                            {{ __('planning/view.risks.strategy.' . $risk->strategy_key) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            {{ __('planning/view.risks.checklist.empty') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal-footer bg-light d-flex justify-content-between">
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">
            {{ __('planning/view.risks.checklist.buttons.cancel') }}
        </button>
        <button type="submit" class="btn btn-sm btn-secondary">
            {{ __('planning/view.risks.checklist.buttons.confirm') }}
        </button>
    </div>
</form>
