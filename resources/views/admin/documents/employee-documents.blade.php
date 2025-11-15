@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Employee Documents Section -->
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-alt me-2"></i>Documents for {{ $employee->full_name }}</span>
                    <div>
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-outline-custom me-2">
                            <i class="fas fa-plus me-1"></i>Upload More
                        </a>
                        <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to Employees
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Employee Info -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-2 text-center">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}" alt="Employee Photo" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                    <i class="fas fa-user fa-2x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-10">
                                            <h4 class="mb-1">{{ $employee->full_name }}</h4>
                                            <p class="text-muted mb-1">{{ $employee->position }} - {{ $employee->department }}</p>
                                            <p class="text-muted small mb-0">Employee ID: {{ $employee->employee_id }}</p>
                                            <div class="mt-2">
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-file me-1"></i>{{ $employee->documents->count() }} Document{{ $employee->documents->count() !== 1 ? 's' : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents List -->
                    @if($employee->documents->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search documents...">
                                <button class="btn btn-outline-custom">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                    <th><i class="fas fa-file me-2"></i>Document</th>
                                    <th><i class="fas fa-tag me-2"></i>Type</th>
                                    <th><i class="fas fa-weight me-2"></i>Size</th>
                                    <th><i class="fas fa-calendar me-2"></i>Uploaded</th>
                                    <th><i class="fas fa-ellipsis-h me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->documents as $document)
                                <tr>
                                    <td>{{ $document->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas {{ $document->file_icon }} fa-lg me-3 text-primary"></i>
                                            <div>
                                                <strong>{{ $document->document_name }}</strong>
                                                @if($document->description)
                                                <br><small class="text-muted">{{ Str::limit($document->description, 50) }}</small>
                                                @endif
                                                <br><small class="text-muted">{{ $document->original_filename }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ strtoupper(pathinfo($document->original_filename, PATHINFO_EXTENSION)) }}
                                        </span>
                                    </td>
                                    <td>{{ $document->file_size_formatted }}</td>
                                    <td>{{ $document->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.documents.show', $document->id) }}" class="btn btn-sm btn-outline-info me-1" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.documents.download', $document->id) }}" class="btn btn-sm btn-outline-success me-1" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('admin.documents.edit', $document->id) }}" class="btn btn-sm btn-outline-custom me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.documents.destroy', $document->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this document?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted mb-3">No Documents Found</h4>
                        <p class="text-muted mb-4">This employee doesn't have any documents uploaded yet.</p>
                        <a href="{{ route('admin.documents.create') }}" class="btn btn-custom">
                            <i class="fas fa-plus me-2"></i>Upload First Document
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Auto-dismiss alerts after 2 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 2000); // 2000ms = 2 seconds
    });
});

document.getElementById('searchInput')?.addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endsection
