<?php

return [
    'activities' => [
        'sequencing' => 'Sequenciar Atividades',
        'training' => 'Treinamento',
        'minutes' => 'Atas/Minutas',

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
    'risks' => [
        'title' => 'Riscos',
        'active_risks' => 'Riscos Ativos',
        'inactive_risks' => 'Riscos Inativos',
        'actions' => [
            'management_plan' => 'Plano de gerenciamento dos riscos',
            'checklist_analysis' => 'Análise de checklist',
            'watch_list' => 'Lista de observação',
            'short_term_response' => 'Lista resposta a curto prazo',
            'lessons_learned' => 'Lista de lições aprendidas',
            'response_list' => 'Lista de respostas aos riscos',
            'new_risk' => 'Novo risco',
        ],
        'table' => [
            'id' => 'Id',
            'title' => 'Título',
            'description' => 'Descrição',
            'probability' => 'Probabilidade',
            'impact' => 'Impacto',
            'exposure_factor' => 'Fator de exposição',
            'status' => 'Status',
            'actions' => 'Ações',
            'empty' => 'Nenhum risco encontrado nesta categoria.',
            'strategy' => 'Estratégia'
        ],
        'levels' => [
            'low' => 'Baixa',
            'medium' => 'Média',
            'high' => 'Alta',
            'very_high' => 'Muito Alta',
        ],
        'status' => [
            'identified' => 'Identificado',
            'monitored' => 'Monitorado',
            'occurred' => 'Ocorrido',
            'closed' => 'Encerrado',
        ],
        'strategy' => [
            'accept' => 'Aceitar',
            'avoid' => 'Eliminar',
            'mitigate' => 'Mitigar',
            'transfer' => 'Transferir',
        ],
        'ear' => [
            'organizational' => 'Organizacional',
            'technical' => 'Técnico',
            'external' => 'Externo',
            'pm' => 'Gerência de Projeto',
        ],
        'form' => [
            'section_id' => 'Identificação do Risco',
            'section_analysis' => 'Análise Qualitativa do Risco',
            'section_response' => 'Plano de Resposta ao Risco',
            'section_control' => 'Monitoramento e Controle do Risco',

            'cause' => 'Causa',
            'consequence' => 'Consequência',
            'activity' => 'Atividade',
            'period' => 'Período de vigência',
            'ear_classification' => 'Classificação na EAR',
            'potential' => 'Potencial para outros projetos',
            'notes' => 'Notas',
            'prevention' => 'Plano de prevenção',
            'contingency_reserve' => 'Incluir na reserva de contingência',
            'contingency_plan' => 'Plano de contingência',
            'trigger' => 'Gatilho',
            'responsible' => 'Responsável',
            'active' => 'Ativo',
            'yes' => 'Sim',
            'no' => 'Não',
        ],
        'plan' => [
            'title' => 'Plano de gerenciamento dos riscos',
            'definitions_title' => 'Definições de Probabilidades e Níveis de Impacto',
            'probability' => 'Probabilidade',
            'impact' => 'Impacto',
            'matrix_title' => 'Matriz de Probabilidade e Impacto',
            'monitoring_title' => 'Definições para o Monitoramento e Controle dos Riscos',
            'protocol' => 'Protocolo para aplicação da reserva de contingência:',
            'frequency' => 'Frequência para revisão dos riscos (em dias):',
            'levels' => [
                'super_low' => 'Muito Baixa',
                'low' => 'Baixa',
                'medium' => 'Média',
                'high' => 'Alta',
                'super_high' => 'Muito Alta',
            ],
            'matrix_headers' => [
                'super_low' => 'Muito Baixo',
                'low' => 'Baixo',
                'medium' => 'Médio',
                'high' => 'Alto',
                'super_high' => 'Muito Alto',
            ]
        ],
        'messages' => [
            'plan_updated' => 'Plano de gerenciamento atualizado com sucesso.',
        ],
        'checklist' => [
            'title' => 'Análise de checklist',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'description' => 'Descrição',
                'exposure_factor' => 'Fator de exposição',
                'strategy' => 'Estratégia',
            ],
            'empty' => 'Nenhum risco disponível em outros projetos para importação.',
            'buttons' => [
                'cancel' => 'Cancelar',
                'confirm' => 'Confirmar riscos identificados',
            ],
            'messages' => [
                'empty_selection' => 'Nenhum item selecionado.',
                'success_imported' => ':count riscos importados com sucesso.',
                'imported_note' => 'Importado do projeto #:id via Checklist.',
            ]
        ],
        'watch_list' => [
            'title' => 'Riscos - Lista de observação',
            'return' => 'Retornar aos riscos do projeto',
            'active_risks' => 'Riscos ativos',
            'inactive_risks' => 'Riscos inativos',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'description' => 'Descrição',
                'probability' => 'Probabilidade',
                'impact' => 'Impacto',
                'exposure_factor' => 'Fator de exposição',
                'status' => 'Status',
            ]
        ],
        'short_term' => [
            'title' => 'Riscos - Lista resposta a curto prazo',
            'active_risks' => 'Riscos ativos',
            'inactive_risks' => 'Riscos inativos',
            'empty' => 'Nenhum risco de alta prioridade encontrado.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'description' => 'Descrição',
                'probability' => 'Probabilidade',
                'impact' => 'Impacto',
                'exposure_factor' => 'Fator de exposição',
                'status' => 'Status',
            ],
            'close' => 'Fechar'
        ],
        'lessons_learned' => [
            'title' => 'Riscos - Lista de lições aprendidas',
            'active_risks' => 'Riscos ativos',
            'inactive_risks' => 'Riscos inativos',
            'empty' => 'Nenhuma lição aprendida registrada.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'lessons' => 'Lições Aprendidas',
            ],
            'close' => 'Fechar'
        ],
        'response_list' => [
            'title' => 'Riscos - Lista de respostas aos riscos',
            'active_risks' => 'Riscos ativos',
            'inactive_risks' => 'Riscos inativos',
            'empty' => 'Nenhum plano de resposta registrado.',
            'table' => [
                'id' => 'Id',
                'title' => 'Título',
                'exposure_factor' => 'Fator de exposição',
                'strategy' => 'Estratégia',
                'prevention' => 'Plano de prevenção',
                'contingency' => 'Plano de contingência',
            ],
            'close' => 'Fechar'
        ],
    ],
];
