@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-plus me-2"></i>Add New Leave Record
                </h1>
                <p class="page-subtitle">Record a new leave request with complete details for employee tracking and management</p>
            </div>

            <!-- Main Form -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        <span>Leave Record Details</span>
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.employee-leave.store') }}" id="leaveForm">
                        @csrf

                        <!-- Employee Selection -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="employee_id" class="form-label fw-bold">
                                        <i class="fas fa-user me-2"></i>Select Employee <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('employee_id') is-invalid @enderror"
                                            id="employee_id"
                                            name="employee_id"
                                            required>
                                        <option value="">Choose an employee...</option>
                                        @foreach(\App\Models\Employee::all() as $employee)
                                            <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->employee_id }} - {{ $employee->full_name }} ({{ $employee->position }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted mt-2">
                                        <i class="fas fa-info-circle me-1"></i>Select the employee for whom you are recording leave
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Type Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="leave_type" class="form-label fw-bold">
                                        <i class="fas fa-tag me-2"></i>Leave Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('leave_type') is-invalid @enderror"
                                            id="leave_type"
                                            name="leave_type"
                                            required>
                                        <option value="">Select leave type...</option>
                                        @foreach(\App\Models\Leave::getLeaveTypes() as $key => $value)
                                            <option value="{{ $key }}" {{ old('leave_type') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('leave_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-flag me-2"></i>Additional Options
                                    </label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   id="is_half_day"
                                                   name="is_half_day"
                                                   value="1"
                                                   {{ old('is_half_day') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_half_day">
                                                <i class="fas fa-clock-half me-1"></i>Half Day Leave
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input emergency-check"
                                                   type="checkbox"
                                                   id="emergency"
                                                   name="emergency"
                                                   value="1"
                                                   {{ old('emergency') ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger" for="emergency">
                                                <i class="fas fa-exclamation-triangle me-1"></i>Emergency Leave
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Selection -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label fw-bold">
                                        <i class="fas fa-calendar-plus me-2"></i>Start Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control form-control-lg @error('start_date') is-invalid @enderror"
                                           id="start_date"
                                           name="start_date"
                                           value="{{ old('start_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           onchange="updateDaysCount()"
                                           required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label fw-bold">
                                        <i class="fas fa-calendar-minus me-2"></i>End Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date"
                                           class="form-control form-control-lg @error('end_date') is-invalid @enderror"
                                           id="end_date"
                                           name="end_date"
                                           value="{{ old('end_date') }}"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           onchange="updateDaysCount()"
                                           required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Days Count Display -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info" id="daysPreview">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Duration Preview:</strong> Select start and end dates to see calculated days
                                </div>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason" class="form-label fw-bold">
                                        <i class="fas fa-file-text me-2"></i>Reason for Leave <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror"
                                              id="reason"
                                              name="reason"
                                              rows="4"
                                              placeholder="Please provide detailed reason for the leave request..."
                                              maxlength="500"
                                              required>{{ old('reason') }}</textarea>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Provide detailed reason to ensure proper approval and documentation
                                        </small>
                                        <small class="text-count">
                                            <span id="reasonCount">0</span>/500 characters
                                        </small>
                                    </div>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="admin_notes" class="form-label fw-bold">
                                        <i class="fas fa-sticky-note me-2"></i>HR Notes (Optional)
                                    </label>
                                    <textarea class="form-control"
                                              id="admin_notes"
                                              name="admin_notes"
                                              rows="3"
                                              placeholder="Additional notes from HR about this leave record..."
                                              maxlength="500">{{ old('admin_notes') }}</textarea>
                                    <small class="text-muted mt-1">
                                        <i class="fas fa-info-circle me-1"></i>Internal HR notes for record keeping
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary" onclick="clearForm()">
                                            <i class="fas fa-undo me-1"></i>Clear Form
                                        </button>
                                        <button type="button" class="btn btn-outline-info" onclick="validateDates()">
                                            <i class="fas fa-search me-1"></i>Validate Dates
                                        </button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.employee-leave.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Back to List
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-2"></i>Save Leave Record
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date inputs with minimum validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const reasonTextarea = document.getElementById('reason');
    const adminNotesTextarea = document.getElementById('admin_notes');

    // Set minimum dates (next day onwards)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const minDate = tomorrow.toISOString().split('T')[0];
    startDateInput.min = minDate;
    endDateInput.min = minDate;

    // Update character count for reason
    reasonTextarea.addEventListener('input', updateReasonCount);

    // Auto-calculate end date when start date changes
    startDateInput.addEventListener('change', function() {
        if (endDateInput.value === '') {
            endDateInput.value = this.value;
            updateDaysCount();
        }
    });

    // Validate form before submission
    document.getElementById('leaveForm').addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
        }
    });

    // Initialize with current values
    updateDaysCount();
    updateReasonCount();
});

function updateDaysCount() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const isHalfDay = document.getElementById('is_half_day').checked;
    const previewDiv = document.getElementById('daysPreview');

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        // Validate date order
        if (start > end) {
            previewDiv.className = 'alert alert-warning';
            previewDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i><strong>Invalid Dates:</strong> Start date cannot be after end date';
            return;
        }

        let daysCount = isHalfDay ? 0.5 : Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;

        previewDiv.className = 'alert alert-info';
        previewDiv.innerHTML = `
            <i class="fas fa-info-circle me-2"></i>
            <strong>Duration Preview:</strong>
            ${daysCount} ${isHalfDay ? 'half day' : (daysCount === 1 ? 'day' : 'days')}
            from ${start.toLocaleDateString()} to ${end.toLocaleDateString()}
        `;
    }
}

function updateReasonCount() {
    const textarea = document.getElementById('reason');
    const counter = document.getElementById('reasonCount');
    counter.textContent = textarea.value.length;
    counter.className = textarea.value.length > 450 ? 'text-danger' : 'text-muted';
}

function validateForm() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);

        if (start > end) {
            alert('Start date cannot be after end date!');
            return false;
        }

        if (start < new Date(new Date().setDate(new Date().getDate() + 1))) {
            alert('Leave start date must be at least 1 day in the future!');
            return false;
        }
    }

    return true;
}

function validateDates() {
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;

    if (!startDate || !endDate) {
        alert('Please select both start and end dates first.');
        return;
    }

    const start = new Date(startDate);
    const end = new Date(endDate);

    if (start > end) {
        alert('❌ Error: Start date cannot be after end date');
        document.getElementById('end_date').focus();
        return;
    }

    const daysDiff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
    alert(`✅ Valid dates selected!\nDuration: ${daysDiff} days\nFrom: ${start.toLocaleDateString()}\nTo: ${end.toLocaleDateString()}`);
}

function clearForm() {
    if (confirm('Are you sure you want to clear all form data?')) {
        document.getElementById('leaveForm').reset();
        updateDaysCount();
        updateReasonCount();
        document.getElementById('employee_id').focus();
    }
}

// Emergency leave checkbox styling
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('emergency-check')) {
        const label = e.target.parentElement;
        if (e.target.checked) {
            label.classList.add('text-danger', 'fw-bold');
        } else {
            label.classList.remove('text-danger', 'fw-bold');
        }
    }
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0069d9 100%) !important;
    color: white;
}

.form-label {
    margin-bottom: 0.75rem;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(78, 180, 230, 0.25);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.alert-info {
    border-left: 4px solid var(--primary-color);
}

.text-count {
    font-size: 0.9rem;
}

.emergency-check:checked ~ .form-check-label {
    background: linear-gradient(135deg, #dc3545 0%, #ff6b7d 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: bold;
}

.btn-outline-primary:hover {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: white;
}
</style>
@endsection
