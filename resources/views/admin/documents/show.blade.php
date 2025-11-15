@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Document Details Section -->
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-alt me-2"></i>Document Details</span>
                    <div>
                        <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-outline-success me-2">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                        <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-outline-custom me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Document Preview/Icon -->
                        <div class="col-md-4 text-center mb-4">
                            <div class="document-preview">
                                @if(in_array(strtolower(pathinfo($document->original_filename, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset('storage/' . $document->document_path) }}" alt="Document Preview" class="img-fluid rounded shadow" style="max-height: 300px;">
                                @else
                                    <div class="document-icon-placeholder">
                                        <i class="fas {{ $document->file_icon }} fa-5x text-primary mb-3"></i>
                                        <p class="text-muted">{{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }} File</p>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-download me-2"></i>Download File
                                </a>
                            </div>
                        </div>

                        <!-- Document Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Document Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Document ID:</td>
                                            <td>{{ $document->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Document Name:</td>
                                            <td>{{ $document->document_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">File Name:</td>
                                            <td><code>{{ $document->original_filename }}</code></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">File Type:</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">File Size:</td>
                                            <td>{{ $document->file_size_formatted }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">MIME Type:</td>
                                            <td><small class="text-muted">{{ $document->mime_type }}</small></td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Employee & Upload Information -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Employee & Upload Details</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Employee:</td>
                                            <td>
                                                <a href="{{ route('admin.employees.show', $document->employee->id) }}" class="text-decoration-none">
                                                    {{ $document->employee->full_name }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Employee ID:</td>
                                            <td>{{ $document->employee->employee_id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Department:</td>
                                            <td>{{ $document->employee->department }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Uploaded On:</td>
                                            <td>{{ $document->created_at->format('d M Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Updated:</td>
                                            <td>{{ $document->updated_at->format('d M Y \a\t h:i A') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($document->description)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-sticky-note me-2"></i>Description</h5>
                                    <div class="alert alert-light border">
                                        {{ $document->description }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- File Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-cogs me-2"></i>Actions</h5>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-success">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                        <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i>Edit Details
                                        </a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this document?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. The file will be permanently removed from the system.
                </div>
                <p class="mb-0"><strong>Document:</strong> {{ $document->document_name }}</p>
                <p class="mb-0"><strong>File:</strong> {{ $document->original_filename }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Document
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
