@foreach($categories as $category)
    <tr class="{{ $category->trashed() ? 'table-light text-muted opacity-75' : '' }}">
        <td class="ps-4">
            {{-- Level ke hisab se indentation/arrow lagane ke liye --}}
            @if($level > 0)
                {!! str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) !!} ↳ 
            @endif
            {{ $category->name }}
            @if($category->trashed())
                <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">Deleted</span>
            @endif
        </td>
        <td class="ps-4">{{ $category->short_form ?? '-' }}</td>
        <td class="ps-4">
            {{ $category->parent ? $category->parent->name : 'Main Category' }}
        </td>
        
        <td class="ps-4">
            @if($category->users->isNotEmpty())
                @foreach($category->users as $assignedUser)
                    <span class="badge bg-info text-dark mb-1">
                        <i class="fas fa-user me-1"></i>{{ $assignedUser->full_name ?? $assignedUser->name }}
                    </span>
                @endforeach
            @else
                <span class="text-muted small">No User Assigned</span>
            @endif
        </td>

        <td class="ps-4">
            <span class="badge bg-{{ $category->send_email ? 'success' : 'danger' }}">
                {{ $category->send_email ? 'Yes' : 'No' }}
            </span>
        </td>
        <td class="text-end pe-4">
            @if($category->trashed())
                {{-- CHECK: Agar iska parent khud deleted (trashed) hai, toh Restore button NAHI dikhega --}}
                @if($category->parent_id && $category->parent()->withTrashed()->first()->trashed())
                    <span class="text-muted small italic">Parent is deleted</span>
                @else
                    <form action="{{ route('masters.verticals.restore', $category->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Restore Category">
                            <i class="fas fa-undo me-1"></i> Restore
                        </button>
                    </form>
                @endif
            @else
                {{-- Normal Edit/Delete Buttons --}}
                <button class="btn btn-outline-warning btn-sm me-1 text-dark" data-bs-toggle="tooltip" title="Edit" onclick="$('#editVerticalModal{{ $category->id }}').modal('show')">
                    <i class="fas fa-pen"></i>
                </button>
                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" onclick="$('#deleteVerticalModal{{ $category->id }}').modal('show')">
                    <i class="fas fa-trash"></i>
                </button>
            @endif
        </td>
    </tr>

    @if(!$category->trashed())
    <!-- Edit Modal -->
    <div class="modal fade" id="editVerticalModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form action="{{ route('masters.verticals.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-dark rounded-top-4">
                        <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Short Form</label>
                            <input type="text" name="short_form" class="form-control" value="{{ $category->short_form ?? '' }}" placeholder="e.g., CS for Cyber Security" maxlength="10">
                            <small class="text-muted">Used for ticket reference number generation (e.g., CS-20260525001)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">
                                Assigned User(s) <span class="text-danger">*</span>
                            </label>
                            @php
                                $assignedUserIds = $category->users->pluck('id')->toArray();
                            @endphp
                            
                            <div class="border rounded p-2 bg-light" style="max-height: 160px; overflow-y: auto;">
                                @foreach($assignableUsers as $user)
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                            type="checkbox" 
                                            name="user_ids[]" 
                                            value="{{ $user->id }}" 
                                            id="user_{{ $category->id }}_{{ $user->id }}"
                                            {{ in_array($user->id, $assignedUserIds) ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark" for="user_{{ $category->id }}_{{ $user->id }}">
                                            {{ $user->full_name ?? $user->name }} ({{ strtoupper($user->role->slug ?? 'USER') }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted">Check the checkbox to select or deselect the user.</small>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="send_email" id="send_email_{{ $category->id }}" value="1" {{ $category->send_email ? 'checked' : '' }}>
                            <label class="form-check-label text-dark" for="send_email_{{ $category->id }}">
                                Send email notifications for this category?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark fw-bold">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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