@extends('layouts.app')

@section('content')
@php
$breadcrumbs = [
['label' => 'Dashboard', 'url' => route('dashboard')],
['label' => 'Users', 'url' => route('users.index')],
];
@endphp

@include('layouts.partials.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

<div class="container-xxl">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>User Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userModal">
                    <i class="bi bi-plus-lg"></i> Add User
                </button>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="">
                <table id="usersTable" class="table table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>S.No.</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Verticals</th>
                            <th>Created At</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr @if($user->deleted_at) style="background:#f8d7da;" @endif>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>
                                <span class="badge 
                                    @switch($user->role->slug ?? '')
                                        @case('admin') bg-danger @break
                                        @case('manager') bg-primary @break
                                        @case('vm') bg-info @break
                                        @case('nfo') bg-warning text-dark @break
                                        @default bg-secondary
                                    @endswitch
                                ">
                                    {{ $user->role->name ?? 'No Role' }}
                                </span>
                            </td>
                            <td>
                                @if($user->verticals && $user->verticals->count())
                                {{ $user->verticals->pluck('name')->implode(', ') }}
                                @else
                                -
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($user->deleted_at)
                                <span class="badge bg-danger">Deleted</span>
                                @else
                                <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($user->deleted_at)
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button class="btn btn-success btn-sm" type="submit" title="Restore"><i class="bi bi-arrow-counterclockwise"></i></button>
                                    </form>
                                    @else
                                    <button type="button" class="btn btn-primary btn-sm mr-5 editUserBtn" data-user='@json($user)'><i class="fas fa-pen"></i></button>
                                    @if($user->id !== auth()->user()->id && $user->role->slug !== 'manager')
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" style="margin-left: 4px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- DataTables handles pagination/info -->
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{ old('user_id') ? url('users/' . old('user_id')) : route('users.store') }}" id="userForm">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', '') }}">
                    <input type="hidden" name="_method" id="form_method" value="{{ old('user_id') ? 'PUT' : 'POST' }}">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required autofocus maxlength="50">
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" required maxlength="60">
                        @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3" id="defaultPasswordNote">
                        <span class="text-info small">Default password will be assigned: <b>Welcome@123</b></span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" minlength="6">
                            <button class="btn btn-outline-secondary" type="button" id="setDefaultPasswordBtn">Set Default Password</button>
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password" tabindex="-1">
                                <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        <small id="passwordHelpText" class="form-text text-muted">(leave blank to keep current)</small>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation" tabindex="-1">
                                <i class="bi bi-eye-slash" id="togglePasswordIconConfirm"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                            <option value="">Select a role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}" data-slug="{{ $role->slug }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3" id="verticalBox" style="display: {{ (old('role_id') && (App\Models\Role::find(old('role_id'))->slug == 'vm' || App\Models\Role::find(old('role_id'))->slug == 'nfo')) ? 'block' : 'none' }};">
                        <label for="vertical_ids" class="form-label">Verticals</label>
                        <select name="vertical_ids[]" id="vertical_ids" class="form-control" multiple>
                            @foreach($verticals as $vertical)
                            <option value="{{ $vertical->id }}" {{ collect(old('vertical_ids', []))->contains($vertical->id) ? 'selected' : '' }}>{{ $vertical->name }}</option>
                            @endforeach
                        </select>
                        @error('vertical_ids')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" id="userSubmitBtn">
                            <span id="userBtnText">{{ old('user_id') ? 'Update' : 'Create' }}</span>
                            <span id="userBtnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </form>
                <div id="formErrors" class="alert alert-danger mt-3 d-none">
                    <ul class="mb-0" id="formErrorsList"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
<style>
    /* Right-align DataTables pagination */
    div.dataTables_wrapper div.dataTables_paginate {
        text-align: right !important;
        float: right !important;
    }
</style>
@endpush
@push('scripts')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-2"Bfl>rtip',
        buttons: [
            {
                    extend: 'copy',
                    text: '<i class="bi bi-clipboard"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Copy'
                },
                {
                    extend: 'csv',
                    text: '<i class="bi bi-filetype-csv"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Export as CSV'
                },
                {
                    extend: 'excel',
                    text: '<i class="bi bi-file-earmark-excel"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Export as Excel'
                },
                {
                    extend: 'pdf',
                    text: '<i class="bi bi-file-earmark-pdf"></i>',
                    className: 'btn btn-light btn-sm me-1',
                    titleAttr: 'Export as PDF'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i>',
                    className: 'btn btn-light btn-sm',
                    titleAttr: 'Print'
                }
            ]
        });

        // Password validation for Create/Edit User Modal
        $('#userForm').on('submit', function(e) {
            var isEdit = $('#user_id').val() !== '';
            if (isEdit) {
                var password = $('#password').val();
                var pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$/;
                if (password && !pattern.test(password)) {
                    alert('Password must be at least 6 characters, contain one uppercase letter, one lowercase letter, one digit, and one special character (!@#$%^&*).');
                    $('#password').focus();
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Show modal for create
        $(document).on('click', '[data-bs-target="#userModal"]', function() {
            clearUserForm();
            $('#userModalLabel').text('Create User');
            $('#userBtnText').text('Create');
        $('#userForm').attr('action', '{{ route('users.store') }}');
            $('#form_method').val('POST');
            $('#user_id').val('');
            $('#defaultPasswordNote').show();
            $('#formErrors').addClass('d-none');
        });

        // Show modal for edit
        $(document).on('click', '.editUserBtn', function() {
            var user = $(this).data('user');
            clearUserForm();
            $('#userModal').modal('show');
            $('#userModalLabel').text('Edit User');
            $('#userBtnText').text('Update');
            $('#userForm').attr('action', '/users/' + user.id);
            $('#form_method').val('PUT');
            $('#user_id').val(user.id);
            $('#username').val(user.username);
            $('#full_name').val(user.full_name);
            $('#role_id').val(user.role_id);
            $('#defaultPasswordNote').hide();
            $('#password').removeAttr('required');
            $('#passwordHelpText').text(' (leave blank to keep current)');
            // Set verticals
            if (user.verticals && user.verticals.length > 0) {
            var verticalIds = user.verticals.map(function(v) { return v.id; });
                $('#vertical_ids').val(verticalIds);
            } else {
                $('#vertical_ids').val([]);
            }
            $('#vertical_ids').trigger('change');
            // Show/hide vertical box based on role slug
            var selectedRoleSlug = $('#role_id option:selected').data('slug');
            if (selectedRoleSlug === 'vm' || selectedRoleSlug === 'nfo') {
                $('#verticalBox').show();
            } else {
                $('#verticalBox').hide();
            }
            $('#formErrors').addClass('d-none');
        });

        // Role change show/hide verticals (use slug)
        $('#role_id').on('change', function() {
            var selectedRoleSlug = $('#role_id option:selected').data('slug');
            if (selectedRoleSlug === 'vm' || selectedRoleSlug === 'nfo') {
                $('#verticalBox').show();
            } else {
                $('#verticalBox').hide();
                $('#vertical_ids').val([]).trigger('change');
            }
        });

        // Clear form on modal close
        $('#userModal').on('hidden.bs.modal', function () {
            clearUserForm();
        });

        function clearUserForm() {
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#form_method').val('POST');
        $('#userForm').attr('action', '{{ route('users.store') }}');
            $('#userBtnText').text('Create');
            $('#userModalLabel').text('Create User');
            $('#defaultPasswordNote').show();
            $('#formErrors').addClass('d-none');
        }

        $('#setDefaultPasswordBtn').on('click', function() {
            $('#password').val('Welcome@123');
            $('#password_confirmation').val('Welcome@123');
        });

        // Toggle password visibility for password and confirmation fields
        $(document).on('click', '.toggle-password', function() {
            var target = $($(this).data('target'));
            var icon = $(this).find('i');
            if (target.attr('type') === 'password') {
                target.attr('type', 'text');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            } else {
                target.attr('type', 'password');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            }
        });
    });
</script>
@endpush