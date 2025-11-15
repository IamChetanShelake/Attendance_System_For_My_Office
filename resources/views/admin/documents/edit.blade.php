@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Edit Document Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i>Edit Document
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.documents.update', $document->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Employee Information (Read-only) -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-user me-2"></i>Employee Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6>{{ $document->employee->full_name }}</h6>
                                            <p class="mb-1"><strong>ID:</strong> {{ $document->employee->employee_id }}</p>
                                            <p class="mb-1"><strong>Department:</strong> {{ $document->employee->department }}</p>
                                            <p class="mb-0"><strong>Position:</strong> {{ $document->employee->position }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Document Information -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Current Document Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-info">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas {{ $document->file_icon }} fa-2x text-primary me-3"></i>
                                                <div>
                                                    <h6 class="mb-1">{{ $document->document_name }}</h6>
                                                    <small class="text-muted">{{ $document->original_filename }}</small>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <small class="text-muted">Size</small>
                                                    <br><strong>{{ $document->file_size_formatted }}</strong>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Type</small>
                                                    <br><strong>{{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }}</strong>
                                                </div>
                                                <div class="col-4">
                                                    <small class="text-muted">Uploaded</small>
                                                    <br><strong>{{ $document->created_at->format('d M Y') }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Document Details -->
                        <h5 class="mb-3"><i class="fas fa-edit me-2"></i>Update Document Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Document Name <span class="text-danger">*</span></label>
                                <input type="text" name="document_name" class="form-control @error('document_name') is-invalid @enderror" value="{{ old('document_name', $document->document_name) }}" required>
                                @error('document_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Replace File (Optional)</label>
                                <input type="file" name="document" class="form-control @error('document') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.ppt,.pptx,.txt">
                                <div class="form-text">Leave empty to keep the current file. Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX, PPT, PPTX, TXT (Max: 10MB)</div>
                                @error('document')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Add any additional notes about this document...">{{ old('description', $document->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- File Replacement Warning -->
                        <div class="alert alert-warning mb-4">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                            <ul class="mb-0">
                                <li>If you upload a new file, the current file will be permanently replaced</li>
                                <li>The original file will be deleted and cannot be recovered</li>
                                <li>Make sure the new file is correct before uploading</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-custom btn-lg me-2">
                                <i class="fas fa-save me-2"></i>Update Document
                            </button>
                            <a href="{{ route('admin.documents.show', $document->id) }}" class="btn btn-outline-secondary btn-lg me-2">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
