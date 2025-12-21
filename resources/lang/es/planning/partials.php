<?php
return [
    'training' => [
        'title' => 'Necesidades de Capacitación',
        'description_label' => 'Describa las necesidades de capacitación del equipo:',
    ],
    'minutes' => [
        'create_title' => 'Registrar Nueva Reunión',
        'edit_title' => 'Editar Acta',
        'type_label' => 'Tipo',
        'types' => [
            'effort' => 'Esfuerzo',
            'duration' => 'Duración',
            'resource' => 'Recursos',
            'size' => 'Tamaño',
        ],
        'date' => 'Fecha',
        'members' => 'Miembros',
        'members_hint' => 'Mantenga Ctrl para seleccionar varios.',
        'report_label' => 'Informe',
        'btn_save' => 'Guardar Acta',
        'btn_update' => 'Actualizar Acta',
        'btn_cancel_edit' => 'Cancelar Edición',
        'table' => [
            'date' => 'Fecha',
            'type' => 'Tipo',
            'report' => 'Informe',
            'participants' => 'Participantes',
            'actions' => 'Acciones',
            'empty' => 'No hay registros.',
        ],
        'confirm_delete' => '¿Eliminar esta acta?',
    ],
    'sequencing' => [
        'title' => 'Secuenciar Actividades',
        'back_btn' => 'Volver',
        'info_text' => 'Defina el orden lógico de ejecución. La <strong>Actividad</strong> solo podrá iniciar después de la conclusión de sus <strong>Predecesoras</strong>.',
        'table' => [
            'activity' => 'Actividad',
            'current_pred' => 'Predecesora(s) Actual(es)',
            'add_pred' => 'Agregar Predecesora',
            'no_dependency' => '- Sin dependencia -',
            'select_placeholder' => 'Seleccione...',
            'remove_title' => 'Eliminar vínculo',
            'no_date' => 'Sin fecha',
        ],
        'gantt' => [
            'title' => 'Vista Gráfica (Gantt)',
            'day' => 'Día',
            'week' => 'Semana',
            'month' => 'Mes',
            'no_tasks' => 'Sin tareas con fechas definidas.',
        ]
    ]
];
