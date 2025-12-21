<?php

return [
    'activities' => [
        'sequencing' => 'Secuencia',
        'training' => 'Capacitación',
        'minutes' => 'Minutos',
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
