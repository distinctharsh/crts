@php /** @var \App\Models\Complaint $complaint */ @endphp
<div class="modal fade assign-modal-wrapper" id="assignModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $complaint->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="assign-ticket-form" data-complaint-id="{{ $complaint->id }}" action="{{ route('complaints.assign', $complaint) }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="assignModalLabel{{ $complaint->id }}">Assign Ticket {{ $complaint->reference_number }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to{{ $complaint->id }}" class="form-label">Assign To <span class="text-danger">*</span></label>
                        <select class="form-select assign-tom-select" name="assigned_to" id="assigned_to{{ $complaint->id }}" required>
                            <option value="">Select User</option>
                            @foreach($complaint->assignableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $complaint->id }}" class="form-label">Remarks</label>
                        <textarea class="form-control" name="description" id="description{{ $complaint->id }}" rows="3"></textarea>
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

<style>
.ts-dropdown .ts-dropdown-content {
    max-height: 160px !important;
    overflow-y: auto !important;
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.ts-dropdown .ts-dropdown-content::-webkit-scrollbar {
    display: none;
}

.ts-dropdown {
    z-index: 1060 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('assignModal{{ $complaint->id }}');
    const selectEl = document.getElementById('assigned_to{{ $complaint->id }}');

    if (modalEl && selectEl) {
        modalEl.addEventListener('shown.bs.modal', () => {
            if (!selectEl.tomselect) {
                new TomSelect(selectEl, {
                    create: false,
                    placeholder: 'Type to search user...',
                    sortField: [],
                    dropdownParent: 'body'
                });
            }
        });
    }
});
</script>