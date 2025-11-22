<div class="modal fade" id="createWbsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('projects.wbs.store', $project) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" id="wbs_parent_id_input">

                <div class="modal-header">
                    <h5 class="modal-title">Novo Item EAP (Sub-nível)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Pacote de Trabalho</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="alert alert-info small">
                        Este item será criado como um "filho" do item selecionado.
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
