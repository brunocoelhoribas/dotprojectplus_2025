<?php

return [
    'title' => 'Projects',
    'new_project' => 'New Project',

    'index' => [
        'title' => 'Projects',
        'filters' => [
            'owner' => 'Owner:',
            'company' => 'Company:',
            'filter_btn' => 'Filter',
            'all_owners' => 'All',
            'all_companies' => 'All',
            'status_all' => 'All',
        ],
        'table' => [
            'complete' => 'Complete',
            'company' => 'Company',
            'name' => 'Project Name',
            'start' => 'Start',
            'end' => 'End',
            'updated' => 'Upd.',
            'owner' => 'Owner',
            'tasks' => 'Tasks',
            'selection' => 'Selection',
            'empty' => 'No projects found for this filter.',
        ],
        'batch' => [
            'change_status' => 'Change status to...',
            'update_btn' => 'Update project status',
        ],
    ],

    'form' => [
        'create_title' => 'Create New Project',
        'edit_title' => 'Edit Project',
        'name' => 'Project Name',
        'company' => 'Company',
        'select_company' => 'Select a Company',
        'owner' => 'Project Owner',
        'select_owner' => 'Select an Owner',
        'target_budget' => 'Target Budget',
        'start_date' => 'Start Date',
        'end_date' => 'Target End Date',
        'status' => 'Status',
        'priority' => 'Priority',
        'description' => 'Description',
        'cancel' => 'Cancel',
        'save' => 'Save Project',
        'update' => 'Update Project',
    ],

    'show' => [
        'edit' => 'Edit',
        'report' => 'Report',
        'delete' => 'Delete',
        'confirm_delete' => 'Are you sure?',

        'details' => [
            'name' => 'Name:',
            'company' => 'Company:',
            'owner' => 'Owner:',
            'start_date' => 'Start Date:',
            'status' => 'Status:',
            'hours' => 'Planned Hours:',
            'end_date' => 'Target End Date:',
            'priority' => 'Priority:',
            'budget' => 'Target Budget:',
        ],

        'tabs' => [
            'initiation' => 'Initiation',
            'planning' => 'Planning & Monitoring',
            'execution' => 'Execution',
            'closing' => 'Closing',
        ],

        'initiation_tabs' => [
            'charter' => 'Project Charter',
            'stakeholder' => 'Stakeholder',
        ],

        'charter' => [
            'title' => 'Project Charter',
            'form' => [
                'title' => 'Title',
                'justification' => 'Justification',
                'expected_results' => 'Expected Results',
                'restrictions' => 'Restrictions',
                'start_date' => 'Start Date (Charter)',
                'milestones' => 'Milestones',
                'manager' => 'Project Manager',
                'objectives' => 'Objectives',
                'premises' => 'Premises',
                'budget' => 'Budget ($)',
                'end_date' => 'End Date (Charter)',
                'success_criteria' => 'Success Criteria',
                'approved_comments' => 'Approval/Disapproval Comments',
                'authorized_comments' => 'Authorization/Non-authorization Comments',
                'generate_pdf' => 'Generate PDF',
                'save_draft' => 'Save Draft',
            ],
        ],

        'stakeholder' => [
            'title' => 'Stakeholders',
            'generate_pdf' => 'Generate PDF',
            'new_btn' => 'New Stakeholder',
            'table' => [
                'name' => 'Stakeholder',
                'responsibilities' => 'Responsibilities',
                'interest' => 'Interest',
                'power' => 'Power',
                'strategy' => 'Strategy',
                'actions' => 'Actions',
                'empty' => 'No stakeholders registered.',
                'save_charter_hint' => 'Save the Project Charter before adding stakeholders.',
            ],
            'edit' => 'Edit',
        ],
    ],
];
