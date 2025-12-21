document.addEventListener('DOMContentLoaded', function() {

    const editModalEl = document.getElementById('editStakeholderModal');
    let editModalInstance = null;
    if (editModalEl) {
        editModalInstance = new bootstrap.Modal(editModalEl);
    }

    const editForm = document.getElementById('editStakeholderForm');
    const deleteForm = document.getElementById('deleteStakeholderForm');
    const deleteBtn = document.getElementById('deleteStakeholderBtn');

    if (deleteBtn) {
        deleteBtn.addEventListener('click', function () {
            if (confirm('Tem certeza que deseja excluir este stakeholder?')) {
                deleteForm.submit();
            }
        });
    }

    window.openEditModal = function (stakeholder) {
        const baseUrl = window.projectRoutes.stakeholders;

        editForm.action = `${baseUrl}/${stakeholder.initiating_stakeholder_id}`;
        deleteForm.action = `${baseUrl}/${stakeholder.initiating_stakeholder_id}`;

        document.getElementById('edit_contact_id').value = stakeholder.contact_id;
        document.getElementById('edit_contact_name').value = stakeholder.contact ? stakeholder.contact.full_name : '';
        document.getElementById('edit_stakeholder_responsibility').value = stakeholder.stakeholder_responsibility;
        document.getElementById('edit_stakeholder_power').value = stakeholder.stakeholder_power;
        document.getElementById('edit_stakeholder_interest').value = stakeholder.stakeholder_interest;
        document.getElementById('edit_stakeholder_strategy').value = stakeholder.stakeholder_strategy;

        if (editModalInstance) editModalInstance.show();
    }

    window.openNewWbsItemModal = function (parentId) {
        const inputParent = document.getElementById('wbs_parent_id_input');
        if (inputParent) {
            inputParent.value = parentId;
        } else {
            console.error('Input wbs_parent_id_input não encontrado!');
        }

        const myModal = new bootstrap.Modal(document.getElementById('createWbsModal'));
        myModal.show();
    }

    window.openNewActivityModal = function (wbsItemId) {
        const form = document.getElementById('createActivityForm');

        if (form) {
            form.action = window.projectRoutes.activityStore.replace('__ID__', wbsItemId);
            const myModal = new bootstrap.Modal(document.getElementById('createActivityModal'));
            myModal.show();
        } else {
            console.error('Formulário createActivityForm não encontrado!');
        }
    }

    window.openEditActivityModal = function (task) {
        let updateUrl = window.projectRoutes.activityUpdate.replace('__ID__', task.task_id);

        const form = document.getElementById('editActivityForm');
        form.action = updateUrl;

        document.getElementById('edit_task_name').value = task.task_name;
        document.getElementById('edit_task_duration').value = task.task_duration;
        document.getElementById('edit_task_percent_complete').value = task.task_percent_complete;

        if (task.task_start_date) {
            document.getElementById('edit_task_start_date').value = task.task_start_date.split('T')[0];
        } else {
            document.getElementById('edit_task_start_date').value = '';
        }

        if (task.task_end_date) {
            document.getElementById('edit_task_end_date').value = task.task_end_date.split('T')[0];
        } else {
            document.getElementById('edit_task_end_date').value = '';
        }

        const myModal = new bootstrap.Modal(document.getElementById('editActivityModal'));
        myModal.show();
    }

    let deleteActivityModalInstance = null;
    const deleteActivityModalEl = document.getElementById('deleteConfirmationModal');

    if (deleteActivityModalEl) {
        deleteActivityModalInstance = new bootstrap.Modal(deleteActivityModalEl);

        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function () {
                document.getElementById('deleteActivityForm').submit();
            });
        }
    }

    window.deleteActivity = function (taskId) {
        const form = document.getElementById('deleteActivityForm');
        form.action = window.projectRoutes.activityDestroy.replace('__ID__', taskId);

        if (deleteActivityModalInstance) {
            deleteActivityModalInstance.show();
        } else {
            console.error('Modal de exclusão não carregado.');
        }
    }

    let deleteWbsModalInstance = null;
    const deleteWbsModalEl = document.getElementById('deleteWbsModal');

    if (deleteWbsModalEl) {
        deleteWbsModalInstance = new bootstrap.Modal(deleteWbsModalEl);
    }

    window.openDeleteWbsModal = function (wbsId) {
        const form = document.getElementById('deleteWbsForm');
        form.action = window.projectRoutes.wbsDestroy.replace('__ID__', wbsId);

        if (deleteWbsModalInstance) {
            deleteWbsModalInstance.show();
        } else {
            console.error('Modal de exclusão EAP não encontrado.');
        }
    }
});
