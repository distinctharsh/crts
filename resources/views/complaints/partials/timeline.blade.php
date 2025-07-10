<style>
.timeline-vertical {
  position: relative;
  margin: 0;
  padding: 0;
  max-height: 350px;
  overflow-y: auto;
}
.timeline-vertical-line {
  position: absolute;
  left: 24px;
  top: 0;
  width: 4px;
  height: 100vh;
  background: #e0e0e0;
  border-radius: 2px;
  z-index: 0;
}
.timeline-vertical-item {
  position: relative;
  margin-left: 60px;
  margin-bottom: 28px;
  padding-left: 0;
}
.timeline-vertical-dot {
  position: absolute;
  left: -44px;
  top: 12px;
  width: 20px;
  height: 20px;
  background: #fff;
  border: 4px solid #0d6efd;
  border-radius: 50%;
  z-index: 2;
}
.timeline-vertical-item.pending_with_user .timeline-vertical-dot,
.timeline-vertical-item.pending_with_vendor .timeline-vertical-dot {
  border-color: #dc3545;
}
.timeline-vertical-content {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  padding: 12px 18px;
  border-left: 4px solid #0d6efd;
}
.timeline-vertical-item.pending_with_user .timeline-vertical-content,
.timeline-vertical-item.pending_with_vendor .timeline-vertical-content {
  border-left-color: #dc3545;
}
</style>
<div class="card shadow-sm mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Status History</h5>
  </div>
  <div class="card-body position-relative" style="max-height: 350px; overflow-y: auto;">
    <div class="timeline-vertical">
      <div class="timeline-vertical-line"></div>
      @foreach($complaint->actions as $action)
        @php
          $assignedUser = $action->assigned_to ? \App\Models\User::find($action->assigned_to) : null;
          $statusName = $action->status ? $action->status->name : null;
        @endphp
        <div class="timeline-vertical-item {{ $statusName }}">
          <span class="timeline-vertical-dot"></span>
          <div class="timeline-vertical-content">
            {{-- Status badge --}}
            <div class="mb-1">
              @php
                $colorClass = $action->status && $action->status->color ? 'bg-' . $action->status->color : 'bg-secondary';
              @endphp
              <span class="badge {{ $colorClass }}">
                {{ ucfirst($action->status->display_name ?? $statusName) }}
              </span>
            </div>
            {{-- Assigned/Reverted To (if applicable) --}}
            @if(in_array($statusName, ['assigned', 'reassigned', 'reverted']) && $action->assigned_to)
              <div class="text-muted small mb-1">
                <span class="fw-semibold">{{ $statusName === 'reverted' ? 'Reverted To:' : 'Assigned To:' }}</span>
                {{ $assignedUser ? $assignedUser->full_name : 'Unknown User' }}
                @if($assignedUser && $assignedUser->role)
                  ({{ strtoupper($assignedUser->role->slug) }})
                @endif
              </div>
            @endif
            {{-- Remarks --}}
            @if($action->description)
              <div class="mb-1 p-2 bg-light border rounded fst-italic">
                {{ $action->description }}
              </div>
            @endif
            {{-- User (only for non-assigned/reverted statuses) --}}
            @if(!in_array($statusName, ['assigned', 'reassigned', 'reverted']))
              <div class="mb-1 fw-bold d-flex align-items-center">
                <i class="bi bi-person-circle me-2"></i>
                {{ $action->user && $action->user_id != 0 ? $action->user->full_name : 'Guest User' }}
              </div>
            @endif
            {{-- Assigned/Reverted By (if applicable) --}}
            @if(in_array($statusName, ['assigned', 'reassigned', 'reverted']))
              <div class="text-muted small mb-1">
                <span class="fw-semibold">{{ $statusName === 'reverted' ? 'Reverted By:' : 'Assigned By:' }}</span>
                {{ $action->user && $action->user_id != 0 ? $action->user->full_name : 'Guest User' }}
              </div>
            @endif
            {{-- Time --}}
            <div class="text-muted small mb-1">
              <i class="bi bi-clock me-1"></i> {{ $action->created_at->format('M d, Y h:i A') }}
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>