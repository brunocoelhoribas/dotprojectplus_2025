<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="fw-bold">Tem certeza que deseja excluir esta atividade?</p>
                <p class="text-muted small mb-0">Esta ação removerá o vínculo com a EAP e não poderá ser desfeita.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Sim, Excluir</button>
            </div>
        </div>
    </div>
</div>

<form id="deleteActivityForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
