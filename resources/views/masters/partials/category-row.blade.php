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
                <form action="{{ route('masters.verticals.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category and all its sub-categories?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            @endif
        </td>
    </tr>

    @if(!$category->trashed())
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
                            <label class="form-label text-dark fw-bold">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Short Form</label>
                            <input type="text" name="short_form" class="form-control" value="{{ $category->short_form ?? '' }}" placeholder="e.g., CS for Cyber Security" maxlength="10">
                            <small class="text-muted">Used for ticket reference number generation (e.g., CS-20260525001)</small>
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
    @endif

    {{-- Recursive inclusion for sub-categories --}}
    @if($category->children()->withTrashed()->count() > 0)
        @include('masters.partials.category-row', [
            'categories' => $category->children()->withTrashed()->orderBy('name')->get(),
            'level' => $level + 1
        ])
    @endif
@endforeach