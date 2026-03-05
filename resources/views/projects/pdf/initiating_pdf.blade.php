@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('projects/pdf.initiating.main_title') }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #999;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }

        .section-header {
            background-color: #eebf2f;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            border: 1px solid #999;
            padding: 8px;
        }

        .main-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            border-bottom: 2px solid #eebf2f;
            padding-bottom: 10px;
        }

        .label {
            font-weight: bold;
            font-size: 10px;
            color: #555;
            text-transform: uppercase;
            display: block;
            margin-bottom: 3px;
        }

        .value {
            font-size: 12px;
            color: #000;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .no-border-bottom { border-bottom: none; }
        .no-border-top { border-top: none; }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>

<div class="main-title">
    {{ __('projects/pdf.initiating.main_title') }}
</div>

<table class="avoid-break">
    <thead>
    <tr>
        <th colspan="2" class="section-header">{{ __('projects/pdf.initiating.section_general') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="width: 70%;">
            <span class="label">{{ __('projects/pdf.initiating.project_title') }}</span>
            <div class="value">{{ $initiating->initiating_title }}</div>
        </td>
        <td style="width: 30%;">
            <span class="label">{{ __('projects/pdf.initiating.budget') }}</span>
            <div class="value">{{ number_format($initiating->initiating_budget, 2, ',', '.') }}</div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <span class="label">{{ __('projects/pdf.initiating.manager') }}</span>
            <div class="value">{{ $manager->full_name ?? 'N/A' }}</div>
        </td>
    </tr>
    </tbody>
</table>

<table class="avoid-break">
    <thead>
    <tr>
        <th colspan="2" class="section-header">{{ __('projects/pdf.initiating.section_dates') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="width: 50%;">
            <span class="label">{{ __('projects/pdf.initiating.start_date') }}</span>
            <div class="value">
                {{ $initiating->initiating_start_date ? Carbon::parse($initiating->initiating_start_date)->format('d/m/Y') : '-' }}
            </div>
        </td>
        <td style="width: 50%;">
            <span class="label">{{ __('projects/pdf.initiating.end_date') }}</span>
            <div class="value">
                {{ $initiating->initiating_end_date ? Carbon::parse($initiating->initiating_end_date)->format('d/m/Y') : '-' }}
            </div>
        </td>
    </tr>
    </tbody>
</table>

<table class="avoid-break">
    <thead>
    <tr>
        <th class="section-header">{{ __('projects/pdf.initiating.section_justification') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <span class="label">{{ __('projects/pdf.initiating.justification') }}</span>
            <div class="value">{!! nl2br(e($initiating->initiating_justification)) !!}</div>
        </td>
    </tr>
    <tr>
        <td>
            <span class="label">{{ __('projects/pdf.initiating.objectives') }}</span>
            <div class="value">{!! nl2br(e($initiating->initiating_objective)) !!}</div>
        </td>
    </tr>
    </tbody>
</table>

<table class="avoid-break">
    <thead>
    <tr>
        <th class="section-header">{{ __('projects/pdf.initiating.section_scope') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <span class="label">{{ __('projects/pdf.initiating.expected_results') }}</span>
            <div class="value">{!! nl2br(e($initiating->initiating_expected_result)) !!}</div>
        </td>
    </tr>
    <tr>
        <td>
            <span class="label">{{ __('projects/pdf.initiating.success_criteria') }}</span>
            <div class="value">{!! nl2br(e($initiating->initiating_success)) !!}</div>
        </td>
    </tr>
    </tbody>
</table>

<table class="avoid-break">
    <thead>
    <tr>
        <th colspan="2" class="section-header">{{ __('projects/pdf.initiating.section_premises') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="width: 50%;">
            <span class="label">{{ __('projects/pdf.initiating.premises') }}</span>
            <div class="value">{!! nl2br(e($initiating->initiating_premise)) !!}</div>
        </td>
        <td style="width: 50%;">
            <span class="label">{{ __('projects/pdf.initiating.restrictions') }}</span>
            <div class="value">{!! nl2br(e($initiating->initiating_restrictions)) !!}</div>
        </td>
    </tr>
    </tbody>
</table>

<table class="avoid-break">
    <thead>
    <tr>
        <th class="section-header">{{ __('projects/pdf.initiating.section_milestones') }}</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <div class="value">{!! nl2br(e($initiating->initiating_milestone)) !!}</div>
        </td>
    </tr>
    </tbody>
</table>

<br><br>
<table style="border: none;">
    <tr>
        <td style="border: none; width: 45%; text-align: center;">
            __________________________________________<br>
            <strong>{{ $manager->full_name ?? __('projects/pdf.initiating.signatures_manager') }}</strong><br>
            <span style="font-size: 10px;">{{ __('projects/pdf.initiating.signatures_manager') }}</span>
        </td>
        <td style="border: none; width: 10%;"></td>
        <td style="border: none; width: 45%; text-align: center;">
            __________________________________________<br>
            <strong>{{ __('projects/pdf.initiating.signatures_sponsor') }}</strong><br>
            <span style="font-size: 10px;">{{ __('projects/pdf.initiating.signatures_approval') }}</span>
        </td>
    </tr>
</table>

</body>
</html>
