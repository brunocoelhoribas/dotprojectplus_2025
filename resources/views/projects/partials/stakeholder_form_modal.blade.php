<div class="modal fade" id="createStakeholderModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('stakeholders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="initiating_id" value="{{ $initiating->initiating_id }}">

                <div class="modal-header bg-warning py-2">
                    <h5 class="modal-title text-dark fw-bold fs-6" id="createModalLabel">
                        {{ __('projects/partials.stakeholder.create_title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-white p-4">

                    <div class="mb-3 row border-bottom pb-3">
                        <label for="contact_id" class="col-sm-3 col-form-label fw-bold small text-dark text-end">
                            {{ __('projects/partials.stakeholder.contact_label') }} *
                        </label>
                        <div class="col-sm-9">
                            <select name="contact_id" id="contact_id" class="form-select" required>
                                <option value="">{{ __('projects/partials.stakeholder.select_contact') }}</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->contact_id }}">{{ $contact->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-3">
                        <label for="stakeholder_responsibility" class="col-sm-3 col-form-label fw-bold small text-dark text-end">
                            {{ __('projects/partials.stakeholder.responsibilities') }}
                        </label>
                        <div class="col-sm-9">
                            <textarea name="stakeholder_responsibility" id="stakeholder_responsibility" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <label for="stakeholder_power" class="col-sm-4 col-form-label fw-bold small text-dark text-end">
                                    {{ __('projects/partials.stakeholder.power') }}
                                </label>
                                <div class="col-sm-8">
                                    <select name="stakeholder_power" id="stakeholder_power" class="form-select">
                                        <option value="">{{ __('projects/partials.stakeholder.options.na') }}</option>
                                        <option value="Baixo">{{ __('projects/partials.stakeholder.options.low') }}</option>
                                        <option value="Médio">{{ __('projects/partials.stakeholder.options.medium') }}</option>
                                        <option value="Alto">{{ __('projects/partials.stakeholder.options.high') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <label for="stakeholder_interest" class="col-sm-4 col-form-label fw-bold small text-dark text-end">
                                    {{ __('projects/partials.stakeholder.interest') }}
                                </label>
                                <div class="col-sm-8">
                                    <select name="stakeholder_interest" id="stakeholder_interest" class="form-select">
                                        <option value="">{{ __('projects/partials.stakeholder.options.na') }}</option>
                                        <option value="Baixo">{{ __('projects/partials.stakeholder.options.low') }}</option>
                                        <option value="Médio">{{ __('projects/partials.stakeholder.options.medium') }}</option>
                                        <option value="Alto">{{ __('projects/partials.stakeholder.options.high') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="stakeholder_strategy" class="col-sm-3 col-form-label fw-bold small text-dark text-end">
                            {{ __('projects/partials.stakeholder.strategy') }}
                        </label>
                        <div class="col-sm-9">
                            <textarea name="stakeholder_strategy" id="stakeholder_strategy" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-light border shadow-sm" data-bs-dismiss="modal">
                        {{ __('projects/partials.stakeholder.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        {{ __('projects/partials.stakeholder.submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editStakeholderModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="editStakeholderForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="initiating_id" id="edit_initiating_id">
                <input type="hidden" name="contact_id" id="edit_contact_id">

                <div class="modal-header bg-warning py-2">
                    <h5 class="modal-title text-dark fw-bold fs-6" id="editModalLabel">
                        {{ __('projects/partials.stakeholder.edit_title') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body bg-white p-4">

                    <div class="mb-3 row border-bottom pb-3">
                        <label class="col-sm-3 col-form-label fw-bold small text-dark text-end">
                            {{ __('projects/partials.stakeholder.name_readonly_label') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" id="edit_contact_name" class="form-control bg-light" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row border-bottom pb-3">
                        <label for="edit_stakeholder_responsibility" class="col-sm-3 col-form-label fw-bold small text-dark text-end">
                            {{ __('projects/partials.stakeholder.responsibilities') }}
                        </label>
                        <div class="col-sm-9">
                            <textarea name="stakeholder_responsibility" id="edit_stakeholder_responsibility" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <label for="edit_stakeholder_power" class="col-sm-4 col-form-label fw-bold small text-dark text-end">
                                    {{ __('projects/partials.stakeholder.power') }}
                                </label>
                                <div class="col-sm-8">
                                    <select name="stakeholder_power" id="edit_stakeholder_power" class="form-select">
                                        <option value="">{{ __('projects/partials.stakeholder.options.na') }}</option>
                                        <option value="Baixo">{{ __('projects/partials.stakeholder.options.low') }}</option>
                                        <option value="Médio">{{ __('projects/partials.stakeholder.options.medium') }}</option>
                                        <option value="Alto">{{ __('projects/partials.stakeholder.options.high') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row align-items-center">
                                <label for="edit_stakeholder_interest" class="col-sm-4 col-form-label fw-bold small text-dark text-end">
                                    {{ __('projects/partials.stakeholder.interest') }}
                                </label>
                                <div class="col-sm-8">
                                    <select name="stakeholder_interest" id="edit_stakeholder_interest" class="form-select">
                                        <option value="">{{ __('projects/partials.stakeholder.options.na') }}</option>
                                        <option value="Baixo">{{ __('projects/partials.stakeholder.options.low') }}</option>
                                        <option value="Médio">{{ __('projects/partials.stakeholder.options.medium') }}</option>
                                        <option value="Alto">{{ __('projects/partials.stakeholder.options.high') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label for="edit_stakeholder_strategy" class="col-sm-3 col-form-label fw-bold small text-dark text-end">
                            {{ __('projects/partials.stakeholder.strategy') }}
                        </label>
                        <div class="col-sm-9">
                            <textarea name="stakeholder_strategy" id="edit_stakeholder_strategy" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-outline-danger me-auto border-0" id="deleteStakeholderBtn">
                        <i class="bi bi-trash me-1"></i> {{ __('projects/partials.stakeholder.delete_btn') }}
                    </button>

                    <button type="button" class="btn btn-sm btn-light border shadow-sm" data-bs-dismiss="modal">
                        {{ __('projects/partials.stakeholder.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary px-4">
                        {{ __('projects/partials.stakeholder.submit') }}
                    </button>
                </div>
            </form>

            <form id="deleteStakeholderForm" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="deleteStakeholderConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2">
                <h6 class="modal-title fw-bold">{{__('planning/view.stakeholder.modal.confirmation')}}</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-circle display-4 text-danger"></i>
                </div>
                <p class="mb-0 fw-bold text-dark">
                    {{ __('projects/views.show.confirm_delete') ?? 'Tem certeza que deseja excluir este stakeholder?' }}
                </p>
                <small class="text-muted">{{__('planning/view.stakeholder.modal.action')}}</small>
            </div>
            <div class="modal-footer justify-content-center bg-light p-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"> {{__('planning/view.stakeholder.modal.confirm_no')}}</button>
                <button type="button" class="btn btn-sm btn-danger" id="confirmDeleteStakeholderBtn">{{__('planning/view.stakeholder.modal.confirm_yes')}}</button>
            </div>
        </div>
    </div>
</div>
