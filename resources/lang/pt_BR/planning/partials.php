<?php
return [
    'training' => [
        'title' => 'Necessidade de Treinamento',
        'description_label' => 'Descreva as necessidades de treinamento da equipe:',
    ],
    'minutes' => [
        'create_title' => 'Registrar Nova Reunião',
        'edit_title' => 'Editar Ata',
        'type_label' => 'Tipo',
        'types' => [
            'effort' => 'Esforço',
            'duration' => 'Duração',
            'resource' => 'Recursos',
            'size' => 'Tamanho',
        ],
        'date' => 'Data',
        'members' => 'Membros',
        'members_hint' => 'Segure Ctrl para selecionar vários.',
        'report_label' => 'Relatório',
        'btn_save' => 'Salvar Ata',
        'btn_update' => 'Atualizar Ata',
        'btn_cancel_edit' => 'Cancelar Edição',
        'table' => [
            'date' => 'Data',
            'type' => 'Tipo',
            'report' => 'Relatório',
            'participants' => 'Participantes',
            'actions' => 'Ações',
            'empty' => 'Nenhum registro.',
        ],
        'confirm_delete' => 'Excluir esta ata?',
    ],
    'sequencing' => [
        'title' => 'Sequenciar Atividades',
        'back_btn' => 'Voltar',
        'info_text' => 'Defina a ordem lógica de execução. A <strong>Atividade</strong> só poderá iniciar após a conclusão de suas <strong>Predecessoras</strong>.',
        'table' => [
            'activity' => 'Atividade',
            'current_pred' => 'Predecessora(s) Atual(is)',
            'add_pred' => 'Adicionar Predecessora',
            'no_dependency' => '- Nenhuma dependência -',
            'select_placeholder' => 'Selecione...',
            'remove_title' => 'Remover vínculo',
            'no_date' => 'Sem data',
        ],
        'gantt' => [
            'title' => 'Visualização Gráfica (Gantt)',
            'day' => 'Dia',
            'week' => 'Semana',
            'month' => 'Mês',
            'no_tasks' => 'Sem tarefas com datas definidas.',
        ]
    ]
];
