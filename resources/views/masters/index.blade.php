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
            <button class="nav-link active" id="network-types-tab" data-bs-toggle="tab" data-bs-target="#network-types" type="button" role="tab">Network Types</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sections-tab" data-bs-toggle="tab" data-bs-target="#sections" type="button" role="tab">Sections</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="statuses-tab" data-bs-toggle="tab" data-bs-target="#statuses" type="button" role="tab">Status</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="verticals-tab" data-bs-toggle="tab" data-bs-target="#verticals" type="button" role="tab">Verticals</button>
        </li>
    </ul>
    <div class="tab-content" id="masterTabsContent">
        <!-- Network Types Tab -->
        <div class="tab-pane fade show active" id="network-types" role="tabpanel">
            <div class="card mb-4 shadow rounded-4 border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-network-wired me-2"></i>Network Types</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" data-bs-toggle="modal" data-bs-target="#addNetworkTypeModal"><i class="fas fa-plus"></i> </button>
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
                            <tr>
                                <td class="ps-4">{{ $networkType->name }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-primary btn-sm me-1" data-bs-toggle="tooltip" title="Edit" data-bs-target="#editNetworkTypeModal{{ $networkType->id }}" data-bs-toggle2="modal" onclick="$('#editNetworkTypeModal{{ $networkType->id }}').modal('show')"><i class="fas fa-pen"></i></button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-target="#deleteNetworkTypeModal{{ $networkType->id }}" data-bs-toggle2="modal" onclick="$('#deleteNetworkTypeModal{{ $networkType->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Edit Modal for this NetworkType -->
                            <div class="modal fade" id="editNetworkTypeModal{{ $networkType->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.network-types.update', $networkType) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-primary text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Edit Network Type</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control tom-select" value="{{ $networkType->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" data-bs-toggle="modal" data-bs-target="#addSectionModal"><i class="fas fa-plus"></i> </button>
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
                            <tr>
                                <td class="ps-4">{{ $section->name }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-success btn-sm me-1" data-bs-toggle="tooltip" title="Edit" data-bs-target="#editSectionModal{{ $section->id }}" onclick="$('#editSectionModal{{ $section->id }}').modal('show')"><i class="fas fa-pen"></i></button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteSectionModal{{ $section->id }}" data-bs-toggle2="modal" onclick="$('#deleteSectionModal{{ $section->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Edit Modal for this Section -->
                            <div class="modal fade" id="editSectionModal{{ $section->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.sections.update', $section) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-success text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Edit Section</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control tom-select" value="{{ $section->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" data-bs-toggle="modal" data-bs-target="#addStatusModal"><i class="fas fa-plus"></i> </button>
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
                            <tr>
                                <td class="ps-4">{{ $status->display_name }}</td>
                                <td>
                                    <span class="badge px-3 py-2 bg-{{ $status->color }}">
                                        {{ $status->color }}
                                    </span>
                                </td>

                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-info btn-sm me-1" data-bs-toggle="tooltip" title="Edit" data-bs-target="#editStatusModal{{ $status->id }}" onclick="$('#editStatusModal{{ $status->id }}').modal('show')"><i class="fas fa-pen"></i></button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteStatusModal{{ $status->id }}" data-bs-toggle2="modal" onclick="$('#deleteStatusModal{{ $status->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Edit Modal for this Status -->
                            <div class="modal fade" id="editStatusModal{{ $status->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.statuses.update', $status) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-info text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Edit Status</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control tom-select" value="{{ $status->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Color</label>
                                                    <select name="color" class="form-select" required>
                                                        <option value="primary" {{ $status->color == 'primary' ? 'selected' : '' }}>Primary</option>
                                                        <option value="secondary" {{ $status->color == 'secondary' ? 'selected' : '' }}>Secondary</option>
                                                        <option value="success" {{ $status->color == 'success' ? 'selected' : '' }}>Success</option>
                                                        <option value="danger" {{ $status->color == 'danger' ? 'selected' : '' }}>Danger</option>
                                                        <option value="warning" {{ $status->color == 'warning' ? 'selected' : '' }}>Warning</option>
                                                        <option value="info" {{ $status->color == 'info' ? 'selected' : '' }}>Info</option>
                                                        <option value="light" {{ $status->color == 'light' ? 'selected' : '' }}>Light</option>
                                                        <option value="dark" {{ $status->color == 'dark' ? 'selected' : '' }}>Dark</option>
                                                    </select>
                                                </div>
                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" name="visible_to_user" id="visible_to_user_{{ $status->id }}" value="1" {{ $status->visible_to_user ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="visible_to_user_{{ $status->id }}">
                                                        Show to user in status dropdown?
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-info">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                    <h5 class="mb-0 fw-bold"><i class="fas fa-building me-2"></i>Verticals</h5>
                    <button class="btn btn-light btn-sm fw-semibold px-3 py-1" data-bs-toggle="modal" data-bs-target="#addVerticalModal"><i class="fas fa-plus"></i> </button>
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
                            @forelse($verticals as $vertical)
                            <tr>
                                <td class="ps-4">{{ $vertical->name }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-outline-warning btn-sm me-1" data-bs-toggle="tooltip" title="Edit" data-bs-target="#editVerticalModal{{ $vertical->id }}" onclick="$('#editVerticalModal{{ $vertical->id }}').modal('show')"><i class="fas fa-pen"></i></button>
                                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="tooltip" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteVerticalModal{{ $vertical->id }}" data-bs-toggle2="modal" onclick="$('#deleteVerticalModal{{ $vertical->id }}').modal('show')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <!-- Edit Modal for this Vertical -->
                            <div class="modal fade" id="editVerticalModal{{ $vertical->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.verticals.update', $vertical) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header bg-warning text-dark rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-pen me-2"></i>Edit Vertical</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" class="form-control tom-select" value="{{ $vertical->name }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-warning">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Delete Modal for this Vertical -->
                            <div class="modal fade" id="deleteVerticalModal{{ $vertical->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-4">
                                        <form action="{{ route('masters.verticals.destroy', $vertical) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header bg-danger text-white rounded-top-4">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Delete Vertical</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="mb-0">Are you sure you want to delete <strong>{{ $vertical->name }}</strong>?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted">No Verticals found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals for Add/Edit (one for each master, can be reused for edit) -->
    <!-- Network Type Modals -->
    <div class="modal fade" id="addNetworkTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <form action="{{ route('masters.network-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white rounded-top-4">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Network Type</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control tom-select" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editNetworkTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Network Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control tom-select" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Section Modals -->
    <div class="modal fade" id="addSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                  <form action="{{ route('masters.sections.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control tom-select" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editSectionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Section</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control tom-select" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Status Modals -->
    <div class="modal fade" id="addStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.statuses.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control tom-select" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <select name="color" class="form-select" required>
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
                            <input class="form-check-input" type="checkbox" name="visible_to_user" id="visible_to_user_new" value="1" checked>
                            <label class="form-check-label" for="visible_to_user_new">
                                Show to user in status dropdown?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control tom-select" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Color</label>
                            <input type="color" class="form-control form-control-color" value="#0d6efd" title="Choose color">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Vertical Modals -->
    <div class="modal fade" id="addVerticalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('masters.verticals.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Vertical</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control tom-select" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editVerticalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                  <form action="{{ route('masters.verticals.update', $vertical) }}"  method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Vertical</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control tom-select" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
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
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('select.tom-select').forEach(function(el) {
            new TomSelect(el, {
                create: false,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                }
            });
        });
    });
</script>
@endpush
@endsection 