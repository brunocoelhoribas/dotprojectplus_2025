@php
    use App\Models\Project\ProjectClosure;
    use App\Models\User\UserContact;

    $closure = ProjectClosure::where('project_name', $project->project_name)->first();

    if (!$closure) {
        $closure = new ProjectClosure();
        $closure->project_name = $project->project_name;
        $closure->planned_budget = $project->project_target_budget;
        $closure->project_planned_start_date = $project->project_start_date;
        $closure->project_planned_end_date = $project->project_end_date;
        $closure->project_start_date = $project->project_actual_start_date ?? null;
        $closure->project_end_date = $project->project_actual_end_date ?? null;
        $closure->budget = 0;
    }

    $availableContacts = UserContact::orderBy('contact_first_name')->get();

    $currentParticipants = $closure->participants ? explode(', ', $closure->participants) : [];
@endphp

<form id="formClosure" onsubmit="saveClosure(event)">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-secondary text-white rounded-top">
            <h6 class="mb-0 fw-bold text-center small text-uppercase">{{ __('closure/view.sections.post_mortem') }}</h6>
        </div>

        <div class="card-body">
            <div class="bg-light p-2 mb-3 border-bottom border-2">
                <strong class="text-secondary small">{{ __('closure/view.sections.meeting_data') }}</strong>
            </div>

            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <label class="form-label small fw-bold text-muted">{{ __('closure/view.labels.participants') }}</label>

                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <span class="small text-muted d-block mb-1">{{ __('closure/view.labels.available') }}</span>
                            <select id="list_available" class="form-select form-select-sm" size="6" multiple>
                                @foreach($availableContacts as $contact)
                                    @php
                                        $fullName = $contact->contact_first_name . ' ' . $contact->contact_last_name;
                                        if (in_array($fullName, $currentParticipants, true)) continue;
                                    @endphp
                                    <option value="{{ $fullName }}">{{ $fullName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 text-center">
                            <button type="button" class="btn btn-sm btn-outline-secondary mb-2 d-block w-100"
                                    onclick="moveItems('list_available', 'list_selected')" title="Adicionar">
                                <i class="bi bi-chevron-double-right"></i> >>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary d-block w-100"
                                    onclick="moveItems('list_selected', 'list_available')" title="Remover">
                                <i class="bi bi-chevron-double-left"></i> <<
                            </button>
                        </div>

                        <div class="col-md-5">
                            <span class="small text-muted d-block mb-1 fw-bold">{{ __('closure/view.labels.selected') }}</span>
                            <select id="list_selected" class="form-select form-select-sm shadow-sm bg-light" size="6"
                                    multiple>
                                @foreach($currentParticipants as $participant)
                                    @if(!empty(trim($participant)))
                                        <option value="{{ $participant }}">{{ $participant }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="participants" id="participants_final_input"
                           value="{{ $closure->participants }}">
                </div>

                <div class="col-md-4 offset-md-8 text-end">
                    <label class="form-label small fw-bold text-muted me-2">{{ __('closure/view.labels.date') }}</label>
                    <input type="date" name="project_meeting_date" class="form-control d-inline-block"
                           style="width: auto;"
                           value="{{ optional($closure->project_meeting_date)->format('Y-m-d') }}">
                </div>
            </div>

            <div class="bg-light p-2 mb-3 border-bottom border-2">
                <strong class="text-secondary small">{{ __('closure/view.sections.project_data') }}</strong>
            </div>

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="row g-3">
                        <div class="col-md-3 text-end"><label
                                class="col-form-label small fw-bold">{{ __('closure/view.labels.planned_start') }}
                                :</label></div>
                        <div class="col-md-3">
                            <input type="date" name="project_planned_start_date" class="form-control form-control-sm"
                                   value="{{ optional($closure->project_planned_start_date)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 text-end"><label
                                class="col-form-label small fw-bold text-success">{{ __('closure/view.labels.planned_budget') }}
                                (R$):</label></div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" name="planned_budget" class="form-control form-control-sm"
                                   value="{{ $closure->planned_budget }}">
                        </div>

                        <div class="col-md-3 text-end"><label
                                class="col-form-label small fw-bold">{{ __('closure/view.labels.actual_start') }}
                                :</label></div>
                        <div class="col-md-3">
                            <input type="date" name="project_start_date" class="form-control form-control-sm"
                                   value="{{ optional($closure->project_start_date)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 text-end"><label
                                class="col-form-label small fw-bold text-primary">{{ __('closure/view.labels.actual_budget') }}
                                (R$):</label></div>
                        <div class="col-md-3">
                            <input type="number" step="0.01" name="budget" class="form-control form-control-sm"
                                   value="{{ $closure->budget }}">
                        </div>

                        <div class="col-md-3 text-end"><label
                                class="col-form-label small fw-bold">{{ __('closure/view.labels.planned_end') }}
                                :</label></div>
                        <div class="col-md-3">
                            <input type="date" name="project_planned_end_date" class="form-control form-control-sm"
                                   value="{{ optional($closure->project_planned_end_date)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6"></div>

                        <div class="col-md-3 text-end"><label
                                class="col-form-label small fw-bold">{{ __('closure/view.labels.actual_end') }}:</label>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="project_end_date" class="form-control form-control-sm"
                                   value="{{ optional($closure->project_end_date)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>

            <div class="bg-light p-2 mb-3 border-bottom border-2">
                <strong class="text-secondary small">{{ __('closure/view.sections.lessons_learned') }}</strong>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">{{ __('closure/view.labels.strengths') }}</label>
                <textarea name="project_strength" class="form-control"
                          rows="3">{{ $closure->project_strength }}</textarea>
                <div class="form-text small fst-italic">{{ __('closure/view.hints.strengths') }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">{{ __('closure/view.labels.weaknesses') }}</label>
                <textarea name="project_weaknesses" class="form-control"
                          rows="3">{{ $closure->project_weaknesses }}</textarea>
                <div class="form-text small fst-italic">{{ __('closure/view.hints.weaknesses') }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">{{ __('closure/view.labels.suggestions') }}</label>
                <textarea name="improvement_suggestions" class="form-control"
                          rows="3">{{ $closure->improvement_suggestions }}</textarea>
                <div class="form-text small fst-italic">{{ __('closure/view.hints.suggestions') }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">{{ __('closure/view.labels.conclusions') }}</label>
                <textarea name="conclusions" class="form-control" rows="3">{{ $closure->conclusions }}</textarea>
                <div class="form-text small fst-italic">{{ __('closure/view.hints.conclusions') }}</div>
            </div>

        </div>

        <div class="card-footer bg-light text-end">
            <button type="submit" id="btnSaveClosure" class="btn btn-secondary px-4 text-white">
                {{ __('Save') }}
            </button>
        </div>
    </div>
</form>

<script>
    function moveItems(sourceId, targetId) {
        const sourceSelect = document.getElementById(sourceId);
        const targetSelect = document.getElementById(targetId);
        const selectedOptions = Array.from(sourceSelect.selectedOptions);

        if (selectedOptions.length === 0) return;

        selectedOptions.forEach(option => {
            const newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.text = option.text;
            targetSelect.add(newOption);

            option.remove();
        });
    }

    function saveClosure(event) {
        event.preventDefault();

        const selectedList = document.getElementById('list_selected');
        let participantsArray = [];
        for (let i = 0; i < selectedList.options.length; i++) {
            participantsArray.push(selectedList.options[i].value);
        }
        document.getElementById('participants_final_input').value = participantsArray.join(', ');

        const form = document.getElementById('formClosure');
        const formData = new FormData(form);
        const btnSave = document.getElementById('btnSaveClosure');
        const originalBtnText = btnSave.innerHTML;

        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Salvando...';

        const url = `{{ route('projects.closure.store', $project->project_id) }}`;

        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log(data.message);
                } else {
                    console.log('Erro ao salvar: ' + JSON.stringify(data.errors || 'Erro desconhecido'));
                }
            })
            .catch(err => {
                console.error(err);
            })
            .finally(() => {
                btnSave.disabled = false;
                btnSave.innerHTML = originalBtnText;
            });
    }
</script>
