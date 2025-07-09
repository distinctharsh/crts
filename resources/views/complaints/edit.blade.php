@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Edit Ticket #{{ $complaint->reference_number }}</h2>
            <div>
                <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-secondary">Back to Details</a>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Ticket</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('complaints.update', $complaint) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: User Name and Intercom -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="user_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                id="user_name" name="user_name" value="{{ old('user_name', $complaint->user_name) }}" required maxlength="30">
                            @error('user_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="intercom" class="form-label">Intercom / Telephone No. <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('intercom') is-invalid @enderror"
                                id="intercom" name="intercom" value="{{ old('intercom', $complaint->intercom) }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)" required maxlength="10">
                            @error('intercom')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Section and Issue Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('section_id') is-invalid @enderror"
                                id="section_id" name="section_id" required>
                                <option value="">Select --</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id', $complaint->section_id) == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('section_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="network_type_id" class="form-label">Issue Type <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('network_type_id') is-invalid @enderror"
                                id="network_type_id" name="network_type_id" required>
                                <option value="">Select --</option>
                                @foreach($networkTypes as $type)
                                <option value="{{ $type->id }}" {{ old('network_type_id', $complaint->network_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('network_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 3: Vertical and Priority (radio) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="vertical_id" class="form-label">Vertical <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('vertical_id') is-invalid @enderror"
                                id="vertical_id" name="vertical_id" required>
                                <option value="">Select --</option>
                                @foreach($verticals as $vertical)
                                <option value="{{ $vertical->id }}" {{ old('vertical_id', $complaint->vertical_id) == $vertical->id ? 'selected' : '' }}>
                                    {{ $vertical->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('vertical_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priority" id="high" value="high" {{ old('priority', $complaint->priority) == 'high' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="high">High</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priority" id="medium" value="medium" {{ old('priority', $complaint->priority) == 'medium' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="medium">Medium</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="priority" id="low" value="low" {{ old('priority', $complaint->priority) == 'low' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="low">Low</label>
                                </div>
                            </div>
                            @error('priority')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Ticket Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" placeholder="Enter the Issue.. "
                            id="description" name="description" rows="3" required>{{ old('description', $complaint->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File Upload (Full Width) -->
                    <div class="mb-4">
                        <label for="file" class="form-label">File Upload</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror"
                            id="file" name="file" accept=".pdf,image/*">
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($complaint->file_path)
                        <div class="mt-2">
                            <small>Current file: </small>
                            <a href="{{ Storage::url($complaint->file_path) }}" target="_blank">
                                {{ basename($complaint->file_path) }}
                            </a>
                            <input type="hidden" name="delete_file" id="delete_file" value="0">
                        </div>
                        @endif
                    </div>

                    <!-- Status (Full Width) -->
                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status *</label>
                        <select class="form-select tom-select @error('status_id') is-invalid @enderror"
                            id="status_id" name="status_id" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('status_id', $complaint->status_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->display_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('status_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Ticket</button>
                </form>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Ticket Details</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Reference</dt>
                    <dd class="col-sm-8">{{ $complaint->reference_number }}</dd>

                    <dt class="col-sm-4">Created By</dt>
                    <dd class="col-sm-8">{{ $complaint->client?->full_name ?? $complaint->user_name }}</dd>

                    <dt class="col-sm-4">Created At</dt>
                    <dd class="col-sm-8">{{ $complaint->created_at->format('M d, Y H:i') }}</dd>

                    <dt class="col-sm-4">Last Updated</dt>
                    <dd class="col-sm-8">{{ $complaint->updated_at->format('M d, Y H:i') }}</dd>

                    @if($complaint->assignedTo)
                    <dt class="col-sm-4">Assigned To</dt>
                    <dd class="col-sm-8">{{ $complaint->assignedTo->full_name }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection