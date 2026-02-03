@include('projects.planning.create_wbs_modal')
@include('projects.planning.create_activity_modal')
@include('projects.planning.edit_activity_modal')
@include('projects.planning.delete_wbs_modal')
@include('projects.planning.delete_confirmation_modal')
@include('projects.planning.partials.training_modal')
@include('projects.planning.partials.minutes_modal')

<div class="mb-4">
    <span id="tab-subtitle" class="text-muted small"></span>
</div>

<ul class="nav nav-tabs nav-tabs-dotproject mb-4" id="planning-tabs">
    <li class="nav-item"><a class="nav-link active" onclick="loadTab(event, 'activities')">{{ __('projects/tabs.planning.menu.activities') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'schedule')">{{ __('projects/tabs.planning.menu.schedule') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'costs')">{{ __('projects/tabs.planning.menu.costs') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'risks')">{{ __('projects/tabs.planning.menu.risks') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'quality')">{{ __('projects/tabs.planning.menu.quality') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'communication')">{{ __('projects/tabs.planning.menu.communication') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'acquisition')">{{ __('projects/tabs.planning.menu.acquisition') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'stakeholders')">{{ __('projects/tabs.planning.menu.stakeholders') }}</a></li>
    <li class="nav-item"><a class="nav-link" onclick="loadTab(event, 'plan')">{{ __('projects/tabs.planning.menu.plan') }}</a></li>
</ul>

<div id="planning-content" class="position-relative min-vh-50">
    <div id="loader" class="text-center py-5 d-none">
        <div class="spinner-border text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            loadTab(null, 'activities');
        });

        function loadTab(event, tabName) {
            if(event) event.preventDefault();

            if(event) {
                document.querySelectorAll('#planning-tabs .nav-link').forEach(link => link.classList.remove('active'));
                event.target.classList.add('active');
            }

            const container = document.getElementById('planning-content');

            const url = `{{ route('projects.planning.tab', ['project' => $project->project_id, 'tab' => ':tab']) }}`.replace(':tab', tabName);

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = data.html || data;

                    container.style.opacity = '1';
                    executeScripts(container);
                })
                .catch(error => {
                    console.error('Erro:', error);
                    container.innerHTML = `<div class="alert alert-danger">Erro ao carregar aba.</div>`;
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
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
                .then(response => {
                    if (response.ok) { loadTab(null, 'activities'); }
                    else { alert('{{ __('projects/tabs.planning.messages.move_error') }}'); container.style.opacity = '1'; }
                })
                .catch(error => { console.error('Erro:', error); container.style.opacity = '1'; });
        }

        function toggleWbsGroup(wbsId) {
            const rows = document.querySelectorAll(`.wbs-group-${wbsId}`);
            const icon = document.getElementById(`wbs-icon-${wbsId}`);
            let isHidden = false;
            rows.forEach(row => {
                if (row.classList.contains('collapse') && !row.classList.contains('show')) {
                    if (row.style.display !== 'none') { row.style.display = 'none'; }
                } else {
                    if (row.style.display === 'none') { row.style.display = ''; isHidden = false; }
                    else { row.style.display = 'none'; isHidden = true; }
                }
            });
            if (isHidden) { icon.classList.remove('bi-caret-up-fill'); icon.classList.add('bi-caret-down-fill'); }
            else { icon.classList.remove('bi-caret-down-fill'); icon.classList.add('bi-caret-up-fill'); }
        }
    </script>
@endpush
