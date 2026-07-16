@php /** @var \App\Models\Complaint $complaint */ @endphp
<div class="modal fade" id="closeModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="closeModalLabel{{ $complaint->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('complaints.update', $complaint) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="closeModalLabel{{ $complaint->id }}">
                        Close Ticket {{ $complaint->reference_number }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="close_status_id{{ $complaint->id }}" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                       <select name="status_id" id="close_status_id{{ $complaint->id }}" class="form-select" required>
                            @if($closeStatus)
                                <option value="{{ $closeStatus->id }}">{{ $closeStatus->display_name ?? 'Closed' }}</option>
                            @else
                                <option value="" disabled selected>Status ID not found in database</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="close_description{{ $complaint->id }}" class="form-label fw-bold">Remarks (Optional)</label>
                        <textarea name="description" id="close_description{{ $complaint->id }}" class="form-control" rows="3" placeholder="Enter closing remarks..."></textarea>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">Reassign</a>
                    <button type="submit" class="btn btn-success">Close Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>