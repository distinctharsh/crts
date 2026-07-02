@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Bulk Import Complaints</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Download Format</h5>
                </div>
                <div class="card-body">
                    <p class="mb-3">Download the Excel format file to fill complaint data before uploading:</p>
                    <a href="{{ route('complaints.download-format') }}" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i> Download Format
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Upload Complaints</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('complaints.bulk-import-store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="excel_file" class="form-label">Excel File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('excel_file') is-invalid @enderror" 
                                   id="excel_file" name="excel_file" accept=".xlsx,.xls" required>
                            @error('excel_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Supported formats: .xlsx, .xls</small>
                        </div>

                        <div class="alert alert-info">
                            <strong>Instructions:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Download the format file first</li>
                                <li>Fill in all required fields marked with *</li>
                                <li>Ensure vertical IDs match the masters data</li>
                                <li>Ensure section IDs match the masters data</li>
                                <li>Ensure network type IDs match the masters data</li>
                                <li>Upload the filled file</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i> Upload & Import
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
@endpush
