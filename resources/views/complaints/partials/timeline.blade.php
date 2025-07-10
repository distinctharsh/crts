<div class="card shadow-sm mb-4">
  <div class="card-header bg-light">
    <h5 class="mb-0">Status History</h5>
  </div>
  <div class="card-body" style="max-height: 350px; overflow-y: auto;">
    <ul class="timeline list-unstyled mb-0">
      @foreach($complaint->actions as $action)
      @php
        $assignedUser = $action->assigned_to ? \App\Models\User::find($action->assigned_to) : null;
        $statusName = $action->status ? $action->status->name : null;
      @endphp
      <li class="mb-3 position-relative ps-4">
        @php
          // Circle color logic
          $circleColor = 'primary';
          if ($statusName === 'resolved') {
              $circleColor = 'success';
          } elseif ($statusName === 'reverted') {
              $circleColor = 'warning';
          } elseif ($statusName === 'pending_with_user' || $statusName === 'pending_with_vendor') {
              $circleColor = 'danger'; // red
          }
          // Status box color logic
          $statusBoxClass = '';
          if ($statusName === 'pending_with_user' || $statusName === 'pending_with_vendor') {
              $statusBoxClass = 'alert alert-danger mb-1 py-1 px-2 d-inline-block';
          }
        @endphp
        <span class="position-absolute top-0 start-0 translate-middle p-2 bg-{{ $circleColor }} border border-light rounded-circle" style="margin-top: 11px;"></span>
        <div class="ms-3">
          @if(in_array($statusName, ['assigned', 'reassigned', 'reverted']) && $action->assigned_to)
            <div class="fw-semibold mb-1">
              <span class="badge bg-primary">Assigned to {{ $assignedUser && $assignedUser->role ? strtoupper($assignedUser->role->slug) : 'User' }}</span>
            </div>
            <div class="mb-1 fs-6 fw-bold d-flex align-items-center">
              <i class="bi bi-person-circle me-2"></i> {{ $assignedUser ? $assignedUser->full_name : 'Unknown User' }}
            </div>
            @if($action->description)
              <div class="mb-1 p-2 bg-light border rounded fst-italic">
                {{ $action->description }}
              </div>
            @endif
            <div class="text-muted small mb-1 d-flex align-items-center">
              <i class="bi bi-person me-1"></i>
              <span class="fw-semibold me-1">{{ $statusName === 'reverted' ? 'Reverted By:' : 'Assigned By:' }}</span>
              {{ $action->user && $action->user_id != 0 ? $action->user->full_name : 'Guest User' }}
            </div>
            <div class="text-muted small" style="font-size: 0.85em;">
              <i class="bi bi-clock me-1"></i> {{ $action->created_at->format('M d, Y h:i A') }}
            </div>
          @elseif(in_array($statusName, ['pending_with_user', 'pending_with_vendor']))
            <div class="mb-1">
              <span class="badge bg-danger bg-opacity-75 text-white fs-6 px-3 py-2 rounded-pill">
                {{ ucfirst($action->status->display_name ?? $statusName) }}
              </span>
            </div>
            @if($action->description)
              <div class="mb-1 p-2 bg-light border rounded fst-italic">
                {{ $action->description }}
              </div>
            @endif
            <div class="text-muted small mb-1 d-flex align-items-center">
              <i class="bi bi-person me-1"></i>
              {{ $action->user && $action->user_id != 0 ? $action->user->full_name : 'Guest User' }}
            </div>
            <div class="text-muted small" style="font-size: 0.85em;">
              <i class="bi bi-clock me-1"></i> {{ $action->created_at->format('M d, Y h:i A') }}
            </div>
          @else
            @if($statusBoxClass)
              <span class="{{ $statusBoxClass }}">{{ ucfirst($action->status->display_name ?? $statusName) }}</span>
            @else
              <h6 class="mb-1">{{ ucfirst($action->status->display_name ?? $statusName) }}</h6>
            @endif
            @if($action->description)
              <div class="mb-1">{{ $action->description }}</div>
            @endif
            <div class="text-muted small mb-1">
              <i class="bi bi-person"></i>
              @if ($action->user && $action->user_id != 0)
                {{ $action->user->full_name }}
              @else
                Guest User
              @endif
              &nbsp;|&nbsp;
              <i class="bi bi-clock"></i> {{ $action->created_at->format('M d, Y h:i A') }}
            </div>
          @endif
          @if($statusName === 'resolved' && $action->resolution)
            <div class="mt-2">
              <strong>Resolution:</strong>
              <div class="alert alert-success mb-0">{{ $action->resolution }}</div>
            </div>
          @endif
        </div>
      </li>
      @endforeach
    </ul>
  </div>
</div>