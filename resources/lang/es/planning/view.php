<?php

return [
    'activities' => [
        'sequencing' => 'Secuenciar Actividades',
        'training' => 'Capacitación',
        'minutes' => 'Actas',

        'table' => [
            'wbs' => 'Actividad / EDT',
            'start' => 'Inicio',
            'end' => 'Fin',
            'duration' => 'Duración',
            'resources' => 'Recursos',
            'actions' => 'Acciones',
        ],
        'menu' => [
            'new_activity' => 'Nueva Actividad',
            'new_subitem' => 'Nuevo Sub-elemento',
            'delete_item' => 'Eliminar Elemento',
        ],
        'task' => [
            'days' => 'días',
            'hours' => 'horas',
            'status' => [
                'completed' => 'Completado',
                'not_started' => 'No Iniciada',
            ],
            'actions' => [
                'edit' => 'Editar',
                'delete' => 'Eliminar',
            ],
            'details' => [
                'owner' => 'Responsable:',
                'not_defined' => 'No definido',
                'effort' => 'Esfuerzo:',
                'units' => [
                    'person_hour' => 'Personas/Hora',
                    'minutes' => 'Minutos',
                    'days' => 'Días',
                    'hours' => 'Horas',
                ],
            ],
        ],
        'empty' => [
            'message' => 'Ninguna estructura EDT definida.',
            'btn' => 'Crear primer elemento de la EDT',
        ],
    ],
    'schedule' => [
        'title' => 'Verifique la secuencia de actividades utilizando el diagrama de Gantt',
        'activity_name' => 'Nombre de la Actividad',
        'list_baselines' => 'Listar Líneas Base',
        'baseline_label' => 'Línea Base',
        'current_position' => 'Posición Actual',
        'date_label' => 'Fecha',
        'report_date' => 'Fecha de Corte',

        'metrics' => [
            'pv' => 'Valor Planificado (VP)',
            'ev' => 'Valor Ganado (VG)',
            'sv' => 'Variación del Cronograma (SV)',
            'spi' => 'Índice de Desempeño (SPI)',
        ],

        'legend' => [
            'behind' => 'SPI < 1: El proyecto está retrasado',
            'ahead'  => 'SPI > 1: El proyecto está adelantado',
            'track'  => 'SPI = 1: El proyecto está a tiempo',
        ],

        'gantt' => [
            'start' => 'Inicio',
            'end' => 'Fin',
            'progress' => 'Progreso',
            'no_data' => 'No hay datos para mostrar.',
        ]
    ],
];
