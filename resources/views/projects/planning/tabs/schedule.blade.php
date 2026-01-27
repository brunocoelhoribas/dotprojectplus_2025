<style>
    .gantt-wrapper {
        display: flex;
        border: 1px solid #dee2e6;
        background: white;
        height: 500px;
    }

    .task-list-container {
        width: 35%;
        min-width: 300px;
        border-right: 2px solid #999;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        flex-direction: column;
    }

    .task-list-header {
        height: 50px;
        background-color: #e9ecef;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        padding-left: 15px;
        font-weight: bold;
        font-size: 12px;
        text-transform: uppercase;
        color: #555;
    }

    .task-list-body {
        flex: 1;
        overflow-y: hidden;
    }

    .task-row {
        height: 30px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        padding-left: 10px;
        font-size: 11px;
        white-space: nowrap;
        background-color: white;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: default;
    }

    .task-row:nth-child(even) {
        background-color: #fcfcfc;
    }

    .task-row:hover {
        background-color: #eef;
    }

    .gantt-chart-container {
        width: 65%;
        flex: 1;
        overflow: auto;
        position: relative;
    }

    .gantt .grid-header {
        height: 50px !important;
        fill: #f8f9fa;
    }

    .gantt .bar-label {
        display: none !important;
    }

    .gantt .grid-row {
        fill: transparent !important;
        stroke: #e0e0e0 !important;
    }

    .gantt .bar {
        fill: #b8daff;
        stroke: #004085;
        stroke-width: 1px;
    }

    .gantt .bar-progress {
        fill: #0056b3;
    }

    .baseline-panel {
        background-color: #bfbfbf;
        border: 1px solid #999;
        padding: 15px;
    }
</style>

<div class="bg-warning p-2 text-center fw-bold border border-dark border-bottom-0 mb-0" style="font-size: 14px;">
    {{ __('planning.schedule.title') }}
</div>

<div class="gantt-wrapper mb-4">
    <div class="task-list-container">
        <div class="task-list-header">
            {{ __('planning/view.schedule.activity_name') }}
        </div>
        <div class="task-list-body" id="left-side-scroll">
            @foreach($ganttData as $task)
                <div class="task-row" title="{{ $task['name'] }}">
                    <i class="bi bi-file-text me-2 text-primary"></i>
                    {{ Str::limit($task['name'], 50) }}
                </div>
            @endforeach
        </div>
    </div>

    <div class="gantt-chart-container" id="right-side-scroll">
        <svg id="gantt-chart"></svg>
    </div>
</div>

