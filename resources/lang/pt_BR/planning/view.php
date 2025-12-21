<?php

return [
    'activities' => [
        'sequencing' => 'Sequenciar Atividades',
        'training' => 'Treinamento',
        'minutes' => 'Atas/Minutas', // Ajustei "Minutos" (tempo) para "Atas" (reunião) se for o contexto

        // Novas chaves para a tabela
        'table' => [
            'wbs' => 'Atividade / EAP',
            'start' => 'Início',
            'end' => 'Fim',
            'duration' => 'Duração',
            'resources' => 'Recursos',
            'actions' => 'Ações',
        ],
        'menu' => [
            'new_activity' => 'Nova Atividade',
            'new_subitem' => 'Novo Sub-item',
            'delete_item' => 'Excluir Item',
        ],
        'task' => [
            'days' => 'dias',
            'hours' => 'horas',
            'status' => [
                'completed' => 'Concluído',
                'not_started' => 'Não Iniciada',
            ],
            'actions' => [
                'edit' => 'Editar',
                'delete' => 'Excluir',
            ],
            'details' => [
                'owner' => 'Responsável:',
                'not_defined' => 'Não definido',
                'effort' => 'Esforço:',
                'units' => [
                    'person_hour' => 'Pessoas/Hora',
                    'minutes' => 'Minutos',
                    'days' => 'Dias',
                    'hours' => 'Horas',
                ],
            ],
        ],
        'empty' => [
            'message' => 'Nenhuma estrutura EAP definida.',
            'btn' => 'Criar primeiro item da EAP',
        ],
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
