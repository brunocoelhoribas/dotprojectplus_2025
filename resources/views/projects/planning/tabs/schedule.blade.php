<div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-outline-secondary btn-sm" onclick="alert('Feature Coming Soon')">
        <i class="bi bi-list-check me-1"></i> {{ __('planning/view.schedule.list_baselines') }}
    </button>
</div>

<div class="gantt-wrapper mb-4 shadow-sm">
    <div class="task-list-container">
        <div class="task-list-header">
            {{ __('planning/view.schedule.activity_name') }}
        </div>
        <div class="task-list-body" id="left-side-scroll">
            @forelse($ganttData as $task)
                <div class="task-row" title="{{ $task['name'] }}">
                    <i class="bi bi-file-text me-2 text-primary opacity-75"></i>
                    {{ Str::limit($task['name'], 40) }}
                </div>
            @empty
                <div class="p-3 text-center text-muted small">{{ __('planning/view.schedule.no_task') }}</div>
            @endforelse
        </div>
    </div>

    <div class="gantt-chart-container" id="right-side-scroll">
        <svg id="gantt-chart"></svg>
    </div>
</div>

<div class="metrics-panel shadow-sm">
    <div class="row g-4">
        <div class="col-lg-4 border-end-lg">
            <h6 class="fw-bold text-secondary mb-3 border-bottom pb-2">{{ __('planning/view.schedule.gantt.params_metrics') }}</h6>

            <div class="metric-row">
                <div class="metric-label">{{ __('planning/view.schedule.baseline_label') }}</div>
                <div class="metric-value">
                    <select class="form-select form-select-sm" id="baseline-select" onchange="handleBaselineChange()">
                        <option value="current" {{ $selectedBaseline === 'current' ? 'selected' : '' }}>
                            {{ __('planning/view.schedule.current_position') }}
                        </option>
                        @foreach($baselines as $base)
                            <option value="{{ $base->id }}" {{ $selectedBaseline === $base->id ? 'selected' : '' }}>
                                {{ $base->baseline_date->format('d/m/Y') }} - {{ $base->baseline_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="metric-row">
                <div class="metric-label">{{ __('planning/view.schedule.report_date') }}</div>
                <div class="metric-value">
                    <input type="date" class="form-control form-control-sm"
                           id="report-date"
                           value="{{ request('report_date', date('Y-m-d')) }}"
                           onchange="handleDateChange()">
                </div>
            </div>

            <hr class="my-3 opacity-25">

            <div class="metric-row">
                <div class="metric-label">{{ __('planning/view.schedule.metrics.pv') }}</div>
                <div class="metric-value">
                    <input type="text" class="form-control form-control-sm bg-light" readonly value="{{ $evmData['total_vp'] }}">
                </div>
            </div>

            <div class="metric-row">
                <div class="metric-label">{{ __('planning/view.schedule.metrics.ev') }}</div>
                <div class="metric-value">
                    <input type="text" class="form-control form-control-sm bg-light" readonly value="{{ $evmData['total_va'] }}">
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-6">
                    <div class="p-2 border rounded bg-white text-center">
                        <small class="d-block text-muted mb-1">{{ __('planning/view.schedule.metrics.sv') }}</small>
                        <span class="fw-bold {{ $evmData['vpr'] < 0 ? 'text-danger' : 'text-success' }}">{{ $evmData['vpr'] }}</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 border rounded bg-white text-center">
                        <small class="d-block text-muted mb-1">{{ __('planning/view.schedule.metrics.spi') }}</small>
                        <span class="fw-bold {{ $evmData['idp'] < 1 ? 'text-danger' : 'text-success' }}">{{ $evmData['idp'] }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-3 p-2 bg-white rounded border text-muted" style="font-size: 0.75rem;">
                <div class="d-flex align-items-center mb-1"><span class="badge bg-danger p-1 me-2 rounded-circle" style="width:8px; height:8px;"></span> {{ __('planning/view.schedule.legend.behind') }}</div>
                <div class="d-flex align-items-center mb-1"><span class="badge bg-success p-1 me-2 rounded-circle" style="width:8px; height:8px;"></span> {{ __('planning/view.schedule.legend.ahead') }}</div>
                <div class="d-flex align-items-center"><span class="badge bg-secondary p-1 me-2 rounded-circle" style="width:8px; height:8px;"></span> {{ __('planning/view.schedule.legend.track') }}</div>
            </div>
        </div>

        <div class="col-lg-8">
            <h6 class="fw-bold text-secondary mb-3 pb-2 border-bottom">{{ __('planning/view.schedule.gantt.graph') }}</h6>
            <div class="bg-white p-2 rounded border" style="height: 350px;">
                <canvas id="evmChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const i18n = {
            gantt: {
                start: "{{ __('planning/view.schedule.gantt.start') }}",
                end: "{{ __('planning/view.schedule.gantt.end') }}",
                progress: "{{ __('planning/view.schedule.gantt.progress') }}",
                noData: "{{ __('planning/view.schedule.gantt.no_data') }}"
            },
            metrics: {
                pv: "{{ __('planning/view.schedule.metrics.pv') }}",
                ev: "{{ __('planning/view.schedule.metrics.ev') }}"
            }
        };

        const tasks = @json($ganttData);
        const ganttContainer = document.getElementById('gantt-chart');

        if (ganttContainer) ganttContainer.innerHTML = '';

        if (tasks.length > 0 && typeof Gantt !== 'undefined') {
            const gantt = new Gantt("#gantt-chart", tasks, {
                header_height: 50,
                column_width: 30,
                step: 24,
                view_modes: ['Week', 'Month'],
                bar_height: 25,
                bar_corner_radius: 3,
                arrow_curve: 5,
                padding: 18,
                view_mode: 'Month',
                date_format: 'YYYY-MM-DD',
                language: '{{ app()->getLocale() }}',
                custom_popup_html: function (task) {
                    return `
                    <div class="p-2 small text-start bg-white border rounded shadow-sm">
                        <div class="fw-bold mb-1 text-dark">${task.name}</div>
                        <div class="text-muted">${i18n.gantt.start}: ${task.start}</div>
                        <div class="text-muted">${i18n.gantt.end}: ${task.end}</div>
                        <div class="mt-1 fw-bold text-primary">${i18n.gantt.progress}: ${task.progress}%</div>
                    </div>`;
                }
            });

            const rightSide = document.getElementById('right-side-scroll');
            const leftSide = document.getElementById('left-side-scroll');

            if (rightSide && leftSide) {
                rightSide.addEventListener('scroll', function () {
                    leftSide.scrollTop = rightSide.scrollTop;
                });
            }
        } else if (ganttContainer) {
            ganttContainer.parentNode.innerHTML = `<div class="p-5 text-center text-muted bg-light border rounded m-3">${i18n.gantt.noData}</div>`;
        }

        const evmData = @json($evmData);
        const canvas = document.getElementById('evmChart');

        if (canvas && typeof Chart !== 'undefined') {
            const ctx = canvas.getContext('2d');

            if (window.currentEvmChart instanceof Chart) {
                window.currentEvmChart.destroy();
            }

            window.currentEvmChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: evmData.labels,
                    datasets: [
                        {
                            label: i18n.metrics.pv,
                            data: evmData.vp,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            pointBackgroundColor: '#0d6efd',
                            pointRadius: 4,
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: i18n.metrics.ev,
                            data: evmData.va,
                            borderColor: '#198754',
                            backgroundColor: 'rgba(25, 135, 84, 0.1)',
                            pointBackgroundColor: '#198754',
                            pointStyle: 'rectRot',
                            pointRadius: 5,
                            borderWidth: 2,
                            tension: 0.3,
                            spanGaps: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#000',
                            bodyColor: '#333',
                            borderColor: '#dee2e6',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f0f0f0' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        }

        window.handleBaselineChange = function () { reloadTabWithParams(); };
        window.handleDateChange = function () { reloadTabWithParams(); };

        function reloadTabWithParams() {
            const baselineSelect = document.getElementById('baseline-select');
            const dateInput = document.getElementById('report-date');

            if (!baselineSelect || !dateInput) return;

            const baselineId = baselineSelect.value;
            const reportDate = dateInput.value;

            const container = document.getElementById('planning-content');

            let url = "{{ route('projects.planning.tab', ['project' => $project->project_id, 'tab' => 'schedule']) }}";
            url += `?baseline_id=${baselineId}&report_date=${reportDate}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    container.innerHTML = data.html;

                    if (typeof executeScripts === 'function') {
                        executeScripts(container);
                    } else {
                        const scripts = container.querySelectorAll('script');
                        scripts.forEach(old => {
                            const newScript = document.createElement('script');
                            Array.from(old.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.appendChild(document.createTextNode(old.innerHTML));
                            old.parentNode.replaceChild(newScript, old);
                        });
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                });
        }
    })();
</script>
