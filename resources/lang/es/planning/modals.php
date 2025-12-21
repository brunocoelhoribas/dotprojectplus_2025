<?php

return [
    'common' => [
        'cancel' => 'Cancelar',
        'save' => 'Guardar',
        'save_changes' => 'Guardar Cambios',
        'confirm_delete' => 'Sí, Eliminar',
        'start' => 'Inicio',
        'end' => 'Fin',
        'duration' => 'Duración (días)',
    ],
    'wbs' => [
        'create_title' => 'Nuevo Elemento EDT (Sub-nivel)',
        'name_label' => 'Nombre del Paquete de Trabajo',
        'child_hint' => 'Este elemento se creará como un "hijo" del elemento seleccionado.',
        'delete_title' => 'Eliminar Elemento EDT',
        'delete_confirm' => '¿Está seguro de que desea eliminar este paquete de trabajo?',
        'delete_warning' => 'Atención: Todos los sub-elementos (hijos) también serán eliminados. Las actividades vinculadas se desvincularán pero no se borrarán.',
        'confirm_btn' => 'Confirmar Eliminación',
    ],
    'activity' => [
        'create_title' => 'Nueva Actividad',
        'edit_title' => 'Editar Actividad',
        'name_label' => 'Nombre de la Actividad',
        'percent_complete' => '% Completado',
        'status' => [
            '0' => '0% (No Iniciada)',
            '50' => '50% (En Progreso)',
            '100' => '100% (Completada)',
        ],
        'delete_title' => 'Confirmar Eliminación',
        'delete_text' => '¿Está seguro de que desea eliminar esta actividad?',
        'delete_hint' => 'Esta acción eliminará el vínculo con la EDT y no se puede deshacer.',
    ],
];
