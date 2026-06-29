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
    <div class="col-md-12">
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

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="user_name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('user_name') is-invalid @enderror"
                                id="user_name" name="user_name" value="{{ old('user_name', isset($complaint) ? $complaint->user_name : '') }}" placeholder="Name of the User" required maxlength="30">
                            @error('user_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-4">
                            <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('room_number') is-invalid @enderror"
                                id="room_number" name="room_number" value="{{ old('room_number', isset($complaint) ? $complaint->room_number : '') }}" placeholder="Enter Room Number" required min="0" max="999999">
                            @error('room_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 align-items-end" id="vertical-chain-container">
                        <div class="col-md-4 mb-3">
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

                        <div class="col-md-4 mb-3">
                            <label for="network_type_id" class="form-label">Issue Type <span class="text-danger">*</span></label>
                            <select class="form-select tom-select @error('network_type_id') is-invalid @enderror"
                                id="network_type_id" name="network_type_id" required>
                                <option value="">Select --</option>
                                @foreach($networkTypes as $type)
                                <option value="{{ $type->id }}" {{ old('network_type_id', isset($complaint) ? $complaint->network_type_id : 2) == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('network_type_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3 hierarchy-wrapper">
                            <label class="form-label">Category</label>
                            <select class="form-select hierarchy-select" data-level="1" name="vertical_ids[]" required>
                                <option value="">Select Category</option>
                                @foreach($verticals as $category)
                                    <option value="{{ $category->id }}" {{ old('vertical_ids.0', isset($complaint) && isset($savedVerticals[0]) ? $savedVerticals[0] : '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @auth 
                        @if(!auth()->user()->isNFO())
                        <div class="col-md-4 mb-3" id="assignToWrapper" style="display:none;">
                            <label for="assigned_to" class="form-label">Assign To</label>
                            <select id="assigned_to" name="assigned_to" class="form-select">
                                <option value="">-- Leave Unassigned --</option>
                            </select>
                        </div>
                        @endif
                        @endauth
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
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

                        <div class="col-md-4 mb-3">
                            <label for="file" class="form-label">File Upload</label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror"
                                id="file" name="file" accept=".pdf,image/*">
                            @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-danger">Allowed: PDF, Images. Max size: 2MB</small>
                            @if(isset($complaint) && $complaint->file_path)
                            <div class="mt-2">
                                <small>Current file: </small>
                                <a href="{{ Storage::url($complaint->file_path) }}" target="_blank">{{ basename($complaint->file_path) }}</a>
                                <input type="hidden" name="delete_file" id="delete_file" value="0">
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="description" class="form-label">Ticket Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" placeholder="Enter the Issue.. "
                                id="description" name="description" rows="3" required>{{ old('description', isset($complaint) ? $complaint->description : '') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @if(isset($complaint))
                    <div class="mb-3">
                        <label for="status_id" class="form-label">Status *</label>
                        <select class="form-select tom-select @error('status_id') is-invalid @enderror" id="status_id" name="status_id" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ old('status_id', $complaint->status_id) == $status->id ? 'selected' : '' }}>
                                    {{ $status->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary shadow-sm" id="submitTicketBtn" style="border-radius: 12px;">
                            <span id="submitBtnText">{{ isset($complaint) ? 'Update Ticket' : 'Submit Ticket' }}</span>
                            <span id="submitBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const assignWrapper = document.getElementById('assignToWrapper');
        const assignSelect = document.getElementById('assigned_to');
        const container = document.getElementById('vertical-chain-container');
        const statusSelect = document.getElementById('status_id');

        const oldVerticals = @json(old('vertical_ids', isset($complaint) ? $complaint->verticals->pluck('id')->toArray() : []));
        const selectedVertical = oldVerticals.length ? oldVerticals[oldVerticals.length - 1] : null;
        const selectedUser = @json(old('assigned_to', isset($assignedUser) ? $assignedUser->id : null));
        const savedVerticals = @json(isset($complaint) && isset($savedVerticals) ? $savedVerticals : []);
        const assignedUserData = @json(isset($assignedUser) ? $assignedUser : null);

        @if(isset($complaint))
            const ASSIGNED_STATUS_ID = {{ $statuses->firstWhere('name', 'assigned')?->id ?? 0 }};
            const UNASSIGNED_STATUS_ID = {{ $statuses->firstWhere('name', 'unassigned')?->id ?? 0 }};
        @endif

        document.querySelectorAll('.tom-select').forEach(el => {
            if (el.id === 'vertical_ids') return; 

            const config = {
                searchField: ['text'],
                maxOptions: 100,
                persist: false
            };

            if (el.id === 'intercom') config.create = true;
            new TomSelect(el, config);
        });

        const assignTom = assignSelect
            ? new TomSelect(assignSelect, {
                valueField: 'id',
                labelField: 'full_name',
                searchField: 'full_name',
                persist: false
            })
            : null;

        async function loadAssignableUsers(verticalId, selectedUserId = null) {
            if (!assignTom || !assignWrapper) return;

            if (!verticalId) {
                assignTom.clear();
                assignTom.clearOptions();
                assignWrapper.style.display = 'none';
                return;
            }
            try {
                const response = await fetch(`{{ route('api.assignable-users') }}?vertical_ids=${verticalId}`);
                const users = await response.json();

                assignTom.clear();
                assignTom.clearOptions();

                assignWrapper.style.display = 'block';
                assignTom.addOption({ id: '', full_name: '-- Leave Unassigned --' });
                users.forEach(user => {
                    assignTom.addOption({
                        id: user.id,
                        full_name: `${user.full_name} (${user.role?.name?.toUpperCase() ?? ''})`
                    });
                });

                // If selected user exists but is not in the API response, add them manually
                if (selectedUserId && !users.find(u => u.id == selectedUserId) && assignedUserData) {
                    assignTom.addOption({
                        id: assignedUserData.id,
                        full_name: `${assignedUserData.full_name} (${assignedUserData.role?.name?.toUpperCase() ?? ''})`
                    });
                }

                assignTom.refreshOptions(false);

                if (selectedUserId) {
                    assignTom.setValue(String(selectedUserId), true);
                } else {
                    // If no user selected, ensure the default empty option is selected
                    assignTom.setValue('', true);
                }
            } catch (error) {
                console.error('Failed to load assignable users:', error);
            }
        }

        function triggerDependentAPIs(userId = null) {
            const allSelects = container.querySelectorAll('.hierarchy-select');
            let firstSelectedValue = '';
            for (let i = 0; i < allSelects.length; i++) {
                if (allSelects[i].value) {
                    firstSelectedValue = allSelects[i].value;
                    break;
                }
            }

            const userToSelect = userId !== null ? userId : selectedUser;
            loadAssignableUsers(firstSelectedValue, userToSelect);

            if (!firstSelectedValue && statusSelect) {
                @if(isset($complaint))
                    statusSelect.tomselect ? statusSelect.tomselect.setValue(UNASSIGNED_STATUS_ID) : statusSelect.value = UNASSIGNED_STATUS_ID;
                @endif
            }
        }

        if (container) {
            container.addEventListener('change', async function(e) {
                if (!e.target.classList.contains('hierarchy-select')) return;

                const currentSelect = e.target;
                const currentWrapper = currentSelect.closest('.hierarchy-wrapper');
                const currentLevel = parseInt(currentSelect.getAttribute('data-level'));
                const parentId = currentSelect.value;

                let nextEl = currentWrapper.nextElementSibling;
                while (nextEl) {
                    const toRemove = nextEl;
                    nextEl = nextEl.nextElementSibling;
                    if (toRemove.id !== 'assignToWrapper') {
                        toRemove.remove();
                    }
                }

                if (!parentId) {
                    triggerDependentAPIs();
                    return;
                }

                try {
                    const response = await fetch(`/api/get-child-verticals?parent_id=${parentId}`);
                    const children = await response.json();

                    if (children && children.length > 0) {
                        const nextLevel = currentLevel + 1;
                        let labelText = 'Sub-Category';

                        const selectHtml = `
                            <div class="col-md-4 mb-3 hierarchy-wrapper">
                                <label class="form-label">${labelText}</label>
                                <select class="form-select hierarchy-select" data-level="${nextLevel}" name="vertical_ids[]" required>
                                    <option value="">Select ${labelText}</option>
                                    ${children.map(child => `<option value="${child.id}">${child.name}</option>`).join('')}
                                </select>
                            </div>
                        `;
                        
                        if (assignWrapper) {
                            assignWrapper.insertAdjacentHTML('beforebegin', selectHtml);
                        } else {
                            container.insertAdjacentHTML('beforeend', selectHtml);
                        }
                    }
                } catch (error) {
                    console.error('Failed to load deep hierarchy levels:', error);
                }

                triggerDependentAPIs();
            });
        }

        if (assignSelect) {
            assignSelect.addEventListener('change', function () {
                if (!statusSelect) return;
                @if(isset($complaint))
                    if (this.value) {
                        statusSelect.tomselect ? statusSelect.tomselect.setValue(ASSIGNED_STATUS_ID) : statusSelect.value = ASSIGNED_STATUS_ID;
                    } else {
                        statusSelect.tomselect ? statusSelect.tomselect.setValue(UNASSIGNED_STATUS_ID) : statusSelect.value = UNASSIGNED_STATUS_ID;
                    }
                @endif
            });
        }

        async function buildHierarchyChain() {
            if (!savedVerticals.length) return;

            let currentSelect = container.querySelector('.hierarchy-select[data-level="1"]');
            let currentLevel = 1;

            for (let i = 0; i < savedVerticals.length; i++) {
                const verticalId = savedVerticals[i];

                if (currentSelect) {
                    currentSelect.value = verticalId;
                }

                if (i < savedVerticals.length - 1) {
                    try {
                        const response = await fetch(`/api/get-child-verticals?parent_id=${verticalId}`);
                        const children = await response.json();

                        if (children && children.length > 0) {
                            currentLevel = currentLevel + 1;
                            let labelText = 'Sub-Category';

                            const selectHtml = `
                                <div class="col-md-4 mb-3 hierarchy-wrapper">
                                    <label class="form-label">${labelText}</label>
                                    <select class="form-select hierarchy-select" data-level="${currentLevel}" name="vertical_ids[]" required>
                                        <option value="">Select ${labelText}</option>
                                        ${children.map(child => `<option value="${child.id}">${child.name}</option>`).join('')}
                                    </select>
                                </div>
                            `;

                            if (assignWrapper) {
                                assignWrapper.insertAdjacentHTML('beforebegin', selectHtml);
                            } else {
                                container.insertAdjacentHTML('beforeend', selectHtml);
                            }

                            currentSelect = container.querySelector(`.hierarchy-select[data-level="${currentLevel}"]`);
                        }
                    } catch (error) {
                        console.error('Failed to load child verticals:', error);
                        break;
                    }
                }
            }

            triggerDependentAPIs(selectedUser);
        }

        await buildHierarchyChain();
    });
</script>
@endsection