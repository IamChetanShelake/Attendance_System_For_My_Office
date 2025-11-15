@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-key me-2"></i>Generate Login Credentials
                </h1>
                <p class="page-subtitle">Create login credentials for employees to access the mobile application</p>
            </div>

            <!-- Form Card -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Select Employees</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employee-logins.store') }}" method="POST">
                        @csrf

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>How it works:</strong> Select one or more employees below. Login credentials will be automatically generated with secure passwords and company email addresses.
                        </div>

                        @if($availableEmployees->count() > 0)
                            <!-- Employee Selection -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Available Employees ({{ $availableEmployees->count() }})</h6>
                                <div class="row">
                                    @foreach($availableEmployees as $employee)
                                        <div class="col-lg-4 col-md-6 mb-3">
                                            <div class="card employee-card h-100 border" data-employee-id="{{ $employee->id }}">
                                                <div class="card-body d-flex align-items-start">
                                                    <div class="form-check me-3 mt-1">
                                                        <input class="form-check-input employee-checkbox"
                                                               type="checkbox"
                                                               name="employee_ids[]"
                                                               value="{{ $employee->id }}"
                                                               id="employee-{{ $employee->id }}">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex align-items-center mb-2">
                                                            @if($employee->photo)
                                                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                                                     class="rounded-circle me-3"
                                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                                     style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                                                                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <h6 class="mb-1">{{ $employee->full_name }}</h6>
                                                                <p class="mb-1 text-muted">{{ $employee->position }}</p>
                                                                <small class="text-muted">ID: {{ $employee->employee_id }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="employee-details" style="display: none;">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small class="text-muted">Department:</small><br>
                                                                    <span class="badge bg-light text-dark">{{ $employee->department ?? 'N/A' }}</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted">Type:</small><br>
                                                                    <span class="badge bg-light text-dark">{{ $employee->type ?? 'N/A' }}</span>
                                                                </div>
                                                            </div>
                                                            <hr class="my-2">
                                                            <small class="text-muted">
                                                                <strong>Generated Email:</strong> {{ strtolower($employee->first_name) . '.' . strtolower($employee->last_name) . '@techmetworks.com' }}
                                                            </small>
                                                            <br>
                                                            <small class="text-muted">
                                                                <strong>Password:</strong> <span class="text-primary">{{ $employee->first_name }}#{{ $employee->employee_id }}</span>
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary toggle-details" data-employee-id="{{ $employee->id }}">
                                                        <i class="fas fa-chevron-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Selection Summary -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Selection Summary</h6>
                                            <p class="mb-0" id="selection-summary">No employees selected</p>
                                        </div>
                                        <div class="text-end">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="select-all">
                                                    <i class="fas fa-check-square me-1"></i>Select All
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-all">
                                                    <i class="fas fa-square me-1"></i>Clear All
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.employee-logins.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Login Management
                                </a>
                                <button type="submit" class="btn btn-success" id="generate-btn" disabled>
                                    <i class="fas fa-key me-2"></i>Generate Login Credentials
                                </button>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">All Employees Have Login Credentials</h4>
                                <p class="text-muted mb-4">All registered employees already have login credentials generated.</p>
                                <a href="{{ route('admin.employee-logins.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Login Management
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

@if($availableEmployees->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const selectAllBtn = document.getElementById('select-all');
    const clearAllBtn = document.getElementById('clear-all');
    const selectionSummary = document.getElementById('selection-summary');
    const generateBtn = document.getElementById('generate-btn');

    // Toggle employee details
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
            const employeeId = this.getAttribute('data-employee-id');
            const card = this.closest('.employee-card');
            const details = card.querySelector('.employee-details');
            const icon = this.querySelector('i');

            if (details.style.display === 'none') {
                details.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                details.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        });
    });

    // Select all employees
    selectAllBtn.addEventListener('click', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelection();
    });

    // Clear all selections
    clearAllBtn.addEventListener('click', function() {
        employeeCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSelection();
    });

    // Update selection when checkboxes change
    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    function updateSelection() {
        const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
        const count = checkedBoxes.length;

        if (count === 0) {
            selectionSummary.textContent = 'No employees selected';
            generateBtn.disabled = true;
        } else if (count === 1) {
            selectionSummary.textContent = '1 employee selected';
            generateBtn.disabled = false;
        } else {
            selectionSummary.textContent = `${count} employees selected`;
            generateBtn.disabled = false;
        }

        // Update generate button text
        const baseText = 'Generate Login Credentials';
        if (count > 0) {
            generateBtn.innerHTML = `<i class="fas fa-key me-2"></i>${baseText} (${count})`;
        } else {
            generateBtn.innerHTML = `<i class="fas fa-key me-2"></i>${baseText}`;
        }

        // Highlight selected cards
        document.querySelectorAll('.employee-card').forEach(card => {
            const checkbox = card.querySelector('.employee-checkbox');
            if (checkbox.checked) {
                card.classList.add('border-primary', 'bg-light');
            } else {
                card.classList.remove('border-primary', 'bg-light');
            }
        });
    }

    // Initialize
    updateSelection();
});
</script>
@endif

<style>
.employee-card {
    cursor: pointer;
    transition: all 0.3s ease;
}

.employee-card:hover {
    border-color: #0d6efd !important;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.employee-card .employee-details {
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid #dee2e6;
}

.toggle-details {
    border: none;
    background: none;
    color: #6c757d;
}

.toggle-details:hover {
    color: #0d6efd;
}

.card.bg-light {
    border-color: #0d6efd !important;
}

#generate-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
</style>
@endsection
