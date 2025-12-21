<?php

return [
    'activities' => [
        'sequencing' => 'Sequenciar Atividades',
        'training' => 'Treinamento',
        'minutes' => 'Minutos',
    ],
    'schedule' => [
        'title' => 'Confira o sequenciamento das atividades pelo gráfico de Gantt',
        'activity_name' => 'Nome da Atividade',
        'list_baselines' => 'Listar Baselines',
        'baseline_label' => 'Linha de Base',
        'current_position' => 'Posição Atual',
        'date_label' => 'Data',
        'report_date' => 'Data de Reporte',

        'metrics' => [
            'pv' => 'Valor Planejado (VP)',
            'ev' => 'Valor Agregado (VA)',
            'sv' => 'Variação de Prazo (VPR)',
            'spi' => 'Índice de Desempenho (IDP)',
        ],

        'legend' => [
            'behind' => 'IDP < 1: Cronograma está atrasado',
            'ahead'  => 'IDP > 1: Cronograma está adiantado',
            'track'  => 'IDP = 1: Cronograma está no prazo',
        ],

        'gantt' => [
            'start' => 'Início',
            'end' => 'Fim',
            'progress' => 'Progresso',
            'no_data' => 'Sem dados para exibir.',
        ]
    ],
];
