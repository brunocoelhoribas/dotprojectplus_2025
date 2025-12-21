@include('projects.planning.create_wbs_modal')
@include('projects.planning.create_activity_modal')
@include('projects.planning.edit_activity_modal')
@include('projects.planning.delete_wbs_modal')
@include('projects.planning.delete_confirmation_modal')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="h5 mb-0">Planejamento e Monitoramento</h4>
        <span id="tab-subtitle" class="text-muted fs-6 fw-normal"></span>
    </div>

    <div id="planning-actions"></div>
</div>

<div class="card border-0 shadow-none">
    <div class="card-header bg-light border-bottom-0 p-0">
        <ul class="nav nav-tabs card-header-tabs mx-0" id="planning-tabs">
            <li class="nav-item">
                <a class="nav-link active fw-bold" href="#" onclick="loadTab(event, 'activities')">Atividades</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'schedule')">Cronograma</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'costs')">Custos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'risks')">Riscos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'quality')">Qualidade</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'communication')">Comunicação</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'procurement')">Aquisições</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'stakeholders')">Stakeholders</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" onclick="loadTab(event, 'plan')">Plano do projeto</a>
            </li>
        </ul>
    </div>

    <div class="card-body p-3 border border-top-0 bg-white position-relative" id="planning-content">
        <div id="loader" class="text-center py-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
        </div>
    </div>
</div>

@include('projects.planning.partials.training_modal')
@include('projects.planning.partials.minutes_modal')

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadTab(null, 'activities');
        });

        function loadTab(event, tabName) {
            if(event) event.preventDefault();

            if(event) {
                document.querySelectorAll('#planning-tabs .nav-link').forEach(link => {
                    link.classList.remove('active', 'fw-bold');
                });
                event.target.classList.add('active', 'fw-bold');
            }

            const container = document.getElementById('planning-content');
            const actionsContainer = document.getElementById('planning-actions');

            container.style.opacity = '0.5';

            const url = `{{ route('projects.planning.tab', ['project' => $project->project_id, 'tab' => ':tab']) }}`.replace(':tab', tabName);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.style.opacity = '1';

                    if(actionsContainer) actionsContainer.innerHTML = data.actions;

                    executeScripts(container);
                })
                .catch(error => {
                    console.error('Erro:', error);
                    container.innerHTML = '<div class="alert alert-danger">Erro ao carregar conteúdo.</div>';
                    container.style.opacity = '1';
                });
        }

        function executeScripts(element) {
            const scripts = element.querySelectorAll('script');
            scripts.forEach(oldScript => {
                const newScript = document.createElement('script');
                Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                oldScript.parentNode.replaceChild(newScript, oldScript);
            });
        }

        function moveItem(url) {
            const container = document.getElementById('planning-content');
            container.style.opacity = '0.5';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (response.ok) {
                        loadTab(null, 'activities');
                    } else {
                        alert('Erro ao mover o item.');
                        container.style.opacity = '1';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    container.style.opacity = '1';
                });
        }

        function toggleWbsGroup(wbsId) {
            const rows = document.querySelectorAll(`.wbs-group-${wbsId}`);
            const icon = document.getElementById(`wbs-icon-${wbsId}`);

            let isHidden = false;

            rows.forEach(row => {
                if (row.classList.contains('collapse') && !row.classList.contains('show')) {
                    if (row.style.display !== 'none') {
                        row.style.display = 'none';
                    }
                } else {
                    if (row.style.display === 'none') {
                        row.style.display = '';
                        isHidden = false;
                    } else {
                        row.style.display = 'none';
                        isHidden = true;
                    }
                }
            });

            if (isHidden) {
                icon.classList.remove('bi-caret-up-fill');
                icon.classList.add('bi-caret-down-fill');
            } else {
                icon.classList.remove('bi-caret-down-fill');
                icon.classList.add('bi-caret-up-fill');
            }
        }
    </script>
@endpush
