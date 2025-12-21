<div class="modal fade" id="minutesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-journal-text me-2"></i>{{ __('planning/partials.minutes.create_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetMinuteForm()"></button>
            </div>

            <div class="modal-body">
                <div class="card border-light shadow-sm mb-4">
                    <div class="card-body bg-light-subtle">
                        <form id="minuteForm" action="{{ route('projects.minutes.store', $project) }}" method="POST">
                            @csrf
                            <div id="methodInputContainer"></div>

                            <div class="mb-3 p-2 bg-white border rounded">
                                <label class="fw-bold me-3 small text-uppercase text-muted">{{ __('planning/partials.minutes.type_label') }}:</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_effort" id="chk_effort" value="1">
                                    <label class="form-check-label" for="chk_effort">{{ __('planning/partials.minutes.types.effort') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_duration" id="chk_duration" value="1">
                                    <label class="form-check-label" for="chk_duration">{{ __('planning/partials.minutes.types.duration') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_resource" id="chk_resource" value="1">
                                    <label class="form-check-label" for="chk_resource">{{ __('planning/partials.minutes.types.resource') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_size" id="chk_size" value="1">
                                    <label class="form-check-label" for="chk_size">{{ __('planning/partials.minutes.types.size') }}</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold">{{ __('planning/partials.minutes.date') }}</label>
                                    <input type="date" name="date" id="input_date" class="form-control" required value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="col-md-4 mb-3 w-100">
                                    <label class="form-label fw-bold">{{ __('planning/partials.minutes.members') }}</label>
                                    <select name="member_ids[]" id="input_members" class="form-select" multiple size="4">
                                        @foreach($users as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="form-text small" style="font-size: 0.75rem;">
                                        {{ __('planning/partials.minutes.members_hint') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('planning/partials.minutes.report_label') }}</label>
                                <textarea name="description" id="input_description" class="form-control" rows="4" required></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" id="btnCancelEdit" class="btn btn-secondary d-none" onclick="resetMinuteForm()">
                                    {{ __('planning/partials.minutes.btn_cancel_edit') }}
                                </button>
                                <button type="submit" id="btnSubmit" class="btn btn-primary">
                                    {{ __('planning/partials.minutes.btn_save') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="my-4">

                <div class="table-responsive border rounded" style="max-height: 300px; overflow-y: auto;">
                    <table class="table table-hover table-sm table-striped mb-0 small align-middle">
                        <thead class="table-light sticky-top">
                        <tr>
                            <th>{{ __('planning/partials.minutes.table.date') }}</th>
                            <th>{{ __('planning/partials.minutes.table.type') }}</th>
                            <th>{{ __('planning/partials.minutes.table.report') }}</th>
                            <th>{{ __('planning/partials.minutes.table.participants') }}</th>
                            <th class="text-center" style="width: 80px;">{{ __('planning/partials.minutes.table.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($minutes as $minute)
                            <tr>
                                <td>{{ $minute->minute_date ? $minute->minute_date->format('d/m/Y') : '-' }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        @if($minute->isEffort) <span class="badge bg-info text-dark">{{ __('planning/partials.minutes.types.effort') }}</span> @endif
                                        @if($minute->isDuration) <span class="badge bg-warning text-dark">{{ __('planning/partials.minutes.types.duration') }}</span> @endif
                                        @if($minute->isResource) <span class="badge bg-success">{{ __('planning/partials.minutes.types.resource') }}</span> @endif
                                        @if($minute->isSize) <span class="badge bg-primary">{{ __('planning/partials.minutes.types.size') }}</span> @endif
                                    </div>
                                </td>
                                <td>{{ Str::limit($minute->description, 50) }}</td>
                                <td>
                                    @foreach($minute->members as $member)
                                        <span class="badge bg-light text-dark border">{{ $member->user_username }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button"
                                                class="btn btn-xs btn-outline-primary border-0 btn-edit-minute"
                                                data-minute="{{ json_encode($minute, JSON_THROW_ON_ERROR) }}"
                                                data-members="{{ json_encode($minute->members->pluck('user_id'), JSON_THROW_ON_ERROR) }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form
                                            action="{{ route('projects.minutes.destroy', [$project->project_id, $minute->id]) }}"
                                            method="POST" onsubmit="return confirm('{{ __('planning/partials.minutes.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger border-0">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">{{ __('planning/partials.minutes.table.empty') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.btn-edit-minute');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const minute = JSON.parse(this.dataset.minute);
                    const memberIds = JSON.parse(this.dataset.members);
                    fillMinuteForm(minute, memberIds);
                });
            });
        });

        function fillMinuteForm(minute, memberIds) {
            // Tradução dinâmica via Blade
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>{{ __('planning/partials.minutes.edit_title') }}';
            document.getElementById('btnSubmit').textContent = '{{ __('planning/partials.minutes.btn_update') }}';
            document.getElementById('btnCancelEdit').classList.remove('d-none');

            let updateUrl = "{{ route('projects.minutes.update', ['project' => $project->project_id, 'minute' => 'MIN_ID']) }}";
            updateUrl = updateUrl.replace('MIN_ID', minute.id);

            const form = document.getElementById('minuteForm');
            form.action = updateUrl;

            const methodContainer = document.getElementById('methodInputContainer');
            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';

            if (minute.minute_date) {
                document.getElementById('input_date').value = minute.minute_date.split('T')[0];
            }
            document.getElementById('input_description').value = minute.description;

            document.getElementById('chk_effort').checked = !!minute.isEffort;
            document.getElementById('chk_duration').checked = !!minute.isDuration;
            document.getElementById('chk_resource').checked = !!minute.isResource;
            document.getElementById('chk_size').checked = !!minute.isSize;

            const select = document.getElementById('input_members');
            Array.from(select.options).forEach(option => option.selected = false);

            const idsToCheck = memberIds.map(String);
            Array.from(select.options).forEach(option => {
                if (idsToCheck.includes(String(option.value))) {
                    option.selected = true;
                }
            });

            document.querySelector('#minutesModal .modal-body').scrollTop = 0;
        }

        window.resetMinuteForm = function() {
            document.getElementById('modalTitle').innerHTML = '<i class="bi bi-journal-text me-2"></i>{{ __('planning/partials.minutes.create_title') }}';
            document.getElementById('btnSubmit').textContent = '{{ __('planning/partials.minutes.btn_save') }}';
            document.getElementById('btnCancelEdit').classList.add('d-none');

            const form = document.getElementById('minuteForm');
            form.reset();
            form.action = "{{ route('projects.minutes.store', $project) }}";
            document.getElementById('methodInputContainer').innerHTML = '';
        }
    </script>
@endpush
