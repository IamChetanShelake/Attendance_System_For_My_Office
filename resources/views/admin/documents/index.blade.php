@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Documents Management Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-file-alt me-2"></i>Documents Management
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

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="{{ route('admin.documents.create') }}" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Upload Documents
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search employees...">
                                <button class="btn btn-outline-custom">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($employeesWithDocuments as $employee)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 employee-document-card">
                                <div class="card-body text-center">
                                    @if($employee->photo)
                                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Employee Photo" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="fas fa-user fa-2x text-muted"></i>
                                        </div>
                                    @endif

                                    <h5 class="card-title mb-1">{{ $employee->full_name }}</h5>
                                    <p class="text-muted mb-2">{{ $employee->position }}</p>
                                    <p class="text-muted small mb-3">ID: {{ $employee->employee_id }}</p>

                                    <div class="document-count mb-3">
                                        <span class="badge bg-primary fs-6">
                                            <i class="fas fa-file me-1"></i>{{ $employee->documents_count }} Document{{ $employee->documents_count !== 1 ? 's' : '' }}
                                        </span>
                                    </div>

                                    <div class="recent-documents mb-3">
                                        @if($employee->documents?->count())
                                            <small class="text-muted">Recent documents:</small>
                                            <ul class="list-unstyled small mt-1">
                                                @foreach($employee->documents->take(2) as $document)
                                                <li class="text-truncate" title="{{ $document->document_name }}">
                                                    <i class="fas {{ $document->file_icon }} me-1 text-primary"></i>
                                                    {{ $document->document_name }}
                                                </li>
                                                @endforeach
                                                @if($employee->documents->count() > 2)
                                                <li class="text-muted">+{{ $employee->documents->count() - 2 }} more...</li>
                                                @endif
                                            </ul>
                                        @endif
                                    </div>

                                    <a href="{{ route('admin.documents.employee', $employee->id) }}" class="btn btn-custom btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Documents
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Documents Found</h4>
                            <p class="text-muted mb-4">No employees have uploaded documents yet.</p>
                            <a href="{{ route('admin.documents.create') }}" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Upload First Document
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.employee-document-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.employee-document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.employee-document-card .card-body {
    padding: 2rem 1.5rem;
}

.document-count .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.recent-documents ul li {
    margin-bottom: 0.25rem;
    padding: 0.125rem 0;
}
</style>

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

document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const cards = document.querySelectorAll('.employee-document-card');

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endsection
