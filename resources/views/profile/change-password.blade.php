@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">Change Password</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ url('/profile/change-password') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Old Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('old_password') is-invalid @enderror" id="old_password" name="old_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#old_password" tabindex="-1">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon0"></i>
                                </button>
                            </div>
                            @error('old_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autofocus pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{6,}$" title="Password must be at least 6 characters, contain one uppercase letter, one lowercase letter, one digit, and one special character (!@#$%^&*).">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password" tabindex="-1">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon1"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Password must be at least 6 characters, contain one uppercase letter, one lowercase letter, one digit, and one special character (!@#$%^&*).</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="#password_confirmation" tabindex="-1">
                                    <i class="bi bi-eye-slash" id="togglePasswordIcon2"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('data-target'));
            const icon = this.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                target.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });
</script>
@endpush

@push('style')
<link rel="stylesheet" href="{{ asset('css/bootstrap-icons.css') }}">
@endpush 