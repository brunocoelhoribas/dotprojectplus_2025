document.addEventListener('DOMContentLoaded', function() {
    function refreshStakeholderTable() {
        const container = document.getElementById('stakeholder-list-container');
        if (!container) {
            console.error('Container #stakeholder-list-container não encontrado!');
            return;
        }

        container.style.opacity = '0.5';

        const url = new URL(window.location.href);
        url.searchParams.set('update_time', new Date().getTime());

        fetch(url.toString())
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newContent = doc.getElementById('stakeholder-list-container');

                if (newContent) {
                    container.innerHTML = newContent.innerHTML;
                } else {
                    console.error('Não foi possível encontrar o container na resposta do servidor.');
                }
            })
            .catch(err => {
                console.error('Erro ao atualizar:', err);
            })
            .finally(() => {
                container.style.opacity = '1';
            });
    }

    function handleAjaxSubmit(form) {
        const url = form.action;
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || form.querySelector('input[name="_token"]')?.value;
        const methodInput = form.querySelector('input[name="_method"]');
        const method = methodInput ? methodInput.value : form.method;
        const btn = form.querySelector('button[type="submit"]') || document.getElementById('confirmDeleteStakeholderBtn');
        let originalText = '';

        if (btn) {
            originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Processando...';
        }

        fetch(url, {
            method: method.toUpperCase(),
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Erro ao processar.');
                return data;
            })
            .then(data => {
                if (data.success) {
                    document.querySelectorAll('.modal.show').forEach(el => {
                        const modal = bootstrap.Modal.getInstance(el);
                        if (modal) modal.hide();
                    });

                    form.reset();
                    refreshStakeholderTable();

                } else {
                    console.log('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Ocorreu um erro: ' + error.message);
            })
            .finally(() => {
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = originalText;
                }
            });
    }

    const createModalEl = document.getElementById('createStakeholderModal');
    if (createModalEl) {
        const createForm = createModalEl.querySelector('form');
        if (createForm) {
            const newCreateForm = createForm.cloneNode(true);
            createForm.parentNode.replaceChild(newCreateForm, createForm);

            newCreateForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAjaxSubmit(this);
            });
        }
    }

    const editModalEl = document.getElementById('editStakeholderModal');
    let editModalInstance = null;

    if (editModalEl) {
        editModalInstance = new bootstrap.Modal(editModalEl);
        const editForm = document.getElementById('editStakeholderForm');

        if (editForm) {
            const newEditForm = editForm.cloneNode(true);
            editForm.parentNode.replaceChild(newEditForm, editForm);

            newEditForm.addEventListener('submit', function(e) {
                e.preventDefault();
                handleAjaxSubmit(this);
            });
        }
    }

    const deleteBtn = document.getElementById('deleteStakeholderBtn');
    const deleteForm = document.getElementById('deleteStakeholderForm');
    const confirmDeleteBtn = document.getElementById('confirmDeleteStakeholderBtn');

    const confirmModalEl = document.getElementById('deleteStakeholderConfirmationModal');
    let confirmModalInstance = null;
    if (confirmModalEl) {
        confirmModalInstance = new bootstrap.Modal(confirmModalEl);
    }

    window.openDeleteModal = function(stakeholder) {
        const baseUrl = window.projectRoutes.stakeholders;

        if (deleteForm) {
            deleteForm.action = `${baseUrl}/${stakeholder.initiating_stakeholder_id}`;
        } else {
            console.error('Formulário deleteStakeholderForm não encontrado na página.');
            return;
        }

        if (confirmModalInstance) {
            confirmModalInstance.show();
        }
    };

    if (deleteBtn && confirmModalInstance) {
        const newDeleteBtn = deleteBtn.cloneNode(true);
        deleteBtn.parentNode.replaceChild(newDeleteBtn, deleteBtn);

        newDeleteBtn.addEventListener('click', function (e) {
            e.preventDefault();

            if (editModalInstance) editModalInstance.hide();
            confirmModalInstance.show();
        });
    }

    if (confirmDeleteBtn && deleteForm) {
        const newConfirmBtn = confirmDeleteBtn.cloneNode(true);
        confirmDeleteBtn.parentNode.replaceChild(newConfirmBtn, confirmDeleteBtn);

        newConfirmBtn.addEventListener('click', function() {
            handleAjaxSubmit(deleteForm);
        });
    }

    window.openEditModal = function (stakeholder) {
        const baseUrl = window.projectRoutes.stakeholders;

        const editForm = document.getElementById('editStakeholderForm');
        const deleteForm = document.getElementById('deleteStakeholderForm');

        editForm.action = `${baseUrl}/${stakeholder.initiating_stakeholder_id}`;
        if(deleteForm) deleteForm.action = `${baseUrl}/${stakeholder.initiating_stakeholder_id}`;

        if (document.getElementById('edit_initiating_id')) {
            document.getElementById('edit_initiating_id').value = stakeholder.initiating_id;
        }

        if(document.getElementById('edit_contact_id')) document.getElementById('edit_contact_id').value = stakeholder.contact_id;
        if(document.getElementById('edit_contact_name')) document.getElementById('edit_contact_name').value = stakeholder.contact?.full_name || '';
        if(document.getElementById('edit_stakeholder_responsibility')) document.getElementById('edit_stakeholder_responsibility').value = stakeholder.stakeholder_responsibility || '';
        if(document.getElementById('edit_stakeholder_power')) document.getElementById('edit_stakeholder_power').value = stakeholder.stakeholder_power || '';
        if(document.getElementById('edit_stakeholder_interest')) document.getElementById('edit_stakeholder_interest').value = stakeholder.stakeholder_interest || '';
        if(document.getElementById('edit_stakeholder_strategy')) document.getElementById('edit_stakeholder_strategy').value = stakeholder.stakeholder_strategy || '';

        if (editModalInstance) editModalInstance.show();
    };

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
