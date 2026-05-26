@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2>{{ isset($complaint) ? 'Edit Ticket #' . $complaint->reference_number : 'Create Ticket' }}</h2>
            <div>
                @if(isset($complaint))
                    <a href="{{ route('complaints.show', $complaint) }}" class="btn btn-secondary">Back to Details</a>
                @else
                    @auth
                        <a href="{{ route('complaints.index') }}" class="btn btn-secondary">Back</a>
                    @else
                        <a href="/home" class="btn btn-secondary">Back</a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ticket Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ isset($complaint) ? route('complaints.update', $complaint) : route('complaints.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($complaint))
                        @method('PUT')
                    @endif

                    <!-- First Row - User Name and Intercom -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="user_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                id="user_name" name="user_name" value="{{ old('user_name', isset($complaint) ? $complaint->user_name : '') }}" placeholder="Name of the User" required maxlength="30">
                            @error('user_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="intercom" class="form-label">Intercom / Telephone No. <span class="text-danger">*</span></label>
                            <select id="intercom" name="intercom" class="form-select tom-select @error('intercom') is-invalid @enderror" required >
                                <option value="">Enter or select Intercom</option>
                                @foreach($intercoms as $intercom)
                                    <option value="{{ $intercom }}"
                                        {{ old('intercom', isset($complaint) ? $complaint->intercom : '') == $intercom ? 'selected' : '' }}
                                    >
                                        {{ $intercom }}
                                    </option>
                                @endforeach
                            </select>

                            @error('intercom')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Second Row - Network Type and Priority -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('room_number') is-invalid @enderror"
                                id="room_number" name="room_number" value="{{ old('room_number', isset($complaint) ? $complaint->room_number : '') }}" placeholder="Enter Room Number" required min="0" max="999999">
                            @error('room_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <!-- <small class="text-muted">Max 6 digits</small> -->
                        </div>
                        <div class="col-md-6">
                            <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('section_id') is-invalid @enderror"
                                id="section_id" name="section_id" required>
                                <option value="">Select --</option>
                                @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ old('section_id', isset($complaint) ? $complaint->section_id : '') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('section_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                       
                    </div>

                    <!-- Third Row - Vertical and Room Number -->
                    <div class="row mb-3">
                         <div class="col-md-6">
                            <label for="network_type_id" class="form-label">Issue Type <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('network_type_id') is-invalid @enderror"
                                id="network_type_id" name="network_type_id" required>
                                <option value="">Select --</option>
                                @foreach($networkTypes as $type)
                                <option value="{{ $type->id }}" {{ old('network_type_id', isset($complaint) ? $complaint->network_type_id : '') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('network_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="vertical_ids" class="form-label">Verticals <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('vertical_ids') is-invalid @enderror"
                                id="vertical_ids" name="vertical_ids[]" multiple required>
                                @php
                                    // Separate verticals: those that are not "Other" and the "Other" option
                                    $otherVertical = null;
                                    $regularVerticals = [];
                                    foreach($verticals as $vertical) {
                                        if(strtolower($vertical->name) === 'other') {
                                            $otherVertical = $vertical;
                                        } else {
                                            $regularVerticals[] = $vertical;
                                        }
                                    }
                                @endphp
                                @foreach($regularVerticals as $vertical)
                                <option value="{{ $vertical->id }}" {{ in_array($vertical->id, old('vertical_ids', isset($complaint) ? $complaint->verticals->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                    {{ $vertical->name }}
                                </option>
                                @endforeach
                                @if($otherVertical)
                                <option value="{{ $otherVertical->id }}" {{ in_array($otherVertical->id, old('vertical_ids', isset($complaint) ? $complaint->verticals->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                    {{ $otherVertical->name }}
                                </option>
                                @endif
                            </select>
                            @error('vertical_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Hold Ctrl/Cmd to select multiple verticals</small>
                        </div>
                      
                    </div>

                    <!-- Priority Field - Checkbox instead of Radio -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Priority <span class="text-danger">*</span></label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="priority" id="high" value="high" 
                                        {{ old('priority', isset($complaint) ? $complaint->priority : '') == 'high' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="high">High</label>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">If not selected, priority will default to Medium</small>
                            @error('priority')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Complaint Description (Full Width) -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Ticket Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" placeholder="Enter the Issue.. "
                            id="description" name="description" rows="3" required>{{ old('description', isset($complaint) ? $complaint->description : '') }}</textarea>
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
                        <small class="text-danger">Allowed: PDF, Images (jpg, jpeg, png). Max file size: 2MB</small>
                        @if(isset($complaint) && $complaint->file_path)
                        <div class="mt-2">
                            <small>Current file: </small>
                            <a href="{{ Storage::url($complaint->file_path) }}" target="_blank">
                                {{ basename($complaint->file_path) }}
                            </a>
                            <input type="hidden" name="delete_file" id="delete_file" value="0">
                        </div>
                        @endif
                    </div>

                    @if(isset($complaint))
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
                    @endif

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary shadow-sm" id="submitTicketBtn" style="border-radius: 12px;">
                            <span id="submitBtnText">{{ isset($complaint) ? 'Update Ticket' : 'Submit Ticket' }}</span>
                            <span id="submitBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Information</h5>
            </div>
            <div class="card-body">
                @if(isset($complaint))
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
                @else
                <p class="mb-3">Please provide all the required information to create a new ticket. Our team will review your ticket and take appropriate action.</p>
                <h6 class="mb-2">Important Note:</h6>
                <ul class="list-unstyled mb-3">
                    <li>
                        <span class="badge bg-danger">High Priority</span>
                        - Use only for urgent issues requiring immediate attention
                    </li>
                </ul>
                <h6 class="mb-2">What happens next?</h6>
                <ol class="mb-0">
                    <li class="mb-2">Your ticket will be assigned a unique reference number</li>
                    <li class="mb-2">A manager will review and assign it to the appropriate team</li>
                    <li class="mb-2">You'll receive updates on the status of your ticket</li>
                    <li>You can track your ticket using the reference number</li>
                </ol>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('select.tom-select').forEach(function(el){

        let config = {
            searchField:['text'],
            maxOptions:100,
            persist:false,

            render:{
                no_results:function(){
                    return '<div class="no-results">No result found</div>';
                }
            }
        };

        // Only intercom allows new entries
        if(el.id === 'intercom'){
            config.create = true;
        } else {
            config.create = false;
        }

        // Enable multiple selection for verticals
        if(el.id === 'vertical_ids'){
            config.maxItems = null;
            config.plugins = {
                remove_button:{
                    title:'Remove',
                }
            };
        }

        new TomSelect(el, config);

    });

});
</script>

@endsection

@push('style')
<style>
body {
    background: linear-gradient(120deg, #f8f9fa 0%, #e3eafc 100%);
    min-height: 100vh;
}
.card {
    border-radius: 22px;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.13);
    border: none;
    margin-bottom: 2rem;
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 12px 40px 0 rgba(13, 110, 253, 0.18);
}
.card-header {
    border-radius: 22px 22px 0 0;
    background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
    color: #fff;
    font-weight: 700;
    font-size: 1.18rem;
    letter-spacing: 0.7px;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.07);
}
.form-label {
    font-weight: 600;
    letter-spacing: 0.2px;
    margin-bottom: 0.35rem;
}
input.form-control, select.form-select, textarea.form-control {
    border-radius: 14px;
    font-size: 1.09rem;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.07);
    border: 1px solid #e3eafc;
    background: #f8fafd;
    transition: box-shadow 0.2s, border-color 0.2s;
}
input.form-control:focus, select.form-select:focus, textarea.form-control:focus {
    box-shadow: 0 0 0 2px #0d6efd33;
    border-color: #0d6efd;
    background: #fff;
}
.btn-primary {
    background: linear-gradient(90deg, #0d6efd 0%, #0a58ca 100%);
    border: none;
    font-weight: 700;
    letter-spacing: 0.5px;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.10);
    transition: background 0.2s, box-shadow 0.2s;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #0b5ed7 0%, #0a58ca 100%);
    box-shadow: 0 4px 16px rgba(13, 110, 253, 0.15);
}
.d-grid {
    margin-top: 1.5rem;
}
.card-title {
    letter-spacing: 0.5px;
    font-size: 1.15rem;
}
.card-body {
    padding-top: 2rem;
    padding-bottom: 2rem;
}
/* Intercom Suggestions Styling */
#intercomSuggestions {
    width: 100%;
    left: 0;
    top: 100%;
    max-height: 200px;
    overflow-y: auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-radius: 8px;
}
#intercomSuggestions .list-group-item {
    border: none;
    border-bottom: 1px solid #e3eafc;
    padding: 0.6rem 1rem;
    font-size: 0.95rem;
    transition: background-color 0.2s;
}
#intercomSuggestions .list-group-item:last-child {
    border-bottom: none;
}
#intercomSuggestions .list-group-item:hover {
    background-color: #e7f1ff;
}
@media (max-width: 991px) {
    .card-body {
        padding-top: 1.2rem;
        padding-bottom: 1.2rem;
    }
}
</style>
@endpush