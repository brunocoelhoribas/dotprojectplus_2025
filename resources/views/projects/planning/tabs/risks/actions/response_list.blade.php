<div class="modal-header bg-warning">
    <h5 class="modal-title text-dark">{{ __('planning/view.risks.response_list.title') }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body p-0">
    <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">

        <div class="bg-light p-2 fw-bold small text-uppercase border-bottom">
            {{ __('planning/view.risks.response_list.active_risks') }}
        </div>
        <table class="table table-bordered table-sm table-hover mb-0 align-middle">
            <thead class="bg-warning text-dark sticky-top" style="top: 0; z-index: 2;">
            <tr class="small fw-bold text-center">
                <th style="width: 50px;">{{ __('planning/view.risks.response_list.table.id') }}</th>
                <th style="width: 20%;">{{ __('planning/view.risks.response_list.table.title') }}</th>
                <th style="width: 120px;">{{ __('planning/view.risks.response_list.table.exposure_factor') }}</th>
                <th style="width: 100px;">{{ __('planning/view.risks.response_list.table.strategy') }}</th>
                <th>{{ __('planning/view.risks.response_list.table.prevention') }}</th>
                <th>{{ __('planning/view.risks.response_list.table.contingency') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($activeRisks as $risk)
            <tr>
                <td class="text-center small">{{ $risk->risk_id }}</td>
                <td class="fw-bold small">{{ $risk->risk_name }}</td>

                <td class="text-center small">
                        <span class="badge {{ $risk->exposure_factor_level }} text-uppercase" style="font-size: 0.7rem;">
                            {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                        </span>
                </td>

                <td class="text-center small">
                    {{ __('planning/view.risks.strategy.' . $risk->strategy_key) }}
                </td>

                <td class="small">{{ $risk->risk_prevention_actions ?: '-' }}</td>
                <td class="small">{{ $risk->risk_contingency_plan ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-3 text-muted small">
                    {{ __('planning/view.risks.response_list.empty') }}
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>

        @if($inactiveRisks->isNotEmpty())
        <div class="bg-secondary text-white p-2 fw-bold small text-uppercase border-top border-bottom mt-0">
            {{ __('planning/view.risks.response_list.inactive_risks') }}
        </div>
        <table class="table table-bordered table-sm table-hover mb-0 align-middle">
            <thead class="bg-light text-muted">
            <tr class="small fw-bold text-center">
                <th style="width: 50px;">{{ __('planning/view.risks.response_list.table.id') }}</th>
                <th style="width: 20%;">{{ __('planning/view.risks.response_list.table.title') }}</th>
                <th style="width: 120px;">{{ __('planning/view.risks.response_list.table.exposure_factor') }}</th>
                <th style="width: 100px;">{{ __('planning/view.risks.response_list.table.strategy') }}</th>
                <th>{{ __('planning/view.risks.response_list.table.prevention') }}</th>
                <th>{{ __('planning/view.risks.response_list.table.contingency') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($inactiveRisks as $risk)
            <tr class="text-muted">
                <td class="text-center small">{{ $risk->risk_id }}</td>
                <td class="small">{{ $risk->risk_name }}</td>
                <td class="text-center small">
                            <span class="badge {{ $risk->exposure_factor_level }} text-uppercase" style="font-size: 0.7rem; opacity: 0.7;">
                                {{ __('planning/view.risks.levels.' . $risk->risk_exposure) }}
                            </span>
                </td>
                <td class="text-center small">{{ __('planning/view.risks.strategy.' . $risk->strategy_key) }}</td>
                <td class="small">{{ $risk->risk_prevention_actions ?: '-' }}</td>
                <td class="small">{{ $risk->risk_contingency_plan ?: '-' }}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
        @endif

    </div>
</div>

<div class="modal-footer bg-light">
    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
        {{ __('planning/view.risks.response_list.close') }}
    </button>
</div>
