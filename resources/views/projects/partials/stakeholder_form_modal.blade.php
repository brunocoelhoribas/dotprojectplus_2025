<div class="modal fade" id="createStakeholderModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('stakeholders.store') }}" method="POST">
                @csrf
                {{-- O ID do Termo de Abertura (initiating) é essencial --}}
                <input type="hidden" name="initiating_id" value="{{ $initiating->initiating_id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Novo Stakeholder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="contact_id" class="form-label">Contato (Nome do Stakeholder) *</label>
                        <select name="contact_id" id="contact_id" class="form-select" required>
                            <option value="">Selecione um contato...</option>
                            @foreach($contacts as $contact)
                                <option value="{{ $contact->contact_id }}">{{ $contact->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="stakeholder_responsibility" class="form-label">Responsabilidades</label>
                        <textarea name="stakeholder_responsibility" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="stakeholder_power" class="form-label">Poder</label>
                            <select name="stakeholder_power" class="form-select">
                                <option value="">N/D</option>
                                <option value="Baixo">Baixo</option>
                                <option value="Médio">Médio</option>
                                <option value="Alto">Alto</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="stakeholder_interest" class="form-label">Interesse</label>
                            <select name="stakeholder_interest" class="form-select">
                                <option value="">N/D</option>
                                <option value="Baixo">Baixo</option>
                                <option value="Médio">Médio</option>
                                <option value="Alto">Alto</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="stakeholder_strategy" class="form-label">Estratégia</label>
                        <textarea name="stakeholder_strategy" class="form-control" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Submeter</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--
  MODAL 2: EDITAR STAKEHOLDER
--}}
<div class="modal fade" id="editStakeholderModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editStakeholderForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="initiating_id" value="{{ $initiating->initiating_id }}">
                <input type="hidden" name="contact_id" id="edit_contact_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Stakeholder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Stakeholder (Nome)</label>
                        <input type="text" id="edit_contact_name" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="edit_stakeholder_responsibility" class="form-label">Responsabilidades</label>
                        <textarea name="stakeholder_responsibility" id="edit_stakeholder_responsibility" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="edit_stakeholder_power" class="form-label">Poder</label>
                            <select name="stakeholder_power" id="edit_stakeholder_power" class="form-select">
                                <option value="">N/D</option>
                                <option value="Baixo">Baixo</option>
                                <option value="Médio">Médio</option>
                                <option value="Alto">Alto</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_stakeholder_interest" class="form-label">Interesse</label>
                            <select name="stakeholder_interest" id="edit_stakeholder_interest" class="form-select">
                                <option value="">N/D</option>
                                <option value="Baixo">Baixo</option>
                                <option value="Médio">Médio</option>
                                <option value="Alto">Alto</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="edit_stakeholder_strategy" class="form-label">Estratégia</label>
                        <textarea name="stakeholder_strategy" id="edit_stakeholder_strategy" class="form-control" rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger me-auto" id="deleteStakeholderBtn">Deletar Stakeholder</button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Submeter</button>
                </div>
            </form>

            <form id="deleteStakeholderForm" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
</div>
