<div class="modal fade" id="createActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="createActivityForm" method="POST">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Nova Atividade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome da Atividade *</label>
                        <input type="text" name="task_name" class="form-control" required>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Início</label>
                            <input type="date" name="task_start_date" class="form-control">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Fim</label>
                            <input type="date" name="task_end_date" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duração (dias)</label>
                        <input type="number" name="task_duration" class="form-control" value="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
