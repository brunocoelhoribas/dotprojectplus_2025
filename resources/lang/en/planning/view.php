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
    'risks' => [
        'title' => 'Risks',
        'active_risks' => 'Active Risks',
        'inactive_risks' => 'Inactive Risks',
        'actions' => [
            'management_plan' => 'Risk Management Plan',
            'checklist_analysis' => 'Checklist Analysis',
            'watch_list' => 'Watch List',
            'short_term_response' => 'Short-term Response List',
            'lessons_learned' => 'Lessons Learned List',
            'response_list' => 'Risk Response List',
            'new_risk' => 'New Risk',
        ],
        'table' => [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'probability' => 'Probability',
            'impact' => 'Impact',
            'exposure_factor' => 'Exposure Factor',
            'status' => 'Status',
            'actions' => 'Actions',
            'empty' => 'No risks found in this category.',
            'strategy' => 'Strategy'
        ],
        'levels' => [
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'very_high' => 'Very High',
        ],
        'status' => [
            'identified' => 'Identified',
            'monitored' => 'Monitored',
            'occurred' => 'Occurred',
            'closed' => 'Closed',
        ],
        'strategy' => [
            'accept' => 'Accept',
            'avoid' => 'Avoid',
            'mitigate' => 'Mitigate',
            'transfer' => 'Transfer',
        ],
        'ear' => [
            'organizational' => 'Organizational',
            'technical' => 'Technical',
            'external' => 'External',
            'pm' => 'Project Management',
        ],
        'form' => [
            'section_id' => 'Risk Identification',
            'section_analysis' => 'Qualitative Risk Analysis',
            'section_response' => 'Risk Response Plan',
            'section_control' => 'Risk Monitoring and Control',
            'cause' => 'Cause',
            'consequence' => 'Consequence',
            'activity' => 'Activity',
            'period' => 'Validity Period',
            'ear_classification' => 'RBS Classification',
            'potential' => 'Potential for other projects',
            'notes' => 'Notes',
            'prevention' => 'Prevention Plan',
            'contingency_reserve' => 'Include in contingency reserve',
            'contingency_plan' => 'Contingency Plan',
            'trigger' => 'Trigger',
            'responsible' => 'Owner',
            'active' => 'Active',
            'yes' => 'Yes',
            'no' => 'No',
        ],
        'checklist' => [
            'title' => 'Checklist Analysis',
            'table' => [
                'id' => 'ID',
                'title' => 'Title',
                'description' => 'Description',
                'exposure_factor' => 'Exposure Factor',
                'strategy' => 'Strategy',
            ],
            'empty' => 'No risks available from other projects for import.',
            'buttons' => [
                'cancel' => 'Cancel',
                'confirm' => 'Confirm identified risks',
            ],
            'messages' => [
                'empty_selection' => 'No item selected.',
                'success_imported' => ':count risks imported successfully.',
                'imported_note' => 'Imported from project #:id via Checklist.',
            ]
        ],
    ],
];
