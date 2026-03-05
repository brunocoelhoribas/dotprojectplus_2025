<form id="budgetForm" action="{{ route('projects.costs.budget.update', $project->project_id) }}" method="POST" onsubmit="submitBudgetForm(event)">
    @csrf

    <div class="modal-header bg-warning py-2">
        <h5 class="modal-title text-dark fw-bold">{{ __('planning/view.cost.budget.title') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body p-0">
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-bordered table-sm small mb-0 border-dark" style="font-size: 0.8rem;">
                <thead class="text-center bg-warning sticky-top" style="z-index: 10;">
                <tr>
                    <th class="bg-warning text-dark border-dark" style="min-width: 250px;">
                        {{ __('planning/view.cost.budget.table.item') }}
                    </th>
                    @foreach($months as $m)
                        <th class="bg-warning text-dark border-dark" style="min-width: 80px;">{{ $m['label'] }}</th>
                    @endforeach
                    <th class="bg-warning text-dark border-dark fw-bold" style="min-width: 100px;">
                        {{ __('planning/view.cost.budget.table.total') }}
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white">

                <tr class="bg-secondary bg-opacity-25 fw-bold">
                    <td colspan="{{ count($months) + 2 }}" class="text-center">
                        {{ __('planning/view.cost.budget.table.section_hr') }}
                    </td>
                </tr>
                @foreach($hrRows as $row)
                    <tr>
                        <td class="text-truncate" style="max-width: 250px;" title="{{ $row['name'] }}">
                            {{ $row['name'] }}
                        </td>
                        @foreach($months as $m)
                            <td class="text-end">{{ number_format($row['monthly'][$m['key']], 2, ',', '.') }}</td>
                        @endforeach
                        <td class="text-end fw-bold">{{ number_format($row['total'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold bg-light">
                    <td class="text-end">{{ __('planning/view.cost.budget.table.subtotal_hr') }}</td>
                    @foreach($months as $m)
                        <td class="text-end">{{ number_format($hrRows->sum(fn($r) => $r['monthly'][$m['key']]), 2, ',', '.') }}</td>
                    @endforeach
                    <td class="text-end">{{ number_format($hrRows->sum('total'), 2, ',', '.') }}</td>
                </tr>

                <tr class="bg-secondary bg-opacity-25 fw-bold">
                    <td colspan="{{ count($months) + 2 }}" class="text-center">
                        {{ __('planning/view.cost.budget.table.section_non_hr') }}
                    </td>
                </tr>
                @foreach($nonHrRows as $row)
                    <tr>
                        <td class="text-truncate" style="max-width: 250px;" title="{{ $row['name'] }}">
                            {{ $row['name'] }}
                        </td>
                        @foreach($months as $m)
                            <td class="text-end">{{ number_format($row['monthly'][$m['key']], 2, ',', '.') }}</td>
                        @endforeach
                        <td class="text-end fw-bold">{{ number_format($row['total'], 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold bg-light">
                    <td class="text-end">{{ __('planning/view.cost.budget.table.subtotal_non_hr') }}</td>
                    @foreach($months as $m)
                        <td class="text-end">{{ number_format($nonHrRows->sum(fn($r) => $r['monthly'][$m['key']]), 2, ',', '.') }}</td>
                    @endforeach
                    <td class="text-end">{{ number_format($nonHrRows->sum('total'), 2, ',', '.') }}</td>
                </tr>

                <tr class="bg-secondary bg-opacity-25 fw-bold">
                    <td colspan="{{ count($months) + 2 }}" class="text-center">
                        {{ __('planning/view.cost.budget.table.section_reserve') }}
                    </td>
                </tr>
                @forelse($reserveRows as $row)
                    <tr>
                        <td class="text-truncate" style="max-width: 250px;" title="{{ $row['name'] }}">
                            {{ $row['name'] }}
                        </td>
                        @foreach($months as $m)
                            <td class="text-end">{{ number_format($row['monthly'][$m['key']], 2, ',', '.') }}</td>
                        @endforeach
                        <td class="text-end fw-bold">{{ number_format($row['total'], 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($months) + 2 }}" class="text-center text-muted fst-italic">
                            {{ __('planning/view.cost.budget.table.empty_reserve') }}
                        </td>
                    </tr>
                @endforelse

                <tr class="fw-bold bg-warning border-top border-dark">
                    <td class="text-end text-dark">{{ __('planning/view.cost.budget.table.monthly_total') }}</td>
                    @foreach($months as $m)
                        <td class="text-end text-dark">{{ number_format($monthlyTotals[$m['key']], 2, ',', '.') }}</td>
                    @endforeach
                    <td class="text-end text-dark fs-6">{{ number_format($budget->budget_sub_total, 2, ',', '.') }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="bg-warning p-3 border-top border-dark">
            <div class="row align-items-center justify-content-end">
                <div class="col-auto fw-bold text-dark">
                    {{ __('planning/view.cost.budget.footer.mgmt_reserve') }}
                </div>
                <div class="col-auto" style="width: 100px;">
                    <input type="number" name="budget_reserve_management" id="mgmtReserveInput"
                           class="form-control form-control-sm text-end fw-bold"
                           value="{{ $budget->budget_reserve_management }}"
                           step="0.01" min="0" max="100"
                           oninput="calcBudgetTotal({{ $budget->budget_sub_total }})">
                </div>

                <div class="col-auto fw-bold text-dark ms-3">
                    {{ __('planning/view.cost.budget.footer.partial_budget') }}
                </div>
                <div class="col-auto bg-white border px-2 py-1 text-end fw-bold" style="min-width: 120px;">
                    {{ number_format($budget->budget_sub_total, 2, ',', '.') }}
                </div>

                <div class="col-auto fw-bold text-dark ms-3">
                    {{ __('planning/view.cost.budget.footer.total_value') }}
                </div>
                <div class="col-auto bg-white border px-2 py-1 text-end fw-bold fs-6 text-primary" id="finalBudgetTotal" style="min-width: 140px;">
                    {{ number_format($budget->budget_total, 2, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer bg-light py-1">
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
            {{ __('planning/view.cost.budget.footer.close') }}
        </button>
        <button type="submit" class="btn btn-sm btn-primary">
            {{ __('planning/view.cost.budget.footer.save') }}
        </button>
    </div>
</form>
