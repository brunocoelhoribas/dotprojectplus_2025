@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ __('projects/pdf.plan.plan_title') }}</title>
    <style>
        @page { margin: 2.5cm 2cm; }
        body { font-family: Arial, Verdana, sans-serif; font-size: 10px; color: #000; line-height: 1.4; }
        h1 { font-size: 16px; text-align: center; text-transform: uppercase; margin-bottom: 20px; }
        h2 { font-size: 12px; background-color: #f2f2f2; padding: 5px 10px; border: 1px solid #ccc; margin-top: 20px; margin-bottom: 10px; page-break-after: avoid; }
        h3 { font-size: 11px; border-bottom: 1px solid #000; margin-top: 15px; margin-bottom: 5px; padding-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #000; padding: 4px 6px; text-align: left; vertical-align: top; font-size: 10px; }
        th { background-color: #e0e0e0; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .page-break { page-break-before: always; }
        .no-data { color: #666; font-style: italic; padding: 10px 0; }
        .cover-title { font-size: 24pt; font-weight: bold; text-align: center; margin-top: 5cm; margin-bottom: 3cm; }
        .cover-info { font-size: 12pt; text-align: center; margin-bottom: 10px; }
        .cover-label { font-weight: bold; display: block; margin-bottom: 2px; }
    </style>
</head>
<body>

<div class="cover-title">{{ __('projects/pdf.plan.plan_title') }}</div>

<div class="cover-info">
    <span class="cover-label">{{ __('projects/pdf.plan.cover_project') }}</span>
    {{ $project->project_name }}
</div>
<div class="cover-info">
    <span class="cover-label">{{ __('projects/pdf.plan.cover_manager') }}</span>
    {{ $manager->full_name ?? 'N/A' }}
</div>
<div class="cover-info">
    <span class="cover-label">{{ __('projects/pdf.plan.cover_date') }}</span>
    {{ date('d/m/Y') }}
</div>

<div class="page-break"></div>

<h2>{{ __('projects/pdf.plan.summary') }}</h2>
<ul>
    <li>{{ __('projects/pdf.plan.sec_scope') }}</li>
    <li>{{ __('projects/pdf.plan.sec_time') }}</li>
    <li>{{ __('projects/pdf.plan.sec_cost') }}</li>
    <li>{{ __('projects/pdf.plan.sec_quality') }}</li>
    <li>{{ __('projects/pdf.plan.sec_hr') }}</li>
    <li>{{ __('projects/pdf.plan.sec_communication') }}</li>
    <li>{{ __('projects/pdf.plan.sec_acquisition') }}</li>
    <li>{{ __('projects/pdf.plan.sec_risks') }}</li>
    <li>{{ __('projects/pdf.plan.sec_stakeholders') }}</li>
</ul>

<div class="page-break"></div>

<h2>{{ __('projects/pdf.plan.sec_scope') }}</h2>

<h3>{{ __('projects/pdf.plan.sec_scope_stmt') }}</h3>
@if($initiating)
    <div style="text-align: justify; margin-bottom: 10px;">
        <strong>Justificativa:</strong><br>
        {!! nl2br(e($initiating->initiating_justification)) !!}
        <br><br>
        <strong>Objetivos:</strong><br>
        {!! nl2br(e($initiating->initiating_objective)) !!}
    </div>
@else
    <div class="no-data">{{ __('projects/pdf.plan.no_data') }}</div>
@endif

<h3>{{ __('projects/pdf.plan.sec_wbs') }}</h3>
<table>
    <thead>
    <tr>
        <th width="15%">{{ __('projects/pdf.plan.lbl_id') }}</th>
        <th>{{ __('projects/pdf.plan.lbl_item') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($wbsItems as $item)
        <tr>
            <td>{{ $item->number }}</td>
            <td style="padding-left: {{ $item->level * 10 }}px">
                {{ $item->name }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<h3>{{ __('projects/pdf.plan.sec_wbs_dict') }}</h3>
<table>
    <thead>
    <tr>
        <th width="20%">{{ __('projects/pdf.plan.lbl_id') }}</th>
        <th>{{ __('projects/pdf.plan.lbl_desc') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($wbsItems as $item)
        <tr>
            <td>{{ $item->number }} - {{ $item->name }}</td>
            <td>{{ $item->description ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="page-break"></div>

<h2>{{ __('projects/pdf.plan.sec_time') }}</h2>

<h3>{{ __('projects/pdf.plan.sec_sequencing') }}</h3>
<table>
    <thead>
    <tr>
        <th width="15%">{{ __('projects/pdf.plan.lbl_id') }}</th>
        <th width="40%">{{ __('projects/pdf.plan.lbl_item') }}</th>
        <th>{{ __('projects/pdf.plan.lbl_predecessors') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($wbsItems as $item)
        @if($item->tasks->count() > 0)
            <tr style="background-color: #f9f9f9;">
                <td colspan="3"><strong>{{ $item->number }} {{ $item->name }}</strong></td>
            </tr>
            @foreach($item->tasks as $index => $task)
                <tr>
                    <td>{{ $item->number }}.{{ $index + 1 }}</td>
                    <td>{{ $task->task_name }}</td>
                    <td>
                        @foreach($task->predecessors as $pred)
                            {{ $pred->task_name }}<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>

<h3>{{ __('projects/pdf.plan.sec_duration') }} & {{ __('projects/pdf.plan.sec_schedule') }}</h3>
<table>
    <thead>
    <tr>
        <th>{{ __('projects/pdf.plan.lbl_item') }}</th>
        <th>{{ __('projects/pdf.plan.lbl_start') }}</th>
        <th>{{ __('projects/pdf.plan.lbl_end') }}</th>
        <th>{{ __('projects/pdf.plan.lbl_duration') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($wbsItems as $item)
        @foreach($item->tasks as $task)
            <tr>
                <td>{{ $task->task_name }}</td>
                <td>
                    {{ $task->task_start_date ? Carbon::parse($task->task_start_date)->format('d/m/Y') : '-' }}
                </td>
                <td>
                    {{ $task->task_end_date ? Carbon::parse($task->task_end_date)->format('d/m/Y') : '-' }}
                </td>
                <td>{{ $task->task_duration }} {{ $task->task_duration_type === 24 ? 'd' : 'h' }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>

<h3>{{ __('projects/pdf.plan.sec_gantt') }}</h3>
<div style="border: 1px dashed #999; padding: 20px; text-align: center; background-color: #fcfcfc;">
    <p>{{ __('projects/pdf.plan.gantt_note') }}</p>
</div>

<div class="page-break"></div>

<h2>{{ __('projects/pdf.plan.sec_cost') }}</h2>
<p>Orçamento Total (Target): <strong>R$ {{ number_format($project->project_target_budget, 2, ',', '.') }}</strong></p>

<h2>{{ __('projects/pdf.plan.sec_risks') }}</h2>
@if(isset($risks) && $risks->count() > 0)
    <table>
        <thead>
        <tr>
            <th>{{ __('projects/pdf.plan.lbl_risk_name') }}</th>
            <th>{{ __('projects/pdf.plan.lbl_risk_impact') }}</th>
            <th>{{ __('projects/pdf.plan.lbl_desc') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($risks as $risk)
            <tr>
                <td>{{ $risk->risk_name }}</td>
                <td>{{ $risk->risk_impact ?? 'N/A' }}</td>
                <td>{{ $risk->risk_description }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="no-data">{{ __('projects/pdf.plan.no_data') }}</div>
@endif

<h2>{{ __('projects/pdf.plan.sec_stakeholders') }}</h2>
@if(isset($stakeholders) && $stakeholders->count() > 0)
    <table>
        <thead>
        <tr>
            <th>{{ __('projects/pdf.plan.lbl_stakeholder') }}</th>
            <th>{{ __('projects/pdf.plan.lbl_role') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($stakeholders as $sh)
            <tr>
                <td>{{ $sh->contact->full_name ?? 'N/A' }}</td>
                <td>{{ $sh->role_name ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="no-data">{{ __('projects/pdf.plan.no_data') }}</div>
@endif

<h2>{{ __('projects/pdf.plan.sec_quality') }}</h2>
<div class="no-data">{{ __('projects/pdf.plan.no_data') }}</div>

<h2>{{ __('projects/pdf.plan.sec_communication') }}</h2>
<div class="no-data">{{ __('projects/pdf.plan.no_data') }}</div>

</body>
</html>
