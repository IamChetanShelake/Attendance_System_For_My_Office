@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Salary Management Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-2"></i>Salary/Accountability Management
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="{{ route('admin.accountability.create') }}" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Add Salary Details
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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-hashtag me-2"></i>Employee ID</th>
                                    <th><i class="fas fa-camera me-2"></i>Photo</th>
                                    <th><i class="fas fa-user me-2"></i>Employee Name</th>
                                    <th><i class="fas fa-building me-2"></i>Department</th>
                                    <th><i class="fas fa-money-bill-wave me-2"></i>Salary Amount</th>
                                    <th><i class="fas fa-id-card me-2"></i>Aadhaar</th>
                                    <th><i class="fas fa-university me-2"></i>Bank Name</th>
                                    <th><i class="fas fa-calendar me-2"></i>Effective Date</th>
                                    <th><i class="fas fa-circle me-2"></i>Status</th>
                                    <th><i class="fas fa-ellipsis-h me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salaries as $salary)
                                <tr>
                                    <td>{{ $salary->employee->employee_id }}</td>
                                    <td>
                                        @if($salary->employee->photo)
                                            <img src="{{ asset('storage/' . $salary->employee->photo) }}" alt="Employee Photo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $salary->employee->full_name }}</td>
                                    <td>{{ $salary->employee->department }}</td>
                                    <td>₹{{ number_format($salary->salary_amount, 2) }}</td>
                                    <td>
                                        @if($salary->aadhaar_number)
                                            <span class="font-monospace">{{ $salary->aadhaar_number }}</span>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                    <td>{{ $salary->bank_name }}</td>
                                    <td>{{ $salary->effective_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $salary->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($salary->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.accountability.show', $salary->id) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.accountability.edit', $salary->id) }}" class="btn btn-sm btn-outline-custom me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.accountability.destroy', $salary->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this salary record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">No Salary Records Found</h5>
                                        <p class="text-muted mb-4">Start by adding salary details for your employees</p>
                                        <a href="{{ route('admin.accountability.create') }}" class="btn btn-custom">
                                            <i class="fas fa-plus me-2"></i>Add Salary Details
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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

document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        if (row.cells.length > 1) { // Skip empty state row
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        }
    });
});
</script>
@endsection
