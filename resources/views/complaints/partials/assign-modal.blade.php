@php /** @var \App\Models\Complaint $complaint */ @endphp
<div class="modal fade" id="assignModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $complaint->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('complaints.assign', $complaint) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="assignModalLabel{{ $complaint->id }}">Assign Ticket {{ $complaint->reference_number }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to{{ $complaint->id }}" class="form-label">Assign To</label>
                        <select class="form-select" name="assigned_to" id="assigned_to{{ $complaint->id }}" required>
                            <option value="">Select User</option>
                            @foreach($complaint->assignableUsers as $user)
                            <option value="{{ $user->id }}">{{ $user->full_name }} ({{ strtoupper($user->role->name) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description{{ $complaint->id }}" class="form-label">Remarks</label>
                        <textarea class="form-control" name="description" id="description{{ $complaint->id }}" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div> 