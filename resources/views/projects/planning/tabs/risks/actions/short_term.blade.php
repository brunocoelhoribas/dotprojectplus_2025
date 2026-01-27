<div class="modal-header bg-warning">
    <h5 class="modal-title text-dark">{{ __('planning/view.risks.short_term.title') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-0">
    <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">

        <div class="bg-light p-2 fw-bold small text-uppercase border-bottom">
            {{ __('planning/view.risks.short_term.active_risks') }}
        </div>
        <table class="table table-bordered table-sm table-hover mb-0 align-middle">
            <thead class="bg-warning text-dark sticky-top" style="top: 0; z-index: 2;">
            <tr class="small fw-bold text-center">
                <th style="width: 40px;">{{ __('planning/view.risks.short_term.table.id') }}</th>
                <th>{{ __('planning/view.risks.short_term.table.title') }}</th>
                <th>{{ __('planning/view.risks.short_term.table.description') }}</th>
                <th>{{ __('planning/view.risks.short_term.table.probability') }}</th>
                <th>{{ __('planning/view.risks.short_term.table.impact') }}</th>
                <th>{{ __('planning/view.risks.short_term.table.exposure_factor') }}</th>
                <th>{{ __('planning/view.risks.short_term.table.status') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($activeRisks as $risk)
                <tr>
                    <td class="text-center small">{{ $risk->risk_id }}</td>
                    <td class="fw-bold small">{{ $risk->risk_name }}</td>
                    <td class="small text-muted">{{ Str::limit($risk->risk_description, 50) }}</td>

                    <td class="small">{{ __('planning/view.risks.levels.' . $risk->probability_text) }}</td>
                    <td class="small">{{ __('planning/view.risks.levels.' . $risk->impact_text) }}</td>

                    <td class="text-center small">
                        <span class="badge {{ $risk->exposure_factor_level }} text-uppercase" style="font-size: 0.7rem;">
                            {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                        </span>
                    </td>

                    <td class="small text-center">{{ __('planning/view.risks.status.' . $risk->status_key) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-3 text-muted small">
                        {{ __('planning/view.risks.short_term.empty') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @if($inactiveRisks->isNotEmpty())
            <div class="bg-secondary text-white p-2 fw-bold small text-uppercase border-top border-bottom mt-0">
                {{ __('planning/view.risks.short_term.inactive_risks') }}
            </div>
            <table class="table table-bordered table-sm table-hover mb-0 align-middle">
                <thead class="bg-light text-muted">
                <tr class="small fw-bold text-center">
                    <th style="width: 40px;">{{ __('planning/view.risks.short_term.table.id') }}</th>
                    <th>{{ __('planning/view.risks.short_term.table.title') }}</th>
                    <th>{{ __('planning/view.risks.short_term.table.description') }}</th>
                    <th>{{ __('planning/view.risks.short_term.table.probability') }}</th>
                    <th>{{ __('planning/view.risks.short_term.table.impact') }}</th>
                    <th>{{ __('planning/view.risks.short_term.table.exposure_factor') }}</th>
                    <th>{{ __('planning/view.risks.short_term.table.status') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($inactiveRisks as $risk)
                    <tr class="text-muted">
                        <td class="text-center small">{{ $risk->risk_id }}</td>
                        <td class="small">{{ $risk->risk_name }}</td>
                        <td class="small">{{ Str::limit($risk->risk_description, 50) }}</td>
                        <td class="small">{{ __('planning/view.risks.levels.' . $risk->probability_text) }}</td>
                        <td class="small">{{ __('planning/view.risks.levels.' . $risk->impact_text) }}</td>
                        <td class="text-center small">
                            <span class="badge {{ $risk->exposure_factor_level }} text-uppercase" style="font-size: 0.7rem; opacity: 0.7;">
                                {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                            </span>
                        </td>
                        <td class="small text-center">{{ __('planning/view.risks.status.' . $risk->status_key) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
        {{ __('planning/view.risks.short_term.close') }}
    </button>
</div>
