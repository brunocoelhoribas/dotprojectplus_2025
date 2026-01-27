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
    'risks' => [
        'title' => 'Riesgos',
        'active_risks' => 'Riesgos Activos',
        'inactive_risks' => 'Riesgos Inactivos',
        'actions' => [
            'management_plan' => 'Plan de Gestión de Riesgos',
            'checklist_analysis' => 'Análisis de Lista de Verificación',
            'watch_list' => 'Lista de Observación',
            'short_term_response' => 'Lista de Respuesta a Corto Plazo',
            'lessons_learned' => 'Lista de Lecciones Aprendidas',
            'response_list' => 'Lista de Respuesta a los Riesgos',
            'new_risk' => 'Nuevo Riesgo',
        ],
        'table' => [
            'id' => 'ID',
            'title' => 'Título',
            'description' => 'Descripción',
            'probability' => 'Probabilidad',
            'impact' => 'Impacto',
            'exposure_factor' => 'Factor de Exposición',
            'status' => 'Estado',
            'actions' => 'Acciones',
            'empty' => 'No se encontraron riesgos en esta categoría.',
            'strategy' => 'Estrategia'
        ],
        'levels' => [
            'low' => 'Baja',
            'medium' => 'Media',
            'high' => 'Alta',
            'very_high' => 'Muy Alta',
        ],
        'status' => [
            'identified' => 'Identificado',
            'monitored' => 'Monitoreado',
            'occurred' => 'Ocurrido',
            'closed' => 'Cerrado',
        ],
        'strategy' => [
            'accept' => 'Aceptar',
            'avoid' => 'Evitar',
            'mitigate' => 'Mitigar',
            'transfer' => 'Transferir',
        ],
        'ear' => [
            'organizational' => 'Organizacional',
            'technical' => 'Técnico',
            'external' => 'Externo',
            'pm' => 'Gestión de Proyectos',
        ],
        'form' => [
            'section_id' => 'Identificación del Riesgo',
            'section_analysis' => 'Análisis Cualitativo del Riesgo',
            'section_response' => 'Plan de Respuesta al Riesgo',
            'section_control' => 'Monitoreo y Control del Riesgo',
            'cause' => 'Causa',
            'consequence' => 'Consecuencia',
            'activity' => 'Actividad',
            'period' => 'Período de Vigencia',
            'ear_classification' => 'Clasificación EAR',
            'potential' => 'Potencial para otros proyectos',
            'notes' => 'Notas',
            'prevention' => 'Plan de Prevención',
            'contingency_reserve' => 'Incluir en reserva de contingencia',
            'contingency_plan' => 'Plan de Contingencia',
            'trigger' => 'Disparador',
            'responsible' => 'Responsable',
            'active' => 'Activo',
            'yes' => 'Sí',
            'no' => 'No',
        ],
        'checklist' => [
            'title' => 'Análisis de Checklist',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'description' => 'Descripción',
                'exposure_factor' => 'Factor de exposición',
                'strategy' => 'Estrategia',
            ],
            'empty' => 'No hay riesgos disponibles de otros proyectos para importar.',
            'buttons' => [
                'cancel' => 'Cancelar',
                'confirm' => 'Confirmar riesgos identificados',
            ],
            'messages' => [
                'empty_selection' => 'Ningún elemento seleccionado.',
                'success_imported' => ':count riesgos importados con éxito.',
                'imported_note' => 'Importado del proyecto #:id vía Checklist.',
            ]
        ],
        'watch_list' => [
            'title' => 'Lista de Observación',
            'active_risks' => 'Riesgos activos',
            'inactive_risks' => 'Riesgos inactivos',
            'empty' => 'No se encontraron riesgos de baja prioridad para observación.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'description' => 'Descripción',
                'probability' => 'Probabilidad',
                'impact' => 'Impacto',
                'exposure_factor' => 'Factor de exposición',
                'status' => 'Estado',
            ],
            'close' => 'Cerrar'
        ],
        'short_term' => [
            'title' => 'Lista de Respuesta a Corto Plazo',
            'active_risks' => 'Riesgos activos',
            'inactive_risks' => 'Riesgos inactivos',
            'empty' => 'No se encontraron riesgos de alta prioridad.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'description' => 'Descripción',
                'probability' => 'Probabilidad',
                'impact' => 'Impacto',
                'exposure_factor' => 'Factor de exposición',
                'status' => 'Estado',
            ],
            'close' => 'Cerrar'
        ],
        'lessons_learned' => [
            'title' => 'Lista de Lecciones Aprendidas',
            'active_risks' => 'Riesgos activos',
            'inactive_risks' => 'Riesgos inactivos',
            'empty' => 'No hay lecciones aprendidas registradas.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'lessons' => 'Lecciones Aprendidas',
            ],
            'close' => 'Cerrar'
        ],
        'response_list' => [
            'title' => 'Lista de Respuesta a los Riesgos',
            'active_risks' => 'Riesgos activos',
            'inactive_risks' => 'Riesgos inactivos',
            'empty' => 'No hay planes de respuesta registrados.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'exposure_factor' => 'Factor de exposición',
                'strategy' => 'Estrategia',
                'prevention' => 'Plan de prevención',
                'contingency' => 'Plan de contingencia',
            ],
            'close' => 'Cerrar'
        ],
    ],
];
