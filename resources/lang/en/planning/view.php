<?php

return [
    'activities' => [
        'sequence' => 'Sequence',
        'training' => 'Training',
        'minutes' => 'Minutes',
    ],
    'schedule' => [
        'title' => 'Check the sequence of activities using the Gantt chart',
        'activity_name' => 'Activity Name',
        'list_baselines' => 'List Baselines',
        'baseline_label' => 'Baseline',
        'current_position' => 'Current Position',
        'date_label' => 'Date',
        'report_date' => 'Report Date',

        'metrics' => [
            'pv' => 'Planned Value (PV)',
            'ev' => 'Earned Value (EV)',
            'sv' => 'Schedule Variance (SV)',
            'spi' => 'Schedule Performance Index (SPI)',
        ],

        'legend' => [
            'behind' => 'SPI < 1: Project is behind schedule',
            'ahead'  => 'SPI > 1: Project is ahead of schedule',
            'track'  => 'SPI = 1: Project is on schedule',
        ],

        'gantt' => [
            'start' => 'Start',
            'end' => 'End',
            'progress' => 'Progress',
            'no_data' => 'No data available to display.',
        ]
    ],
];
