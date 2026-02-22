<div class="modal fade" id="statusMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header text-white" id="statusModalHeader">
                <h5 class="modal-title h6 fw-bold" id="statusModalTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4" id="statusModalBody">
            </div>
            <div class="modal-footer bg-light justify-content-center border-0">
                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    let globalStatusModal = null;

    document.addEventListener('DOMContentLoaded', function() {
        const modalEl = document.getElementById('statusMessageModal');
        if (modalEl) {
            globalStatusModal = new bootstrap.Modal(modalEl);
        }
    });

    /**
     * @param {string} title
     * @param {string} message
     * @param {string} type
     */
    function showMessage(title, message, type = 'success') {
        const header = document.getElementById('statusModalHeader');
        const titleEl = document.getElementById('statusModalTitle');
        const bodyEl = document.getElementById('statusModalBody');

        if (!globalStatusModal) {
            alert(title + ": " + message);
            return;
        }

        titleEl.innerText = title;

        if (type === 'success') {
            header.className = 'modal-header bg-success text-white';
            bodyEl.innerHTML = `
                <i class="bi bi-check-circle-fill text-success display-4 mb-3 d-block"></i>
                <span class="fw-bold text-dark">${message}</span>
            `;
        } else {
            header.className = 'modal-header bg-danger text-white';
            bodyEl.innerHTML = `
                <i class="bi bi-x-circle-fill text-danger display-4 mb-3 d-block"></i>
                <span class="fw-bold text-dark">${message}</span>
            `;
        }

        globalStatusModal.show();
    }
</script>
