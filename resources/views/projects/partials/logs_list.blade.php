<div class="table-responsive">
    <table class="table table-sm table-hover align-middle mb-0" style="font-size: 0.85rem;">
        <thead class="bg-light text-secondary">
        <tr>
            <th style="width: 15%">{{ __('execution/view.log.date') }}</th>
            <th style="width: 25%">{{ __('execution/view.table.assigned_to') }}</th>
            <th style="width: 15%" class="text-center">{{ __('execution/view.log.hours') }}</th>
            <th>{{ __('execution/view.log.description') }}</th>
            <th style="width: 120px" class="text-center">{{ __('execution/view.table.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($logs as $log)
            <tr>
                <td>
                    {{ $log->task_log_date ? $log->task_log_date->format('d/m/Y') : '-' }}
                </td>

                <td>
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                             style="width: 24px; height: 24px; font-size: 10px;">
                            {{ substr($log->creator->user_username ?? 'U', 0, 2) }}
                        </div>
                        <span class="text-dark">
                            {{ $log->creator->user_username ?? 'Unknown' }}
                        </span>
                    </div>
                </td>

                <td class="text-center fw-bold text-dark">
                    {{ number_format($log->task_log_hours, 2, ',', '.') }} h
                </td>

                <td class="text-muted text-wrap" style="max-width: 300px; line-height: 1.2;">
                    {!! nl2br(e($log->task_log_description)) !!}
                </td>

                <td class="text-center align-middle">
                    @if(auth()->id() === $log->task_log_creator)

                        <div id="btn-delete-action-{{ $log->task_log_id }}">
                            <button class="btn btn-link text-danger p-0 border-0 opacity-50 hover-opacity-100"
                                    onclick="showDeleteConfirm({{ $log->task_log_id }})"
                                    title="{{ __('execution/view.actions.delete') }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>

                        <div id="confirm-card-{{ $log->task_log_id }}" class="d-none animate__animated animate__fadeIn">
                            <div class="d-flex align-items-center justify-content-center bg-white border rounded shadow-sm p-1" style="gap: 5px;">
                                <span class="small text-muted me-1" style="font-size: 0.7rem;">Excluir?</span>

                                <button class="btn btn-sm btn-danger p-0 d-flex align-items-center justify-content-center"
                                        id="btn-confirm-delete-{{ $log->task_log_id }}"
                                        onclick="deleteLog({{ $log->task_log_id }})"
                                        style="width: 24px; height: 24px;"
                                        title="Confirmar">
                                    <i class="bi bi-check-lg"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-secondary p-0 d-flex align-items-center justify-content-center"
                                        onclick="cancelDelete({{ $log->task_log_id }})"
                                        style="width: 24px; height: 24px;"
                                        title="Cancelar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>

                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    <div class="mb-2"><i class="bi bi-clock-history fs-4 opacity-25"></i></div>
                    {{ __('execution/view.table.empty_logs') }}
                </td>
            </tr>
        @endforelse
        </tbody>

        @if($logs->isNotEmpty())
            <tfoot class="bg-light fw-bold border-top">
            <tr>
                <td colspan="2" class="text-end text-uppercase small text-muted">Total:</td>
                <td class="text-center text-primary">{{ number_format($logs->sum('task_log_hours'), 2, ',', '.') }} h</td>
                <td colspan="2"></td>
            </tr>
            </tfoot>
        @endif
    </table>
</div>
