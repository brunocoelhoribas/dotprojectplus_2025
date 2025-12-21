<?php

return [
    'activities' => [
        'sequencing' => 'Sequence Activities',
        'training' => 'Training',
        'minutes' => 'Minutes',

        'table' => [
            'wbs' => 'Activity / WBS',
            'start' => 'Start',
            'end' => 'End',
            'duration' => 'Duration',
            'resources' => 'Resources',
            'actions' => 'Actions',
        ],
        'menu' => [
            'new_activity' => 'New Activity',
            'new_subitem' => 'New Sub-item',
            'delete_item' => 'Delete Item',
        ],
        'task' => [
            'days' => 'days',
            'hours' => 'hours',
            'status' => [
                'completed' => 'Completed',
                'not_started' => 'Not Started',
            ],
            'actions' => [
                'edit' => 'Edit',
                'delete' => 'Delete',
            ],
            'details' => [
                'owner' => 'Owner:',
                'not_defined' => 'Not defined',
                'effort' => 'Effort:',
                'units' => [
                    'person_hour' => 'Person/Hour',
                    'minutes' => 'Minutes',
                    'days' => 'Days',
                    'hours' => 'Hours',
                ],
            ],
        ],
        'empty' => [
            'message' => 'No WBS structure defined.',
            'btn' => 'Create first WBS item',
        ],
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
