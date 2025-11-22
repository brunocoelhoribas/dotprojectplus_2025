<div class="modal fade" id="editActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editActivityForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Editar Atividade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome da Atividade *</label>
                        <input type="text" name="task_name" id="edit_task_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Início</label>
                            <input type="date" name="task_start_date" id="edit_task_start_date" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fim</label>
                            <input type="date" name="task_end_date" id="edit_task_end_date" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Duração (dias)</label>
                            <input type="number" name="task_duration" id="edit_task_duration" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">% Concluído</label>
                            <select name="task_percent_complete" id="edit_task_percent_complete" class="form-select">
                                <option value="0">0% (Não Iniciada)</option>
                                <option value="50">50% (Em Andamento)</option>
                                <option value="100">100% (Concluída)</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteActivityForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
