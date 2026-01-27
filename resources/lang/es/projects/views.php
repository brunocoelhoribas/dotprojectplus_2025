<?php

return [
    'title' => 'Proyectos',
    'new_project' => 'Nuevo Proyecto',

    'index' => [
        'title' => 'Proyectos',
        'filters' => [
            'owner' => 'Responsable:',
            'company' => 'Empresa:',
            'filter_btn' => 'Filtrar',
            'all_owners' => 'Todos',
            'all_companies' => 'Todas',
            'status_all' => 'Todos',
        ],
        'table' => [
            'complete' => 'Completa',
            'company' => 'Empresa',
            'name' => 'Nombre del Proyecto',
            'start' => 'Inicio',
            'end' => 'Fin',
            'updated' => 'Act.',
            'owner' => 'Responsable',
            'tasks' => 'Actividades',
            'selection' => 'Selección',
            'empty' => 'No se encontraron proyectos para este filtro.',
        ],
        'batch' => [
            'change_status' => 'Cambiar estado a...',
            'update_btn' => 'Actualizar estado del proyecto',
        ],
    ],

    'edit' => [
        'page_title' => 'Editar Proyecto: :name',
        'title' => 'Editar Proyecto',
    ],
    'form' => [
        'create_title' => 'Crear Nuevo Proyecto',
        'edit_title' => 'Editar Proyecto',
        'name' => 'Nombre del Proyecto',
        'company' => 'Empresa',
        'select_company' => 'Seleccione una Empresa',
        'owner' => 'Dueño del Proyecto',
        'select_owner' => 'Seleccione un Dueño',
        'target_budget' => 'Presupuesto Previsto',
        'start_date' => 'Fecha de Inicio',
        'end_date' => 'Fecha Final Meta',
        'status' => 'Estado',
        'priority' => 'Prioridad',
        'description' => 'Descripción',
        'cancel' => 'Cancelar',
        'save' => 'Guardar Proyecto',
        'update' => 'Actualizar Proyecto',
    ],

    'show' => [
        'edit' => 'Editar',
        'report' => 'Informe',
        'delete' => 'Eliminar',
        'confirm_delete' => '¿Está seguro?',

        'details' => [
            'name' => 'Nombre:',
            'company' => 'Empresa:',
            'owner' => 'Responsable:',
            'start_date' => 'Fecha de Inicio:',
            'status' => 'Estado:',
            'hours' => 'Horas planificadas:',
            'end_date' => 'Fecha Final Prevista:',
            'priority' => 'Prioridad:',
            'budget' => 'Presupuesto Previsto:',
        ],

        'tabs' => [
            'initiation' => 'Iniciación',
            'planning' => 'Planificación y Monitoreo',
            'execution' => 'Ejecución',
            'closing' => 'Cierre',
        ],

        'initiation_tabs' => [
            'charter' => 'Acta de Constitución',
            'stakeholder' => 'Interesados',
        ],

        'charter' => [
            'title' => 'Acta de Constitución del Proyecto',
            'form' => [
                'title' => 'Título',
                'justification' => 'Justificación',
                'expected_results' => 'Resultados esperados',
                'restrictions' => 'Restricciones',
                'start_date' => 'Fecha de Inicio (Acta)',
                'milestones' => 'Hitos',
                'manager' => 'Gerente del proyecto',
                'objectives' => 'Objetivos',
                'premises' => 'Premisas',
                'budget' => 'Presupuesto ($)',
                'end_date' => 'Fecha de Cierre (Acta)',
                'success_criteria' => 'Criterios de aceptación',
                'approved_comments' => 'Comentarios de aprobación/no aprobación',
                'authorized_comments' => 'Comentarios de autorización/no autorización',
                'generate_pdf' => 'Generar PDF',
                'save_draft' => 'Guardar Borrador',
            ],
        ],

        'stakeholder' => [
            'title' => 'Interesados',
            'generate_pdf' => 'Generar PDF',
            'new_btn' => 'Nuevo Interesado',
            'table' => [
                'name' => 'Interesado',
                'responsibilities' => 'Responsabilidades',
                'interest' => 'Interés',
                'power' => 'Poder',
                'strategy' => 'Estrategia',
                'actions' => 'Acciones',
                'empty' => 'Ningún interesado registrado.',
                'save_charter_hint' => 'Guarde el Acta de Constitución antes de agregar interesados.',
            ],
            'edit' => 'Editar',
        ],
    ],
];
