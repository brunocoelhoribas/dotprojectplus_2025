<div class="card border-0">
    <div class="card-body">
        <h5 class="card-title">Encerramento do Projeto</h5>
        <p class="card-text">Use esta seção para registrar as lições aprendidas e formalizar o encerramento do projeto.</p>

        <hr>

        <form action="#" method="POST">
            @csrf
            <div class="mb-3">
                <label for="closure_comments" class="form-label">Lições Aprendidas / Comentários Finais</label>
                <textarea class="form-control" id="closure_comments" rows="5"></textarea>
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="close_project_check">
                <label class="form-check-label" for="close_project_check">Confirmar encerramento e arquivar projeto</label>
            </div>

            <button type="submit" class="btn btn-danger">Encerrar Projeto</button>
        </form>
    </div>
</div>
