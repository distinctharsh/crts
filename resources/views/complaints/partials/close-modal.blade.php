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
                    @if($closeStatus)
                        <input type="hidden" name="status_id" value="{{ $closeStatus->id }}">
                    @endif

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