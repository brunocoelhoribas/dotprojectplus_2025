<div class="container-fluid p-0">

    <div class="mb-3">
        <div class="bg-warning text-dark px-2 py-1 fw-bold small">
            {{ __('planning/view.quality.policies_title') }}
        </div>
        <form action="{{ route('projects.quality.update_plan', $project->project_id) }}" method="POST" onsubmit="submitQualityForm(event)">
            @csrf
            <textarea name="quality_policies" class="form-control rounded-0 border-top-0" rows="3">{{ $plan->quality_policies }}</textarea>
            <div class="text-end mt-1">
                <button class="btn btn-xs btn-primary">{{ __('planning/view.quality.save_text') }}</button>
            </div>
        </form>
    </div>

    <div class="mb-3">
        <div class="bg-warning text-dark px-2 py-1 fw-bold small">
            {{ __('planning/view.quality.assurance_title') }}
            <span class="fw-normal fst-italic ms-2" style="font-size: 0.7em;">
                {{ __('planning/view.quality.assurance_help') }}
            </span>
        </div>

        <form action="{{ route('projects.quality.update_plan', $project->project_id) }}" method="POST" onsubmit="submitQualityForm(event)">
            @csrf
            <textarea name="quality_assurance" class="form-control rounded-0 mb-2" rows="2">{{ $plan->quality_assurance }}</textarea>
            <div class="text-end mb-2">
                <button class="btn btn-xs btn-primary">{{ __('planning/view.quality.save_text') }}</button>
            </div>
        </form>

        <button class="btn btn-xs btn-light border shadow-sm mb-1" onclick="openAssuranceModal()">
            {{ __('planning/view.quality.add_audit_item') }}
        </button>

        <table class="table table-bordered table-sm small align-middle mb-0">
            <thead class="bg-warning bg-opacity-25">
            <tr>
                <th>{{ __('planning/view.quality.table.what') }}</th>
                <th>{{ __('planning/view.quality.table.who') }}</th>
                <th>{{ __('planning/view.quality.table.when') }}</th>
                <th>{{ __('planning/view.quality.table.how') }}</th>
                <th style="width: 30px;"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($plan->assuranceItems as $item)
                <tr>
                    <td>{{ $item->what }}</td>
                    <td>{{ $item->who }}</td>
                    <td>{{ $item->when }}</td>
                    <td>{{ $item->how }}</td>
                    <td>
                        <form action="{{ route('projects.quality.destroy_assurance', ['project' => $project->project_id, 'item' => $item->id]) }}"
                              method="POST"
                              onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete') }}')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-3">
        <div class="bg-warning text-dark px-2 py-1 fw-bold small">
            {{ __('planning/view.quality.control_title') }}
        </div>

        <form action="{{ route('projects.quality.update_plan', $project->project_id) }}" method="POST" onsubmit="submitQualityForm(event)">
            @csrf
            <textarea name="quality_controlling" class="form-control rounded-0 mb-2" rows="2">{{ $plan->quality_controlling }}</textarea>
            <div class="text-end mb-2">
                <button class="btn btn-xs btn-primary">{{ __('planning/view.quality.save_text') }}</button>
            </div>
        </form>

        <div class="bg-warning bg-opacity-50 text-dark px-2 py-1 fw-bold small text-center">
            {{ __('planning/view.quality.requirements_title') }}
        </div>
        <ul class="list-group list-group-flush mb-2">
            @foreach($plan->requirements as $req)
                <li class="list-group-item py-1 small d-flex justify-content-between">
                    {{ $loop->iteration }}. {{ $req->requirement }}
                    <form action="{{ route('projects.quality.destroy_requirement', ['project' => $project->project_id, 'requirement' => $req->id]) }}"
                          method="POST" class="d-inline"
                          onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete') }}')">
                        @csrf @method('DELETE')
                        <button class="btn btn-xs text-danger p-0 border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                    </form>
                </li>
            @endforeach
        </ul>
        <button class="btn btn-xs btn-light border shadow-sm" onclick="openRequirementModal()">
            {{ __('planning/view.quality.add_requirement') }}
        </button>
    </div>

    @foreach($plan->goals as $goal)
        <div class="border mb-4">
            <div class="row g-0">
                <div class="col-md-3 border-end p-2 bg-light position-relative">
                    <div class="position-absolute top-0 end-0 p-1">
                        <form action="{{ route('projects.quality.destroy_goal', ['project' => $project->project_id, 'goal' => $goal->id]) }}"
                              method="POST"
                              onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete_goal') }}')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>

                    <div class="fw-bold small text-center mb-2">{{ __('planning/view.quality.gqm.control_goal') }}</div>
                    <div class="mb-1">
                        <label class="small text-muted d-block" style="font-size: 0.7em;">{{ __('planning/view.quality.gqm.analyze') }}</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $goal->gqm_goal_object }}" readonly>
                    </div>
                    <div class="mb-1">
                        <label class="small text-muted d-block" style="font-size: 0.7em;">{{ __('planning/view.quality.gqm.purpose') }}</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $goal->gqm_goal_propose }}" readonly>
                    </div>
                </div>

                <div class="col-md-9 p-2">
                    <div class="fw-bold small text-center mb-2 bg-warning bg-opacity-25">{{ __('planning/view.quality.gqm.analysis_questions') }}</div>
                    <button class="btn btn-xs btn-light border mb-2" onclick="addQuestion({{ $goal->id }})">
                        {{ __('planning/view.quality.gqm.add_question') }}
                    </button>

                    @foreach($goal->questions as $question)
                        <div class="border mb-2 p-2 position-relative">
                            <div class="position-absolute top-0 end-0 p-1">
                                <form action="{{ route('projects.quality.destroy_question', ['project' => $project->project_id, 'question' => $question->id]) }}"
                                      method="POST"
                                      onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete_question') }}')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-xs text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="small fw-bold">{{ __('planning/view.quality.gqm.question') }}</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $question->question }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold">{{ __('planning/view.quality.gqm.target') }}</label>
                                    <input type="text" class="form-control form-control-sm" value="{{ $question->target }}" readonly>
                                </div>
                            </div>

                            <div class="bg-light p-2 border-top">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="small fw-bold">{{ __('planning/view.quality.gqm.metric') }}</span>
                                    <button class="btn btn-xs btn-secondary" onclick="addMetric({{ $question->id }})">
                                        {{ __('planning/view.quality.gqm.add_metric') }}
                                    </button>
                                </div>
                                <table class="table table-bordered table-sm small mb-0 bg-white">
                                    <thead>
                                    <tr>
                                        <th>{{ __('planning/view.quality.gqm.metric') }}</th>
                                        <th>{{ __('planning/view.quality.gqm.how_to_collect') }}</th>
                                        <th style="width: 20px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($question->metrics as $metric)
                                        <tr>
                                            <td>{{ $metric->metric }}</td>
                                            <td>{{ $metric->how_to_collect }}</td>
                                            <td>
                                                <form action="{{ route('projects.quality.destroy_metric', ['project' => $project->project_id, 'metric' => $metric->id]) }}"
                                                      method="POST"
                                                      onsubmit="askConfirmation(event, '{{ __('planning/view.quality.confirm.delete_metric') }}')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-xs text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <button class="btn btn-xs btn-light border shadow-sm" onclick="openGoalModal()">
        {{ __('planning/view.quality.gqm.add_goal') }}
    </button>

