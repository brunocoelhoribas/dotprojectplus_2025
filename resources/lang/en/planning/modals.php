<?php

return [
    'common' => [
        'cancel' => 'Cancel',
        'save' => 'Save',
        'save_changes' => 'Save Changes',
        'confirm_delete' => 'Yes, Delete',
        'start' => 'Start',
        'end' => 'End',
        'duration' => 'Duration (days)',
    ],
    'wbs' => [
        'create_title' => 'New WBS Item (Sub-level)',
        'name_label' => 'Work Package Name',
        'child_hint' => 'This item will be created as a "child" of the selected item.',
        'delete_title' => 'Delete WBS Item',
        'delete_confirm' => 'Are you sure you want to delete this work package?',
        'delete_warning' => 'Warning: All sub-items (children) will also be deleted. Linked activities will be unlinked but not deleted.',
        'confirm_btn' => 'Confirm Deletion',
    ],
    'activity' => [
        'create_title' => 'New Activity',
        'edit_title' => 'Edit Activity',
        'name_label' => 'Activity Name',
        'percent_complete' => '% Complete',
        'status' => [
            '0' => '0% (Not Started)',
            '50' => '50% (In Progress)',
            '100' => '100% (Completed)',
        ],
        'delete_title' => 'Confirm Deletion',
        'delete_text' => 'Are you sure you want to delete this activity?',
        'delete_hint' => 'This action will remove the link to the WBS and cannot be undone.',
    ],
];
