<!-- GLOBAL ASSIGN MODAL -->
<div class="modal fade assign-modal-wrapper" id="globalAssignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="globalAssignForm" action="" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="globalAssignModalLabel">Assign Ticket</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="global_assigned_to" class="form-label">Assign To <span class="text-danger">*</span></label>
                        <select class="form-select" name="assigned_to" id="global_assigned_to" required>
                            <option value="">Select User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="global_assign_description" class="form-label">Remarks</label>
                        <textarea class="form-control" name="description" id="global_assign_description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- GLOBAL CLOSE MODAL -->
<div class="modal fade" id="globalCloseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="globalCloseForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="globalCloseModalLabel">Close Ticket</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if(isset($closeStatus) && $closeStatus)
                        <input type="hidden" name="status_id" value="{{ $closeStatus->id }}">
                    @endif

                    <div class="mb-3">
                        <label for="global_close_description" class="form-label fw-bold">Remarks (Optional)</label>
                        <textarea name="description" id="global_close_description" class="form-control" rows="3" placeholder="Enter closing remarks..."></textarea>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-primary btn-reassign-from-close">Reassign</button>
                    <button type="submit" class="btn btn-success">Close Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- GLOBAL UPDATE STATUS MODAL -->
<div class="modal fade" id="globalUpdateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title" id="globalUpdateStatusModalLabel">
                    <i class="bi bi-pencil-square me-2"></i>Update Ticket Status
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="globalUpdateStatusForm" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="global_status_id" class="form-label fw-bold">Update Status <span class="text-danger">*</span></label>
                        <select name="status_id" id="global_status_id" class="form-select" required>
                            <option value="">Select status</option>
                            @php
                                $statusList = \App\Models\Status::where('visible_to_user', true)->ordered()->get();
                            @endphp
                            @foreach($statusList as $status)
                                <option value="{{ $status->id }}">
                                    {{ $status->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="global_status_comment" class="form-label fw-bold">Add Comment / Remark</label>
                        <textarea name="comment" id="global_status_comment" class="form-control" rows="3" placeholder="Enter remarks or updates regarding this ticket..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- GLOBAL CUSTOM TIME FILTER MODAL -->
<div class="modal fade" id="customTimeFilterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Custom Time Range</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="customTimeInput" class="form-label">Enter duration</label>
                    <input type="text" id="customTimeInput" class="form-control" placeholder="e.g., 5h (Hours), 10d (Days), 3w (Weeks), 2m (Months), 1y (Years)">
                    <small class="text-muted">Use numbers followed by h, d, w, m, or y</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="applyCustomTime" class="btn btn-primary">Apply</button>
            </div>
        </div>
    </div>
</div>


<style>
.ts-dropdown .ts-dropdown-content {
    max-height: 160px !important;
    overflow-y: auto !important;
}
.ts-dropdown { z-index: 1060 !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let globalAssignTomSelect = null;
    const assignSelectEl = document.getElementById('global_assigned_to');

    // 1. Assign Modal Click Handler
    $(document).on('click', '.btn-open-assign-modal', function() {
        let complaintId = $(this).data('id');
        let refNumber = $(this).data('ref');
        let users = $(this).data('users') || [];

        $('#globalAssignForm').attr('action', '/complaints/' + complaintId + '/assign');
        $('#globalAssignModalLabel').text('Assign Ticket #' + refNumber);
        $('#global_assign_description').val('');

        if (globalAssignTomSelect) {
            globalAssignTomSelect.destroy();
        }

        $(assignSelectEl).empty().append('<option value="">Select User</option>');
        users.forEach(function(u) {
            $(assignSelectEl).append(new Option(u.full_name, u.id));
        });

        $('#globalAssignModal').modal('show');
    });

    $('#globalAssignModal').on('shown.bs.modal', function() {
        if (!assignSelectEl.tomselect) {
            globalAssignTomSelect = new TomSelect(assignSelectEl, {
                create: false,
                placeholder: 'Type to search user...',
                dropdownParent: 'body'
            });
        }
    });

    // 2. Close Modal Click Handler
    let activeCloseComplaintId = null;
    let activeCloseRefNumber = null;
    let activeCloseUsers = [];

    $(document).on('click', '.btn-open-close-modal', function() {
        activeCloseComplaintId = $(this).data('id');
        activeCloseRefNumber = $(this).data('ref');
        activeCloseUsers = $(this).data('users') || [];

        $('#globalCloseForm').attr('action', '/complaints/' + activeCloseComplaintId);
        $('#globalCloseModalLabel').text('Close Ticket #' + activeCloseRefNumber);
        $('#global_close_description').val('');

        $('#globalCloseModal').modal('show');
    });

    $(document).on('click', '.btn-reassign-from-close', function() {
        $('#globalCloseModal').modal('hide');
        setTimeout(function() {
            $('.btn-open-assign-modal[data-id="' + activeCloseComplaintId + '"]').trigger('click');
        }, 400);
    });

    // 3. Status Update Modal Click Handler
    $(document).on('click', '.btn-open-status-modal', function() {
        let complaintId = $(this).data('id');
        let refNumber = $(this).data('ref');
        let statusId = $(this).data('status-id');

        $('#globalUpdateStatusForm').attr('action', '/complaints/' + complaintId + '/comment');
        $('#globalUpdateStatusModalLabel').html('<i class="bi bi-pencil-square me-2"></i>Update Ticket #' + refNumber);
        $('#global_status_id').val(statusId);
        $('#global_status_comment').val('');

        $('#globalUpdateStatusModal').modal('show');
    });
});
</script>