</div>

<div class="modal fade" id="goalModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('projects.quality.store_goal', $project->project_id) }}" method="POST" onsubmit="submitQualityForm(event)">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">{{ __('planning/view.quality.modals.new_goal') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-2 row">
                        <label class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.quality.gqm.analyze_placeholder') }}</label>
                        <div class="col-sm-8"><input type="text" name="gqm_goal_object" class="form-control form-control-sm" required></div>
                    </div>
                    <div class="mb-2 row">
                        <label class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.quality.gqm.purpose_placeholder') }}</label>
                        <div class="col-sm-8"><input type="text" name="gqm_goal_propose" class="form-control form-control-sm" required></div>
                    </div>
                    <div class="mb-2 row">
                        <label class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.quality.gqm.respect_to_placeholder') }}</label>
                        <div class="col-sm-8"><input type="text" name="gqm_goal_respect_to" class="form-control form-control-sm" required></div>
                    </div>
                    <div class="mb-2 row">
                        <label class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.quality.gqm.viewpoint_placeholder') }}</label>
                        <div class="col-sm-8"><input type="text" name="gqm_goal_point_of_view" class="form-control form-control-sm" required></div>
                    </div>
                    <div class="mb-2 row">
                        <label class="col-sm-4 col-form-label text-end small fw-bold">{{ __('planning/view.quality.gqm.context_placeholder') }}</label>
                        <div class="col-sm-8"><input type="text" name="gqm_goal_context" class="form-control form-control-sm" required></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('planning/view.quality.modals.cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('planning/view.quality.modals.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="questionModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="questionForm" method="POST" onsubmit="submitQualityForm(event)">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">{{ __('planning/view.quality.modals.new_question') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('planning/view.quality.gqm.question') }}</label>
                        <textarea name="question" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('planning/view.quality.gqm.target') }}</label>
                        <input type="text" name="target" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('planning/view.quality.modals.cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('planning/view.quality.modals.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="metricModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="metricForm" method="POST" onsubmit="submitQualityForm(event)">
                @csrf
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">{{ __('planning/view.quality.modals.new_metric') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('planning/view.quality.gqm.metric') }}</label>
                        <textarea name="metric" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('planning/view.quality.gqm.how_to_collect') }}</label>
                        <textarea name="how_to_collect" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('planning/view.quality.modals.cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary">{{ __('planning/view.quality.modals.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="requirementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('projects.quality.store_requirement', $project->project_id) }}" method="POST" onsubmit="submitQualityForm(event)">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('planning/view.quality.modals.new_requirement') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body"><textarea name="requirement" class="form-control" required></textarea></div>
                <div class="modal-footer"><button type="submit" class="btn btn-sm btn-primary">{{ __('planning/view.quality.modals.save') }}</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="assuranceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('projects.quality.store_assurance', $project->project_id) }}" method="POST" onsubmit="submitQualityForm(event)">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('planning/view.quality.modals.new_assurance') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="small fw-bold">{{ __('planning/view.quality.table.what') }}:</label>
                    <input type="text" name="what" class="form-control form-control-sm mb-2" required>

                    <label class="small fw-bold">{{ __('planning/view.quality.table.who') }}:</label>
                    <select name="who" class="form-select form-select-sm mb-2">
                        @foreach($users as $id => $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <label class="small fw-bold">{{ __('planning/view.quality.table.when') }}:</label>
                    <input type="text" name="when" class="form-control form-control-sm mb-2">

                    <label class="small fw-bold">{{ __('planning/view.quality.table.how') }}:</label>
                    <input type="text" name="how" class="form-control form-control-sm">
                </div>
                <div class="modal-footer"><button type="submit" class="btn btn-sm btn-primary">{{ __('planning/view.quality.modals.save') }}</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold" id="confirmationModalLabel">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ __('planning/view.quality.modals.confirmation_title') }}
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p id="confirmationMessage" class="mb-0 fw-bold text-dark"></p>
            </div>
            <div class="modal-footer bg-light justify-content-center p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                    {{ __('planning/view.quality.modals.confirm_no') }}
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="executePendingAction()">
                    {{ __('planning/view.quality.modals.confirm_yes') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let pendingForm = null;

    function askConfirmation(event, message) {
        event.preventDefault();
        pendingForm = event.target;

        document.getElementById('confirmationMessage').innerText = message;
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
    }

    function executePendingAction() {
        if (pendingForm) {
            const modalEl = document.getElementById('confirmationModal');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) modal.hide();

            processAjaxForm(pendingForm);
        }
    }

    function submitQualityForm(event) {
        event.preventDefault();
        processAjaxForm(event.target);
    }

    function processAjaxForm(form) {
        const url = form.action;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

        let originalText = '';
        if(submitBtn) {
            originalText = submitBtn.innerText;
            submitBtn.innerText = '...';
            submitBtn.disabled = true;
        }

        fetch(url, {
            method: form.method || 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(response => {
                if (!response.ok) throw new Error('Erro na requisição');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    closeAllModais();
                    reloadQualityTab();
                } else {
                    alert('Erro: ' + (data.message || 'Desconhecido'));
                }
            })
            .catch(error => {
                console.error(error);
                alert('Erro ao processar solicitação.');
            })
            .finally(() => {
                if(submitBtn) {
                    submitBtn.innerText = originalText;
                    submitBtn.disabled = false;
                }
                pendingForm = null;
            });
    }

    function closeAllModais() {
        document.querySelectorAll('.modal.show').forEach(el => {
            const instance = bootstrap.Modal.getInstance(el);
            if(instance) instance.hide();
        });
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    }

    function reloadQualityTab() {
        const tabBtn = document.querySelector('a[onclick*="quality"], button[onclick*="quality"]');
        if (tabBtn) {
            tabBtn.click();
            return;
        }
        if (typeof loadTab === 'function') {
            const fakeEvent = { preventDefault: function() {}, target: document.body };
            loadTab(fakeEvent, 'quality');
        }
    }

    function openGoalModal() {
        new bootstrap.Modal(document.getElementById('goalModal')).show();
    }

    function addQuestion(goalId) {
        document.getElementById('questionForm').reset();
        const form = document.getElementById('questionForm');
        let url = "{{ route('projects.quality.store_question', ['project' => $project->project_id, 'goal' => ':GOAL_ID']) }}";
        form.action = url.replace(':GOAL_ID', goalId);
        new bootstrap.Modal(document.getElementById('questionModal')).show();
    }

    function addMetric(questionId) {
        document.getElementById('metricForm').reset();
        const form = document.getElementById('metricForm');
        let url = "{{ route('projects.quality.store_metric', ['project' => $project->project_id, 'question' => ':QUESTION_ID']) }}";
        form.action = url.replace(':QUESTION_ID', questionId);
        new bootstrap.Modal(document.getElementById('metricModal')).show();
    }

    function openRequirementModal() { new bootstrap.Modal(document.getElementById('requirementModal')).show(); }
    function openAssuranceModal() { new bootstrap.Modal(document.getElementById('assuranceModal')).show(); }
</script>
