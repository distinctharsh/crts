@php /** @var \App\Models\Complaint $complaint */ @endphp
<div class="modal fade" id="revertModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="revertModalLabel{{ $complaint->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('complaints.revert', $complaint) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="revertModalLabel{{ $complaint->id }}">Revert to Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to_revert{{ $complaint->id }}" class="form-label">Revert to Manager</label>
                        <select class="form-select" name="assigned_to" id="assigned_to_revert{{ $complaint->id }}" required>
                            <option value="">Select Manager</option>
                            @foreach($managers as $manager)
                                <option value="{{ $manager->id }}" @if($manager->id == $complaint->assigned_by) selected @endif>
                                    {{ $manager->full_name }}@if($manager->id == $complaint->assigned_by) (Original Assigner)@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description_revert{{ $complaint->id }}" class="form-label">Reason for Reverting</label>
                        <textarea class="form-control" name="description" id="description_revert{{ $complaint->id }}" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Revert</button>
                </div>
            </form>
        </div>
    </div>
</div> 