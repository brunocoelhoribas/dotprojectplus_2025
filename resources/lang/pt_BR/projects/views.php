<?php

return [
    'title' => 'Projetos',
    'new_project' => 'Novo Projeto',

    'index' => [
        'title' => 'Projetos',
        'filters' => [
            'owner' => 'Responsável:',
            'company' => 'Empresa:',
            'filter_btn' => 'Filtrar',
            'all_owners' => 'Todos',
            'all_companies' => 'Todas',
            'status_all' => 'Todos',
        ],
        'table' => [
            'complete' => 'Completa',
            'company' => 'Empresa',
            'name' => 'Nome do Projeto',
            'start' => 'Início',
            'end' => 'Encerramento',
            'updated' => 'Atual.',
            'owner' => 'Responsável',
            'tasks' => 'Atividades',
            'selection' => 'Seleção',
            'empty' => 'Nenhum projeto encontrado para este filtro.',
        ],
        'batch' => [
            'change_status' => 'Mudar status para...',
            'update_btn' => 'Atualizar status do projeto',
        ],
    ],
    'edit' => [
        'page_title' => 'Editar Projeto: :name',
        'title' => 'Editar Projeto',
    ],
    'form' => [
        'create_title' => 'Criar Novo Projeto',
        'edit_title' => 'Editar Projeto',
        'name' => 'Nome do Projeto',
        'company' => 'Empresa',
        'select_company' => 'Selecione uma Companhia',
        'owner' => 'Dono do Projeto',
        'select_owner' => 'Selecione um Dono',
        'target_budget' => 'Orçamento Previsto',
        'start_date' => 'Data de Início',
        'end_date' => 'Data Final Alvo',
        'status' => 'Status',
        'priority' => 'Prioridade',
        'description' => 'Descrição',
        'cancel' => 'Cancelar',
        'save' => 'Salvar Projeto',
        'update' => 'Atualizar Projeto',
    ],

    'show' => [
        'edit' => 'Editar',
        'report' => 'Relatório',
        'delete' => 'Excluir',
        'confirm_delete' => 'Tem certeza?',

        'details' => [
            'name' => 'Nome:',
            'company' => 'Empresa:',
            'owner' => 'Responsável:',
            'start_date' => 'Data de Início:',
            'status' => 'Status:',
            'hours' => 'Horas planejadas:',
            'end_date' => 'Data Final Prevista:',
            'priority' => 'Prioridade:',
            'budget' => 'Orçamento Previsto:',
        ],

        'tabs' => [
            'initiation' => 'Iniciação',
            'planning' => 'Planejamento e Monitoramento',
            'execution' => 'Execução',
            'closing' => 'Encerramento',
        ],

        'initiation_tabs' => [
            'charter' => 'Termo de abertura',
            'stakeholder' => 'Stakeholder',
        ],

        'charter' => [
            'title' => 'Termo de Abertura do Projeto',
            'form' => [
                'title' => 'Título',
                'justification' => 'Justificativa',
                'expected_results' => 'Resultados esperados',
                'restrictions' => 'Restrições',
                'start_date' => 'Data de Início (Termo)',
                'milestones' => 'Marcos',
                'manager' => 'Gerente do projeto',
                'objectives' => 'Objetivos',
                'premises' => 'Premissas',
                'budget' => 'Orçamento (R$)',
                'end_date' => 'Data de Encerramento (Termo)',
                'success_criteria' => 'Critérios de aceite',
                'approved_comments' => 'Comentários de aprovação/não aprovação',
                'authorized_comments' => 'Comentários de autorização/não autorização',
                'generate_pdf' => 'Gerar PDF',
                'save_draft' => 'Salvar Rascunho',
            ],
        ],

        'stakeholder' => [
            'title' => 'Stakeholders',
            'generate_pdf' => 'Gerar PDF',
            'new_btn' => 'Novo Stakeholder',
            'table' => [
                'name' => 'Stakeholder',
                'responsibilities' => 'Responsabilidades',
                'interest' => 'Interesse',
                'power' => 'Poder',
                'strategy' => 'Estratégia',
                'actions' => 'Ações',
                'empty' => 'Nenhum stakeholder cadastrado.',
                'save_charter_hint' => 'Salve o Termo de Abertura antes de adicionar stakeholders.',
            ],
            'edit' => 'Editar',
        ],
    ],
];
