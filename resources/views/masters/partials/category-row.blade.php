@foreach($categories as $category)
    <tr class="{{ $category->trashed() ? 'table-light text-muted opacity-75' : '' }}">
        <td class="ps-3 py-1" style="font-size: 0.85rem;">
            @if($level > 0)
                {!! str_repeat('&nbsp;&nbsp;', $level) !!} ↳
            @endif
            {{ $category->name }}
            @if($category->trashed())
                <span class="badge bg-secondary ms-1" style="font-size: 0.65rem;">Deleted</span>
            @endif
        </td>
        <td class="ps-3 py-1" style="font-size: 0.85rem;">{{ $category->short_form ?? '-' }}</td>
        <td class="ps-3 py-1" style="font-size: 0.85rem;">
            {{ $category->parent ? $category->parent->name : 'Main Category' }}
        </td>

        <td class="ps-3 py-1">
            @if($category->users->isNotEmpty())
                @php
                    $users = $category->users;
                    $displayCount = min(2, $users->count());
                    $remainingCount = $users->count() - $displayCount;
                    $allUserNames = $users->pluck('full_name')->implode(', ');
                @endphp
                <div class="d-flex align-items-center gap-1 flex-nowrap">
                    @for($i = 0; $i < $displayCount; $i++)
                        <span class="badge bg-info text-dark" style="font-size: 0.65rem; white-space: nowrap; padding: 0.2rem 0.4rem;" data-bs-toggle="tooltip" title="{{ $allUserNames }}">
                            <i class="fas fa-user me-1" style="font-size: 0.55rem;"></i>{{ $users[$i]->full_name ?? $users[$i]->name }}
                        </span>
                    @endfor
                    @if($remainingCount > 0)
                        <span class="badge bg-secondary text-dark" style="font-size: 0.65rem; padding: 0.2rem 0.4rem;" data-bs-toggle="tooltip" title="{{ $allUserNames }}">
                            +{{ $remainingCount }}
                        </span>
                    @endif
                </div>
            @else
                <span class="text-muted" style="font-size: 0.75rem;">No User Assigned</span>
            @endif
        </td>

        <td class="ps-3 py-1">
            <span class="badge bg-{{ $category->send_email ? 'success' : 'danger' }}" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                {{ $category->send_email ? 'Yes' : 'No' }}
            </span>
        </td>
        <td class="text-end pe-3 py-1">
            @if($category->trashed())
                @if($category->parent_id && $category->parent()->withTrashed()->first()->trashed())
                    <span class="text-muted" style="font-size: 0.75rem;">Parent is deleted</span>
                @else
                    <form action="{{ route('masters.verticals.restore', $category->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;" data-bs-toggle="tooltip" title="Restore Category">
                            <i class="fas fa-undo"></i>
                        </button>
                    </form>
                @endif
            @else
                {{-- Normal Edit/Delete Buttons --}}
                <div class="d-inline-flex gap-1">
                    <button class="btn btn-outline-warning btn-sm text-dark" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;" data-bs-toggle="tooltip" title="Edit" onclick="openCategoryModal('edit', {{ $category->id }}, '{{ $category->name }}', '{{ $category->short_form ?? '' }}', '{{ $category->parent_id ?? '' }}', {{ $category->send_email ? 'true' : 'false' }}, {{ json_encode($category->users->pluck('id')->toArray()) }})">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;" data-bs-toggle="tooltip" title="Delete" onclick="$('#deleteVerticalModal{{ $category->id }}').modal('show')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            @endif
        </td>
    </tr>

    @if(!$category->trashed())
    <div class="modal fade" id="deleteVerticalModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form action="{{ route('masters.verticals.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header bg-danger text-white rounded-top-4">
                        <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <p class="mb-0 text-dark">Are you sure you want to delete <strong>{{ $category->name }}</strong>? Iske andar ke sabhi sub-categories bhi delete ho jayenge.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Recursive inclusion for sub-categories --}}
    @if($category->children()->withTrashed()->count() > 0)
        @include('masters.partials.category-row', [
            'categories' => $category->children()->withTrashed()->orderBy('name')->get(),
            'level' => $level + 1,
            'assignableUsers' => $assignableUsers
        ])
    @endif
@endforeach