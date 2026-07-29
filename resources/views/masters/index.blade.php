@extends('layouts.app')

@section('content')
@php
    function getTextColor($bgColor) {
        $bgColor = ltrim($bgColor, '#');
        $r = hexdec(substr($bgColor, 0, 2));
        $g = hexdec(substr($bgColor, 2, 2));
        $b = hexdec(substr($bgColor, 4, 2));
        return (($r * 299 + $g * 587 + $b * 114) / 1000) > 128 ? '#222' : '#fff';
    }
@endphp
<div class="container py-4">
    <h2 class="fw-bold mb-4 text-center">Master Management</h2>
    <ul class="nav nav-tabs mb-3" id="masterTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="network-types-tab" data-bs-toggle="tab" data-bs-target="#network-types" type="button" role="tab">Issue Types</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">Sections</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="statuses-tab" data-bs-toggle="tab" data-bs-target="#statuses" type="button" role="tab">Status</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="verticals-tab" data-bs-toggle="tab" data-bs-target="#verticals" type="button" role="tab">Category</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="request-types-tab" data-bs-toggle="tab" data-bs-target="#request-types" type="button" role="tab">Request Types</button>
        </li>
    </ul>
    <div class="tab-content" id="masterTabsContent">
        <!-- Network Types Tab -->
        <div class="tab-pane fade show active" id="network-types" role="tabpanel">
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-network-wired me-2"></i>Issue Types</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" onclick="openNetworkTypeModal('add')"><i class="fas fa-plus"></i> </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($networkTypes as $networkType)
                            <tr class="{{ $networkType->trashed() ? 'table-light text-muted opacity-75' : '' }}">
                                <td class="ps-4">
                                    {{ $networkType->name }}
                                    @if($networkType->trashed())
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">Deleted</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($networkType->trashed())
                                        <form action="{{ route('masters.network-types.restore', $networkType->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Restore Network Type">
                                                <i class="fas fa-undo me-1"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit" onclick="openNetworkTypeModal('edit', {{ $networkType->id }}, '{{ $networkType->name }}')"><i class="fas fa-pen"></i></button>
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-target="#deleteNetworkTypeModal{{ $networkType->id }}" data-bs-toggle2="modal" onclick="$('#deleteNetworkTypeModal{{ $networkType->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            
                            @if(!$networkType->trashed())
                            <!-- Delete Modal for this NetworkType -->
                            <div class="modal fade" id="deleteNetworkTypeModal{{ $networkType->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.network-types.destroy', $networkType) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Network Type</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">Are you sure you want to delete <strong>{{ $networkType->name }}</strong>?</p>
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
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No Network Types found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sections Tab -->
        <div class="tab-pane fade" id="sections" role="tabpanel">
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-success text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-layer-group me-2"></i>Sections</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" onclick="openSectionModal('add')"><i class="fas fa-plus"></i> </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                            <tr class="{{ $section->trashed() ? 'table-light text-muted opacity-75' : '' }}">
                                <td class="ps-4">
                                    {{ $section->name }}
                                    @if($section->trashed())
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">Deleted</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($section->trashed())
                                        <form action="{{ route('masters.sections.restore', $section->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Restore Section">
                                                <i class="fas fa-undo me-1"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Edit" onclick="openSectionModal('edit', {{ $section->id }}, '{{ $section->name }}')"><i class="fas fa-pen"></i></button>
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-target="#deleteSectionModal{{ $section->id }}" data-bs-toggle2="modal" onclick="$('#deleteSectionModal{{ $section->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            
                            @if(!$section->trashed())
                            <!-- Delete Modal for this Section -->
                            <div class="modal fade" id="deleteSectionModal{{ $section->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.sections.destroy', $section) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Section</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">Are you sure you want to delete <strong>{{ $section->name }}</strong>?</p>
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

                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No Sections found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Statuses Tab -->
        <div class="tab-pane fade" id="statuses" role="tabpanel">
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-info text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-flag me-2"></i>Status</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" onclick="openStatusModal('add')"><i class="fas fa-plus"></i> </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th>Color</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statuses as $status)
                            <tr class="{{ $status->trashed() ? 'table-light text-muted opacity-75' : '' }}">
                                <td class="ps-4">
                                    {{ $status->display_name }}
                                    @if($status->trashed())
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">Deleted</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge px-3 py-2 bg-{{ $status->color }}">
                                        {{ $status->color }}
                                    </span>
                                </td>

                                <td class="text-end pe-4">
                                    @if($status->trashed())
                                        <form action="{{ route('masters.statuses.restore', $status->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Restore Status">
                                                <i class="fas fa-undo me-1"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <!-- <button class="btn btn-outline-info btn-sm me-1" data-bs-toggle="tooltip" title="Edit" onclick="openStatusModal('edit', {{ $status->id }}, '{{ $status->name }}', '{{ $status->color }}', {{ $status->visible_to_user ? 'true' : 'false' }})"><i class="fas fa-pen"></i></button> -->
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-target="#deleteStatusModal{{ $status->id }}" data-bs-toggle2="modal" onclick="$('#deleteStatusModal{{ $status->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>

                            @if(!$status->trashed())
                            <!-- Delete Modal for this Status -->
                            <div class="modal fade" id="deleteStatusModal{{ $status->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.statuses.destroy', $status) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Status</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">Are you sure you want to delete <strong>{{ $status->name }}</strong>?</p>
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
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No Status found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Verticals Tab -->
        <div class="tab-pane fade" id="verticals" role="tabpanel">
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-warning text-dark rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-building me-2"></i>Category</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" onclick="openCategoryModal('add')"><i class="fas fa-plus"></i> </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th class="ps-4">Short Form</th>
                                <th class="ps-4">Parent Name</th>
                                <th class="ps-4">Users</th>
                                <th class="ps-4">Send Email</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @include('masters.partials.category-row', [
                                'categories' => $verticals,
                                'level' => 0,
                                'assignableUsers' => $assignableUsers
                            ])
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Request Types Tab -->
        <div class="tab-pane fade" id="request-types" role="tabpanel">
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center text-white rounded-top-4" style="background-color: #6f42c1;">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-list-alt me-2"></i>Request Types</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" onclick="openRequestTypeModal('add')"><i class="fas fa-plus"></i> </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Name</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requestTypes as $requestType)
                            <tr class="{{ $requestType->trashed() ? 'table-light text-muted opacity-75' : '' }}">
                                <td class="ps-4">
                                    {{ $requestType->name }}
                                    @if($requestType->trashed())
                                        <span class="badge bg-secondary ms-2" style="font-size: 0.75rem;">Deleted</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($requestType->trashed())
                                        <form action="{{ route('masters.request-types.restore', $requestType->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Restore Request Type">
                                                <i class="fas fa-undo me-1"></i> Restore
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-outline-purple btn-sm me-1" style="color: #6f42c1; border-color: #6f42c1;" data-bs-toggle="tooltip" title="Edit" onclick="openRequestTypeModal('edit', {{ $requestType->id }}, '{{ $requestType->name }}')"><i class="fas fa-pen"></i></button>
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" onclick="$('#deleteRequestTypeModal{{ $requestType->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                            
                            @if(!$requestType->trashed())
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteRequestTypeModal{{ $requestType->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.request-types.destroy', $requestType) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Request Type</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0 text-dark">Are you sure you want to delete <strong>{{ $requestType->name }}</strong>? Purane tickets safe rahenge.</p>
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
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No Request Types found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Network Type Modal -->
    <div class="modal fade" id="networkTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form id="networkTypeForm" action="" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" id="networkTypeFormMethod" value="POST">
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title" id="networkTypeModalTitle">Add Issue Type</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="network_type_name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="networkTypeSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Section Modal -->
    <div class="modal fade" id="sectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form id="sectionForm" action="" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" id="sectionFormMethod" value="POST">
                    <div class="modal-header bg-success text-white rounded-top-4">
                        <h5 class="modal-title" id="sectionModalTitle">Add Section</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="section_name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="sectionSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form id="statusForm" action="" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" id="statusFormMethod" value="POST">
                    <div class="modal-header bg-info text-white rounded-top-4">
                        <h5 class="modal-title" id="statusModalTitle">Add Status</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="status_name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Color <span class="text-danger">*</span></label>
                            <select id="status_color" name="color" class="form-select" required>
                                <option value="primary">Primary</option>
                                <option value="secondary">Secondary</option>
                                <option value="success">Success</option>
                                <option value="danger">Danger</option>
                                <option value="warning">Warning</option>
                                <option value="info">Info</option>
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="visible_to_user" id="status_visible_to_user" value="1" checked>
                            <label class="form-check-label text-dark" for="status_visible_to_user">
                                Show to user in status dropdown?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info" id="statusSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Vertical Modals -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form id="categoryForm" action="" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" id="categoryFormMethod" value="POST">
                    <div class="modal-header bg-warning text-dark rounded-top-4">
                        <h5 class="modal-title" id="categoryModalTitle">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="category_name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Short Form</label>
                            <input type="text" id="category_short_form" name="short_form" class="form-control" placeholder="e.g., CS for Cyber Security" maxlength="10">
                            <small class="text-muted">Used for ticket reference number generation (e.g., CS-20260525001)</small>
                        </div>
                        @if(isset($allVerticals))
                        <div class="mb-3">
                            <label for="category_parent_id" class="form-label text-dark fw-bold">Select Parent (Optional)</label>
                            <select class="form-select" id="category_parent_id" name="parent_id">
                                <option value="">None (Make it a Main Category)</option>
                            @php
                                function getVerticalHierarchy($vertical) {
                                    $path = [];
                                    $current = $vertical;
                                    while($current) {
                                        array_unshift($path, $current->name);
                                        $current = $current->parent;
                                    }
                                    return implode(' → ', $path);
                                }
                            @endphp
                            @foreach($allVerticals as $vertical)
                                    <option value="{{ $vertical->id }}">
                                        {{ getVerticalHierarchy($vertical) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">
                                Assigned User(s) <span class="text-danger">*</span>
                            </label>
                            <div class="border rounded p-2 bg-light" style="max-height: 160px; overflow-y: auto;" id="categoryUsersGroup">
                                @foreach($assignableUsers as $user)
                                    <div class="form-check">
                                        <input class="form-check-input category-user-checkbox" 
                                            type="checkbox" 
                                            name="user_ids[]" 
                                            value="{{ $user->id }}" 
                                            id="category_user_{{ $user->id }}"
                                            data-user-name="{{ $user->full_name ?? $user->name }}"
                                            data-user-role="{{ strtoupper($user->role->slug ?? 'USER') }}">
                                        <label class="form-check-label text-dark" for="category_user_{{ $user->id }}">
                                            {{ $user->full_name ?? $user->name }} ({{ strtoupper($user->role->slug ?? 'USER') }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div id="category_user_error" class="text-danger" style="display: none;">
                                Please select at least one assigned user.
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="send_email" id="category_send_email" value="1" checked>
                            <label class="form-check-label text-dark" for="category_send_email">
                                Send email notifications for this category?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark fw-bold" id="categorySubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Sub Category -->
    <div class="modal fade" id="addSubCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form action="{{ route('masters.sub-categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-purple text-white rounded-top-4" style="background-color: #6f42c1;">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Sub Category</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Main Category</label>
                            <select name="vertical_id" class="form-select" required>
                                <option value="">-- Choose Category --</option>
                                @foreach($allVerticals as $vertical)
                                    <option value="{{ $vertical->id }}">
                                        {{ $vertical->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub Category Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g., Phishing, Printer Issue">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Form</label>
                            <input type="text" name="short_form" class="form-control" placeholder="e.g., PH" maxlength="10">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: #6f42c1;">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Request Type Modal -->
    <div class="modal fade" id="requestTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form id="requestTypeForm" action="" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" id="requestTypeFormMethod" value="POST">
                    <div class="modal-header text-white rounded-top-4" style="background-color: #6f42c1;">
                        <h5 class="modal-title" id="requestTypeModalTitle">Add Request Type</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label text-dark fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" id="request_type_name" name="name" class="form-control" required placeholder="e.g., Incident, Service Request">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn text-white" style="background-color: #6f42c1;" id="requestTypeSubmitBtn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        document.querySelectorAll('select.tom-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                allowEmptyOption: true,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                }
            });
        });

        const categoryUserCheckboxes = document.querySelectorAll('.category-user-checkbox');
        function validateCategoryUserCheckboxes() {
            const isChecked = Array.from(categoryUserCheckboxes).some(cb => cb.checked);
            const errorDiv = document.getElementById('category_user_error');
            if (!isChecked) {
                errorDiv.style.display = 'block';
            } else {
                errorDiv.style.display = 'none';
            }
            categoryUserCheckboxes.forEach(cb => {
                cb.required = !isChecked;
            });
        }

        categoryUserCheckboxes.forEach(cb => {
            cb.addEventListener('change', validateCategoryUserCheckboxes);
        });

        validateCategoryUserCheckboxes();
    });

    function openCategoryModal(mode, id = null, name = '', shortForm = '', parentId = '', sendEmail = false, userIds = []) {
        const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
        const form = document.getElementById('categoryForm');
        const title = document.getElementById('categoryModalTitle');
        const submitBtn = document.getElementById('categorySubmitBtn');
        const methodInput = document.getElementById('categoryFormMethod');

        form.reset();
        document.getElementById('category_user_error').style.display = 'none';
        document.querySelectorAll('.category-user-checkbox').forEach(cb => {
            cb.checked = false;
            cb.required = false;
        });

        if (mode === 'edit') {
            title.textContent = 'Edit Category';
            submitBtn.textContent = 'Update';
            methodInput.value = 'PUT';
            form.action = '/masters/verticals/' + id;
            document.getElementById('category_name').value = name;
            document.getElementById('category_short_form').value = shortForm;
            document.getElementById('category_parent_id').value = parentId;
            document.getElementById('category_send_email').checked = sendEmail;
            userIds.forEach(userId => {
                const checkbox = document.getElementById('category_user_' + userId);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
        } else {
            title.textContent = 'Add Category';
            submitBtn.textContent = 'Save';
            methodInput.value = 'POST';
            form.action = '/masters/verticals';
            document.getElementById('category_send_email').checked = true;
        }

        modal.show();
    }

    function openStatusModal(mode, id = null, name = '', color = '', visibleToUser = false) {
        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        const form = document.getElementById('statusForm');
        const title = document.getElementById('statusModalTitle');
        const submitBtn = document.getElementById('statusSubmitBtn');
        const methodInput = document.getElementById('statusFormMethod');

        form.reset();

        if (mode === 'edit') {
            title.textContent = 'Edit Status';
            submitBtn.textContent = 'Update';
            methodInput.value = 'PUT';
            form.action = '/masters/statuses/' + id;
            document.getElementById('status_name').value = name;
            document.getElementById('status_color').value = color;
            document.getElementById('status_visible_to_user').checked = visibleToUser;
        } else {
            title.textContent = 'Add Status';
            submitBtn.textContent = 'Save';
            methodInput.value = 'POST';
            form.action = '/masters/statuses';
            document.getElementById('status_visible_to_user').checked = true;
        }

        modal.show();
    }

    function openSectionModal(mode, id = null, name = '') {
        const modal = new bootstrap.Modal(document.getElementById('sectionModal'));
        const form = document.getElementById('sectionForm');
        const title = document.getElementById('sectionModalTitle');
        const submitBtn = document.getElementById('sectionSubmitBtn');
        const methodInput = document.getElementById('sectionFormMethod');

        form.reset();

        if (mode === 'edit') {
            title.textContent = 'Edit Section';
            submitBtn.textContent = 'Update';
            methodInput.value = 'PUT';
            form.action = '/masters/sections/' + id;
            document.getElementById('section_name').value = name;
        } else {
            title.textContent = 'Add Section';
            submitBtn.textContent = 'Save';
            methodInput.value = 'POST';
            form.action = '/masters/sections';
        }

        modal.show();
    }

    function openMasterModal(config) {
        const modal = new bootstrap.Modal(document.getElementById(config.modalId));
        const form = document.getElementById(config.formId);
        const title = document.getElementById(config.titleId);
        const submitBtn = document.getElementById(config.submitBtnId);
        const methodInput = document.getElementById(config.methodInputId);

        form.reset();

        if (config.mode === 'edit') {
            title.textContent = 'Edit ' + config.entityName;
            submitBtn.textContent = 'Update';
            methodInput.value = 'PUT';
            form.action = '/masters/' + config.routePrefix + '/' + config.id;
            for (const [fieldId, value] of Object.entries(config.fields)) {
                const element = document.getElementById(fieldId);
                if (element) {
                    if (element.type === 'checkbox') {
                        element.checked = value;
                    } else {
                        element.value = value;
                    }
                }
            }
        } else {
            title.textContent = 'Add ' + config.entityName;
            submitBtn.textContent = 'Save';
            methodInput.value = 'POST';
            form.action = '/masters/' + config.routePrefix;
        }

        modal.show();
    }

    function openSectionModal(mode, id = null, name = '') {
        openMasterModal({
            mode: mode,
            modalId: 'sectionModal',
            formId: 'sectionForm',
            titleId: 'sectionModalTitle',
            submitBtnId: 'sectionSubmitBtn',
            methodInputId: 'sectionFormMethod',
            routePrefix: 'sections',
            entityName: 'Section',
            id: id,
            fields: { section_name: name }
        });
    }

    function openNetworkTypeModal(mode, id = null, name = '') {
        openMasterModal({
            mode: mode,
            modalId: 'networkTypeModal',
            formId: 'networkTypeForm',
            titleId: 'networkTypeModalTitle',
            submitBtnId: 'networkTypeSubmitBtn',
            methodInputId: 'networkTypeFormMethod',
            routePrefix: 'network-types',
            entityName: 'Issue Type',
            id: id,
            fields: { network_type_name: name }
        });
    }

    function openRequestTypeModal(mode, id = null, name = '') {
        openMasterModal({
            mode: mode,
            modalId: 'requestTypeModal',
            formId: 'requestTypeForm',
            titleId: 'requestTypeModalTitle',
            submitBtnId: 'requestTypeSubmitBtn',
            methodInputId: 'requestTypeFormMethod',
            routePrefix: 'request-types',
            entityName: 'Request Type',
            id: id,
            fields: { request_type_name: name }
        });
    }
</script>
@endpush
@endsection 