<div class="baseline-panel">
    <div class="row">
        <div class="col-md-5">
            <div class="mb-3">
                <button class="btn btn-light btn-sm border shadow-sm fw-bold">
                    {{ __('planning/view.schedule.list_baselines') }}
                </button>
            </div>

            <div class="row mb-2 align-items-center">
                <div class="col-6 text-end text-white fw-bold">
                    {{ __('planning/view.schedule.baseline_label') }}
                </div>
                <div class="col-6">
                    <select class="form-select form-select-sm" id="baseline-select" onchange="handleBaselineChange()">
                        <option value="current" {{ $selectedBaseline === 'current' ? 'selected' : '' }}>
                            {{ __('planning/view.schedule.current_position') }}
                        </option>
                        @foreach($baselines as $base)
                            <option value="{{ $base->id }}" {{ $selectedBaseline == $base->id ? 'selected' : '' }}>
                                {{ $base->baseline_date->format('d/m/Y') }} - {{ $base->baseline_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-2 align-items-center">
                <div class="col-6 text-end text-white fw-bold">
                    {{ __('planning/view.schedule.report_date') }}
                </div>
                <div class="col-6">
                    <input type="date" class="form-control form-control-sm"
                           id="report-date"
                           value="{{ request('report_date', date('Y-m-d')) }}"
                           onchange="handleDateChange()">
                </div>
            </div>

            <div class="row mb-2 align-items-center">
                <div class="col-6 text-end text-white fw-bold">
                    {{ __('planning/view.schedule.metrics.pv') }}
                </div>
                <div class="col-6">
                    <input type="text" class="form-control form-control-sm" value="{{ $evmData['total_vp'] }}">
                </div>
            </div>

            <div class="row mb-2 align-items-center">
                <div class="col-6 text-end text-white fw-bold">
                    {{ __('planning/view.schedule.metrics.ev') }}
                </div>
                <div class="col-6">
                    <input type="text" class="form-control form-control-sm" value="{{ $evmData['total_va'] }}">
                </div>
            </div>

            <div class="row mb-2 align-items-center">
                <div class="col-6 text-end text-white fw-bold">
                    {{ __('planning/view.schedule.metrics.sv') }}
                </div>
                <div class="col-6">
                    <input type="text" class="form-control form-control-sm" value="{{ $evmData['vpr'] }}">
                </div>
            </div>

            <div class="row mb-2 align-items-center">
                <div class="col-6 text-end text-white fw-bold">
                    {{ __('planning/view.schedule.metrics.spi') }}
                </div>
                <div class="col-6">
                    <input type="text" class="form-control form-control-sm" value="{{ $evmData['idp'] }}">
                </div>
            </div>

            <div class="mt-3 text-white small" style="font-size: 0.75rem; line-height: 1.2;">
                <div>{{ __('planning/view.schedule.legend.behind') }}</div>
                <div>{{ __('planning/view.schedule.legend.ahead') }}</div>
                <div>{{ __('planning/view.schedule.legend.track') }}</div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="bg-white p-2 rounded shadow-sm" style="height: 320px;">
                <canvas id="evmChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const i18n = {
            gantt: {
                start: "{{ __('planning/view.gantt.start') }}",
                end: "{{ __('planning/view.gantt.end') }}",
                progress: "{{ __('planning/view.gantt.progress') }}",
                noData: "{{ __('planning/view.gantt.no_data') }}"
            },
            metrics: {
                pv: "{{ __('planning/view.metrics.pv') }}",
                ev: "{{ __('planning/view.metrics.ev') }}"
            }
        };

        const tasks = @json($ganttData);
        const ganttContainer = document.getElementById('gantt-chart');

        if (ganttContainer) ganttContainer.innerHTML = '';

        if (tasks.length > 0 && typeof Gantt !== 'undefined') {
            new Gantt("#gantt-chart", tasks, {
                header_height: 50,
                column_width: 30,
                step: 24,
                view_modes: ['Week', 'Month'],
                bar_height: 20,
                bar_corner_radius: 0,
                arrow_curve: 5,
                padding: 10,
                view_mode: 'Month',
                date_format: 'YYYY-MM-DD',
                language: '{{ app()->getLocale() }}',
                custom_popup_html: function (task) {
                    return `
                    <div class="p-2 small text-start">
                        <div class="fw-bold mb-1">${task.name}</div>
                        <div>${i18n.gantt.start}: ${task.start}</div>
                        <div>${i18n.gantt.end}: ${task.end}</div>
                        <div>${i18n.gantt.progress}: ${task.progress}%</div>
                    </div>`;
                }
            });

            const rightSide = document.getElementById('right-side-scroll');
            const leftSide = document.getElementById('left-side-scroll');

            if (rightSide && leftSide) {
                rightSide.onscroll = function () {
                    leftSide.scrollTop = rightSide.scrollTop;
                };
            }
        } else if (ganttContainer) {
            ganttContainer.innerHTML = `<div class="p-5 text-center text-muted">${i18n.gantt.noData}</div>`;
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
                            borderColor: '#36a2eb',
                            backgroundColor: '#36a2eb',
                            pointStyle: 'circle',
                            borderWidth: 2,
                            tension: 0.1
                        },
                        {
                            label: i18n.metrics.ev,
                            data: evmData.va,
                            borderColor: '#999999',
                            backgroundColor: '#999999',
                            pointStyle: 'triangle',
                            borderWidth: 2,
                            tension: 0.1,
                            spanGaps: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {y: {beginAtZero: true}},
                    plugins: {legend: {position: 'top'}}
                }
            });
        }

        window.handleBaselineChange = function () {
            reloadTabWithParams();
        };

        window.handleDateChange = function () {
            reloadTabWithParams();
        };

        function reloadTabWithParams() {
            const baselineSelect = document.getElementById('baseline-select');
            const dateInput = document.getElementById('report-date');

            if (!baselineSelect || !dateInput) return;

            const baselineId = baselineSelect.value;
            const reportDate = dateInput.value;

            const container = document.getElementById('planning-content');
            if (container) container.style.opacity = '0.5';

            let url = "{{ route('projects.planning.tab', ['project' => $project->project_id, 'tab' => 'schedule']) }}";
            url += `?baseline_id=${baselineId}&report_date=${reportDate}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    container.innerHTML = data.html;
                    container.style.opacity = '1';
                    if (typeof executeScripts === 'function') {
                        executeScripts(container);
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    container.style.opacity = '1';
                });
        }
    })();
</script>
