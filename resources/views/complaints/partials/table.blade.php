@php
// $complaints: the list of complaints to show
// $tableId: optional, for DataTable initialization
@endphp
<table id="{{ $tableId ?? 'complaintsTable' }}" class="table table-hover table-bordered table-striped align-middle w-100">
    <thead class="table-primary">
        <tr>
            <th class="no-sort">S.No.</th>
            <th>Reference</th>
            <th>User</th>
            <th>Section</th>
            <th>Network</th>
            <th>Category</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Assigned To</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($complaints as $complaint)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $complaint->reference_number }}</td>
            <td>{{ $complaint->user_name }}</td>
            <td>{{ $complaint->section->name }}</td>
            <td>{{ $complaint->networkType->name ?? 'N/A' }}</td>
            <td>{{ $complaint->verticals->pluck('name')->map(fn($name) => ucfirst($name))->implode(', ') ?? 'N/A' }} -
                @if($complaint->verticals->first() && $complaint->verticals->first()->pivot->sub_category_id)
                    @php
                        $subCategory = \App\Models\SubCategory::find($complaint->verticals->first()->pivot->sub_category_id);
                    @endphp
                    <span class="badge bg-light text-dark border border-secondary">
                        {{ $subCategory->sub_category_name ?? $subCategory->name ?? 'N/A' }}
                    </span>
                @else
                    <span class="text-muted">N/A</span>
                @endif
            </td>
            <td>
                <span class="badge bg-{{ $complaint->status_color }}">
                    {{ $complaint->status->display_name ?? 'Unknown' }}
                </span>
            </td>
            <td>
                <span class="badge bg-{{ $complaint->priority_color }}">
                    {{ ucfirst($complaint->priority) }}
                </span>
            </td>
            <td>{{ $complaint->assignedTo?->full_name ?? 'Not Assigned' }}</td>
            <td>{{ $complaint->description }}</td>
            <td>
                <div class="btn-group">
                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-sm btn-info me-1">View</a>
                    @auth
                    @if((auth()->user()->isManager() || auth()->user()->isVM()) && $complaint->status->name != 'closed' && $complaint->status->name != 'completed')
                    <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-sm btn-primary me-1">Edit</a>
                    @endif
                    @endauth
                    
                    @auth
                    @if(auth()->user()->isManager())
                    @if($complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                        @if($complaint->assigned_to)
                        Reassign
                        @else
                        Assign
                        @endif
                    </button>
                    @endif
                    @elseif(auth()->user()->isVM())
                    @if(($complaint->isUnassigned() || $complaint->assigned_to === auth()->user()->id) && $complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                        Assign
                    </button>
                    @if($complaint->assigned_to === auth()->user()->id && $complaint->status->name != 'completed' && $complaint->status->name != 'closed')
                    <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#revertModal{{ $complaint->id }}">
                        Revert
                    </button>
                    @endif
                    @endif
                    @elseif(auth()->user()->isNFO())
                    @if($complaint->assigned_to === auth()->user()->id && !$complaint->isCompleted() && !$complaint->isClosed())
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $complaint->id }}">
                        Reassign
                    </button>
                    @endif
                    @endif
                    @endauth
                </div>
                {{-- Modals for assign/resolve/revert can be included here if needed --}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table> 