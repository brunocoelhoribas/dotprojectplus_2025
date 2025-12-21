<?php

return [
    'common' => [
        'cancel' => 'Cancelar',
        'save' => 'Salvar',
        'save_changes' => 'Salvar Alterações',
        'confirm_delete' => 'Sim, Excluir',
        'start' => 'Início',
        'end' => 'Fim',
        'duration' => 'Duração (dias)',
    ],
    'wbs' => [
        'create_title' => 'Novo Item EAP (Sub-nível)',
        'name_label' => 'Nome do Pacote de Trabalho',
        'child_hint' => 'Este item será criado como um "filho" do item selecionado.',
        'delete_title' => 'Excluir Item EAP',
        'delete_confirm' => 'Tem certeza que deseja excluir este pacote de trabalho?',
        'delete_warning' => 'Atenção: Todos os sub-itens (filhos) também serão excluídos. As atividades vinculadas serão desvinculadas, mas não apagadas.',
        'confirm_btn' => 'Confirmar Exclusão',
    ],
    'activity' => [
        'create_title' => 'Nova Atividade',
        'edit_title' => 'Editar Atividade',
        'name_label' => 'Nome da Atividade',
        'percent_complete' => '% Concluído',
        'status' => [
            '0' => '0% (Não Iniciada)',
            '50' => '50% (Em Andamento)',
            '100' => '100% (Concluída)',
        ],
        'delete_title' => 'Confirmar Exclusão',
        'delete_text' => 'Tem certeza que deseja excluir esta atividade?',
        'delete_hint' => 'Esta ação removerá o vínculo com a EAP e não poderá ser desfeita.',
    ],
];
