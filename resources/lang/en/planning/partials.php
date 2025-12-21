<?php
return [
    'training' => [
        'title' => 'Training Needs',
        'description_label' => 'Describe the team training needs:',
    ],
    'minutes' => [
        'create_title' => 'Register New Meeting',
        'edit_title' => 'Edit Minute',
        'type_label' => 'Type',
        'types' => [
            'effort' => 'Effort',
            'duration' => 'Duration',
            'resource' => 'Resources',
            'size' => 'Size',
        ],
        'date' => 'Date',
        'members' => 'Members',
        'members_hint' => 'Hold Ctrl to select multiple.',
        'report_label' => 'Report',
        'btn_save' => 'Save Minute',
        'btn_update' => 'Update Minute',
        'btn_cancel_edit' => 'Cancel Edit',
        'table' => [
            'date' => 'Date',
            'type' => 'Type',
            'report' => 'Report',
            'participants' => 'Participants',
            'actions' => 'Actions',
            'empty' => 'No records found.',
        ],
        'confirm_delete' => 'Delete this minute?',
    ],
    'sequencing' => [
        'title' => 'Sequence Activities',
        'back_btn' => 'Back',
        'info_text' => 'Define the logical execution order. The <strong>Activity</strong> can only start after its <strong>Predecessors</strong> are completed.',
        'table' => [
            'activity' => 'Activity',
            'current_pred' => 'Current Predecessor(s)',
            'add_pred' => 'Add Predecessor',
            'no_dependency' => '- No dependency -',
            'select_placeholder' => 'Select...',
            'remove_title' => 'Remove link',
            'no_date' => 'No date',
        ],
        'gantt' => [
            'title' => 'Graphical View (Gantt)',
            'day' => 'Day',
            'week' => 'Week',
            'month' => 'Month',
            'no_tasks' => 'No tasks with defined dates.',
        ]
    ]
];
