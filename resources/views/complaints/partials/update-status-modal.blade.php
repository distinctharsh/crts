@php
    $isAssignedToUser = auth()->check() && $complaint->assigned_to == auth()->user()->id;
@endphp

@if($isAssignedToUser && !$complaint->isCompleted() && !$complaint->isClosed())
<div class="modal fade" id="updateStatusModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="updateStatusModalLabel{{ $complaint->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header bg-primary text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title" id="updateStatusModalLabel{{ $complaint->id }}">
                    <i class="bi bi-pencil-square me-2"></i>Update Ticket #{{ $complaint->reference_number }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('complaints.comment', $complaint) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="status_id_{{ $complaint->id }}" class="form-label fw-bold">Update Status <span class="text-danger">*</span></label>
                        <select name="status_id" id="status_id_{{ $complaint->id }}" class="form-select" required>
                            <option value="">Select status</option>
                            @php
                                $statusList = \App\Models\Status::where('visible_to_user', true)->ordered()->get();
                            @endphp
                            @foreach($statusList as $status)
                                <option value="{{ $status->id }}" {{ $complaint->status_id == $status->id ? 'selected' : '' }}>
                                    {{ $status->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment_{{ $complaint->id }}" class="form-label fw-bold">Add Comment / Remark</label>
                        <textarea name="comment" id="comment_{{ $complaint->id }}" class="form-control" rows="3" placeholder="Enter remarks or updates regarding this ticket..."></textarea>
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
@endif