@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Employee Accessories Management Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-tools me-2"></i>Employee Accessories Management
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
                            <a href="{{ route('admin.accessories.create') }}" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Allocate Accessory
                            </a>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search accessories...">
                                <button class="btn btn-outline-custom">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @forelse($accessories as $accessory)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 accessory-card">
                                <div class="card-body text-center">
                                    @if($accessory->photo)
                                        <img src="{{ asset('storage/' . $accessory->photo) }}" alt="Accessory Photo" class="rounded mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                            <i class="fas fa-tools fa-2x text-muted"></i>
                                        </div>
                                    @endif

                                    <h5 class="card-title mb-1">{{ $accessory->accessory_name }}</h5>
                                    <p class="text-muted mb-2">{{ $accessory->employee->full_name }}</p>
                                    <p class="text-muted small mb-2">ID: {{ $accessory->employee->employee_id }}</p>

                                    <div class="accessory-details mb-3">
                                        {!! $accessory->status_badge !!}
                                        @if($accessory->value)
                                        <div class="mt-2">
                                            <small class="text-muted">Value: {{ $accessory->formatted_value }}</small>
                                        </div>
                                        @endif
                                        <div class="mt-1">
                                            <small class="text-muted">Allocated: {{ $accessory->allocation_date->format('M d, Y') }}</small>
                                        </div>
                                        @if($accessory->return_date)
                                        <div>
                                            <small class="text-muted">Returned: {{ $accessory->return_date->format('M d, Y') }}</small>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.accessories.show', $accessory->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accessories.edit', $accessory->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.accessories.destroy', $accessory->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this accessory allocation?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-tools fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Accessories Found</h4>
                            <p class="text-muted mb-4">No accessories have been allocated to employees yet.</p>
                            <a href="{{ route('admin.accessories.create') }}" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Allocate First Accessory
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
.accessory-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.accessory-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.accessory-card .card-body {
    padding: 2rem 1.5rem;
}

.accessory-details .badge {
    font-size: 0.75rem;
}

.btn-group .btn {
    margin: 0 2px;
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
    const cards = document.querySelectorAll('.accessory-card');

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endsection
