@foreach($categories as $category)
<tr>
    <td class="ps-4" style="padding-left: {{ $level * 20 + 15 }}px !important;">
        @if($level > 0)
            {!! str_repeat('↳ ', $level) !!}
        @endif
        {{ $category->name }}
    </td>
    <td class="ps-4">{{ $category->short_form ?? '-' }}</td>
    <td class="ps-4">{{ $category->parent ? $category->parent->name : 'Main Category' }}</td>
    <td class="ps-4">
        <span class="badge {{ $category->send_email ? 'bg-success' : 'bg-danger' }}">
            {{ $category->send_email ? 'Yes' : 'No' }}
        </span>
    </td>
    <td class="text-end pe-4">
        <!-- Edit Button -->
        <button class="btn btn-outline-warning btn-sm me-1" 
                data-bs-toggle="tooltip" 
                title="Edit Category" 
                onclick="$('#editVerticalModal{{ $category->id }}').modal('show')">
            <i class="fas fa-pen"></i>
        </button>
        
        <!-- Delete Button -->
        <button class="btn btn-outline-danger btn-sm" 
                data-bs-toggle="tooltip" 
                title="Delete Category" 
                onclick="$('#deleteVerticalModal{{ $category->id }}').modal('show')">
            <i class="fas fa-trash"></i>
        </button>
    </td>
</tr>

<!-- Edit Vertical Modal -->
<div class="modal fade" id="editVerticalModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <form action="{{ route('masters.verticals.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark rounded-top-4">
                    <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Form</label>
                        <input type="text" name="short_form" class="form-control" value="{{ $category->short_form }}" maxlength="10">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="send_email" id="send_email_{{ $category->id }}" value="1" {{ $category->send_email ? 'checked' : '' }}>
                        <label class="form-check-label" for="send_email_{{ $category->id }}">
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

<!-- Delete Vertical Modal -->
<div class="modal fade" id="deleteVerticalModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <form action="{{ route('masters.verticals.destroy', $category->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <p class="mb-0">Are you sure you want to delete <strong>{{ $category->name }}</strong>? Iske saare sub-categories par bhi asar pad sakta hai.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($category->children && $category->children->count() > 0)
    @include('masters.partials.category-row', [
        'categories' => $category->children,
        'level' => $level + 1
    ])
@endif
@endforeach