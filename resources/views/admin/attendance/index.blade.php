@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-clock me-2"></i>Daily Attendance - QR Code Punch
                </h1>
                <p class="page-subtitle">Click punch in/out buttons to generate QR codes for employees to scan via mobile app</p>
            </div>

            <!-- Today's Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>{{ $employees->count() }}</h3>
                        <p>Total Employees</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h3 id="punched-in-count">{{ $punchedInCount }}</h3>
                        <p>Punched In Today</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <h3 id="punched-out-count">{{ $punchedOutCount }}</h3>
                        <p>Punched Out Today</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 id="current-time">{{ now()->format('H:i') }}</h3>
                        <p>Current Time</p>
                    </div>
                </div>
            </div>

            <!-- Employee Attendance Table -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Employee Attendance - {{ now()->format('l, F j, Y') }}</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-light btn-sm" onclick="refreshPage()">
                            <i class="fas fa-sync-alt me-1"></i>Refresh
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="expandAllRows()">
                            <i class="fas fa-expand me-1"></i>Expand All
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @forelse($employees as $employee)
                        <!-- Employee Row -->
                        <div class="employee-row mb-3" data-employee-id="{{ $employee->id }}">
                            <div class="row align-items-center">
                                <!-- Employee Photo & Info -->
                                <div class="col-lg-4 col-md-5">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if($employee->photo)
                                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                                     class="rounded-circle attendance-table-photo"
                                                     alt="{{ $employee->full_name }}">
                                            @else
                                                <div class="rounded-circle attendance-table-photo-placeholder bg-primary text-white d-flex align-items-center justify-content-center">
                                                    <span class="fw-bold">{{ strtoupper(substr($employee->first_name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-truncate">{{ $employee->full_name }}</h6>
                                            <p class="mb-0 text-muted small text-truncate">{{ $employee->position }}</p>
                                            <small class="badge bg-secondary">{{ $employee->employee_id }}</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Indicator -->
                                <div class="col-lg-2 col-md-2 text-center">
                                    <span class="badge employee-status-badge bg-light text-dark fw-normal" id="status-{{ $employee->id }}">
                                        <i class="fas fa-circle text-secondary me-1"></i>Ready
                                    </span>
                                </div>

                                <!-- Punch Actions -->
                                <div class="col-lg-4 col-md-5">
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                                        <button class="btn btn-success btn-sm punch-table-btn punch-in-btn-{{ $employee->id }}{{ isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_in_time ? ' disabled' : '' }}"
                                                data-employee-id="{{ $employee->id }}"
                                                data-action="punch_in"
                                                onclick="generateQRCode({{ $employee->id }}, 'punch_in')"
                                                {{ isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_in_time ? 'disabled' : '' }}>
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            <span class="d-none d-sm-inline">Punch In</span>
                                            @if(isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_in_time)
                                                <small>(Done)</small>
                                            @endif
                                        </button>
                                        <button class="btn btn-danger btn-sm punch-table-btn punch-out-btn-{{ $employee->id }}{{ isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_out_time ? ' disabled' : '' }}"
                                                data-employee-id="{{ $employee->id }}"
                                                data-action="punch_out"
                                                onclick="generateQRCode({{ $employee->id }}, 'punch_out')"
                                                {{ isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_out_time ? 'disabled' : '' }}>
                                            <i class="fas fa-sign-out-alt me-1"></i>
                                            <span class="d-none d-sm-inline">Punch Out</span>
                                            @if(isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_out_time)
                                                <small>(Done)</small>
                                            @endif
                                        </button>
                                    </div>
                                </div>

                                <!-- Last Activity & Actions -->
                                <div class="col-lg-2 col-md-12 text-center text-md-end mt-2 mt-md-0">
                                    <small class="text-muted d-block" id="last-activity-{{ $employee->id }}">
                                        <i class="fas fa-clock me-1"></i>Never
                                    </small>
                                    <button class="btn btn-outline-primary btn-sm ms-2 toggle-details-btn"
                                            onclick="toggleEmployeeDetails({{ $employee->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Expandable Details Row -->
                            <div class="employee-details-row mt-2" id="details-{{ $employee->id }}" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-light">
                                            <div class="card-body">
                                                <h6 class="text-primary mb-2">
                                                    <i class="fas fa-user me-2"></i>Contact Information
                                                </h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Email:</small><br>
                                                        <span class="small">{{ $employee->email ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Phone:</small><br>
                                                        <span class="small">{{ $employee->phone ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                                @if($employee->employee && $employee->wfh_pin)
                                                    <div class="row mt-2">
                                                        <div class="col-12">
                                                            <small class="text-muted">WFH PIN:</small><br>
                                                            <span class="small badge bg-success"><i class="fas fa-lock me-1"></i>PIN Set</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-light">
                                            <div class="card-body">
                                                <h6 class="text-success mb-2">
                                                    <i class="fas fa-clock me-2"></i>Today's Activity
                                                </h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">Check In:</small><br>
                                                        <span class="small text-success" id="today-checkin-{{ $employee->id }}">
                                                            @if(isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_in_time)
                                                                {{ $todayAttendances[$employee->id]->punch_in_time->format('H:i') }}
                                                                @if($todayAttendances[$employee->id]->punch_in_source === 'manual')
                                                                    <small class="badge bg-info ms-1">Manual</small>
                                                                @else
                                                                    <small class="badge bg-primary ms-1">QR</small>
                                                                @endif
                                                            @else
                                                                --:--
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Check Out:</small><br>
                                                        <span class="small text-danger" id="today-checkout-{{ $employee->id }}">
                                                            @if(isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->punch_out_time)
                                                                {{ $todayAttendances[$employee->id]->punch_out_time->format('H:i') }}
                                                                @if($todayAttendances[$employee->id]->punch_out_source === 'manual')
                                                                    <small class="badge bg-info ms-1">Manual</small>
                                                                @else
                                                                    <small class="badge bg-primary ms-1">QR</small>
                                                                @endif
                                                            @else
                                                                --:--
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                @if(isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->worked_hours)
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <small class="text-muted">Worked Hours:</small><br>
                                                        <span class="small fw-bold text-primary">{{ $todayAttendances[$employee->id]->formatted_worked_hours }}</span>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(isset($todayAttendances[$employee->id]) && $todayAttendances[$employee->id]->attendance_type !== 'office')
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <small class="text-muted">Attendance Type:</small><br>
                                                        <span class="small badge bg-secondary">
                                                            @if($todayAttendances[$employee->id]->attendance_type === 'wfh')
                                                                <i class="fas fa-home me-1"></i>Work From Home
                                                            @elseif($todayAttendances[$employee->id]->attendance_type === 'hybrid')
                                                                <i class="fas fa-exchange-alt me-1"></i>Hybrid
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Row Divider -->
                            <hr class="my-3">
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted mb-2">No Employees Found</h4>
                            <p class="text-muted mb-4">Add employees first to start attendance tracking.</p>
                            <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Employee
                            </a>
                        </div>
                    @endforelse

                    <!-- Table Footer with Summary -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Quick Actions:</strong>
                                Click any Punch In/Out button to generate a QR code for that employee to scan with the mobile app.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i>
                                <strong>QR Code Expiry:</strong>
                                Each QR code expires after 10 seconds and can only be scanned by the specific employee.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="qrModalLabel">
                    <i class="fas fa-qrcode me-2"></i>Scan QR Code to <span id="modal-action-text">Punch In</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
<div class="modal-body text-center">
                <div class="row">
                    <div class="col-md-6">
                        <div class="employee-info-section">
                            <div class="text-center mb-3">
                                <img id="modal-employee-photo"
                                     src=""
                                     alt="Employee Photo"
                                     class="rounded-circle"
                                     style="width: 80px; height: 80px; object-fit: cover; display: none;">
                            </div>
                            <h6 id="modal-employee-name">Employee Name</h6>
                            <p class="text-muted mb-2" id="modal-employee-position">Position</p>
                            <small class="badge bg-secondary" id="modal-employee-id">ID: EMP001</small>
                            <hr>
                            <div class="alert alert-info">
                                <strong>Action:</strong> <span id="modal-action-badge" class="badge bg-success">Punch In</span><br>
                                <small>Expires in: <span id="countdown-timer" class="text-danger fw-bold">10s</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="qr-code-section">
                            <div id="qr-code-container" class="mb-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Generating QR code...</span>
                                </div>
                                <p class="text-muted">Generating QR code...</p>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-mobile-alt me-2"></i>
                                <strong>Mobile App Required:</strong> Employees must use the mobile app to scan this QR code.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="generateNewQR()">
                    <i class="fas fa-sync-alt me-1"></i>Generate New QR
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global configuration
let config = {
    qrGenerateUrl: '{{ route("admin.daily-attendance.generate-qr") }}',
    attendanceVerifyUrl: '{{ route("admin.daily-attendance.verify-qr") }}',
    csrfToken: '',
    currentQrIdentifier: null,
    countdownInterval: null,
    statusCheckInterval: null,
    qrModal: null,
    currentEmployeeId: null,
    currentAction: null
};

// Safe initialization function
function initializeAttendance() {
    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            config.csrfToken = csrfMeta.getAttribute('content');
        }

        const modalElement = document.getElementById('qrModal');
        if (modalElement && typeof bootstrap !== 'undefined') {
            config.qrModal = new bootstrap.Modal(modalElement);
        }

        console.log('Attendance system initialized successfully');
        updateCurrentTime();

        // Update time every minute
        setInterval(updateCurrentTime, 60000);
    } catch (error) {
        console.error('Error initializing attendance system:', error);
        // Fallback initialization after a short delay
        setTimeout(initializeAttendance, 100);
    }
}

// Initialize immediately or on DOM ready, whichever comes first
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAttendance);
} else {
    initializeAttendance();
}

// Update current time display
function updateCurrentTime() {
    const now = new Date();
    document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    });
}

// Generate QR code function
async function generateQRCode(employeeId, action) {
    try {
        console.log('Generating QR code for employee:', employeeId, action);

        config.currentEmployeeId = employeeId;
        config.currentAction = action;

        // Show loading in modal first
        document.getElementById('qr-code-container').innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Generating QR code...</span>
            </div>
            <p class="text-muted">Generating QR code...</p>
        `;

        // Show modal
        if (config.qrModal) {
            config.qrModal.show();
        }

        console.log('Making AJAX request to:', config.qrGenerateUrl);

        // Make AJAX request to generate QR
        const response = await fetch(config.qrGenerateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken
            },
            body: JSON.stringify({
                employee_id: employeeId,
                action: action
            })
        });

        console.log('Response received:', response);

        const result = await response.json();
        console.log('Parsed response:', result);

        if (result.success) {
            displayQRCode(result);
            startCountdown(result.expires_in_seconds);

            // Update modal info
            document.getElementById('modal-employee-name').textContent = result.employee_name;
            document.getElementById('modal-action-text').textContent = formatAction(result.action);
            document.getElementById('modal-action-badge').textContent = formatAction(result.action);

            // Update employee photo
            const photoElement = document.getElementById('modal-employee-photo');
            if (photoElement) {
                if (result.employee_photo) {
                    photoElement.src = result.employee_photo;
                    photoElement.style.display = 'block';
                } else {
                    photoElement.style.display = 'none';
                }
            }

            // Update button styling
            updateActionButton(result.employee_name, result.action, 'pending');
        } else {
            throw new Error(result.message || 'Failed to generate QR code');
        }

    } catch (error) {
        console.error('Error generating QR code:', error);
        showAlert('Error generating QR code: ' + error.message, 'danger');
        if (config.qrModal) {
            config.qrModal.hide();
        }
    }
}

// Display QR code in modal
function displayQRCode(data) {
    config.currentQrIdentifier = data.qr_identifier;

    const qrContainer = document.getElementById('qr-code-container');

    // Generate QR code using QR Server API (free online service)
    const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(data.qr_data)}&bgcolor=ffffff&color=000000&format=png`;

qrContainer.innerHTML = `
        <div class="text-center">
            <img src="${qrUrl}"
                 alt="QR Code"
                 class="img-fluid rounded border"
                 style="max-width: 200px; max-height: 200px;">
            <p class="text-success mt-2" id="qr-status-text">
                <i class="fas fa-check-circle me-1"></i>QR Code Ready!
            </p>
            <small class="text-muted">ID: ${data.qr_identifier.substring(0, 20)}...</small>
        </div>
    `;

    // Start polling for attendance status
    startAttendanceStatusCheck();
}

// Start countdown timer
function startCountdown(seconds) {
    let remainingSeconds = seconds;

    if (config.countdownInterval) {
        clearInterval(config.countdownInterval);
    }

    config.countdownInterval = setInterval(() => {
        const timerElement = document.getElementById('countdown-timer');
        if (timerElement) {
            timerElement.textContent = remainingSeconds + 's';
        }

        remainingSeconds--;

        if (remainingSeconds < 0) {
            clearInterval(config.countdownInterval);
            handleQRCodeExpiry();
        }
    }, 1000);
}

// Handle QR code expiry
function handleQRCodeExpiry() {
    const timerElement = document.getElementById('countdown-timer');
    if (timerElement) {
        timerElement.textContent = 'Expired!';
        timerElement.classList.remove('text-danger');
        timerElement.classList.add('text-danger', 'fw-bold');
    }

    // Disable QR code display
    const qrContainer = document.getElementById('qr-code-container');
    qrContainer.innerHTML = `
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            QR code has expired. Please generate a new one.
        </div>
    `;

    // Reset status
    if (config.currentEmployeeId !== null) {
        updateActionButton('', '', 'expired');
    }
}

// Generate new QR code
function generateNewQR() {
    if (config.currentEmployeeId && config.currentAction) {
        generateQRCode(config.currentEmployeeId, config.currentAction);
    }
}

// Update action button visual feedback
function updateActionButton(employeeName, action, status) {
    const statusElement = document.getElementById(`status-${config.currentEmployeeId}`);

    if (!statusElement) return;

    switch (status) {
        case 'pending':
            statusElement.innerHTML = '<i class="fas fa-clock text-warning me-1"></i>QR Generated';
            statusElement.className = 'badge bg-warning text-dark';
            break;
        case 'scanned':
            statusElement.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Scanned';
            statusElement.className = 'badge bg-success';
            break;
        case 'expired':
            statusElement.innerHTML = '<i class="fas fa-times-circle text-danger me-1"></i>Expired';
            statusElement.className = 'badge bg-danger';
            break;
        default:
            statusElement.innerHTML = '<i class="fas fa-circle text-secondary me-1"></i>Ready';
            statusElement.className = 'badge bg-light text-dark';
    }
}

// Format action text
function formatAction(action) {
    return action === 'punch_in' ? 'Punch In' : 'Punch Out';
}

// Refresh page
function refreshPage() {
    window.location.reload();
}

// Utility function to show alerts
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        if (alertDiv && alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Expand all employee details rows
function expandAllRows() {
    const allDetailsRows = document.querySelectorAll('.employee-details-row');
    const toggleButtons = document.querySelectorAll('.toggle-details-btn i');

    allDetailsRows.forEach(row => {
        row.style.display = 'block';
    });

    toggleButtons.forEach(icon => {
        icon.className = 'fas fa-eye-slash';
    });
}

// Toggle employee details visibility
function toggleEmployeeDetails(employeeId) {
    const detailsRow = document.getElementById(`details-${employeeId}`);
    const toggleButton = document.querySelector(`[onclick="toggleEmployeeDetails(${employeeId})"] i`);

    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
        detailsRow.style.display = 'block';
        toggleButton.className = 'fas fa-eye-slash';
    } else {
        detailsRow.style.display = 'none';
        toggleButton.className = 'fas fa-eye';
    }
}

// Start polling for attendance status when QR is generated
function startAttendanceStatusCheck() {
    if (config.statusCheckInterval) {
        clearInterval(config.statusCheckInterval);
    }

    // Check every 2 seconds while QR is active
    config.statusCheckInterval = setInterval(async () => {
        try {
            const response = await fetch('{{ route("admin.daily-attendance.status-check", ":qrId") }}'.replace(':qrId', config.currentQrIdentifier), {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success && result.attendance_recorded) {
                    // Attendance was recorded, show success and update UI
                    handleAttendanceSuccess(result);
                    return;
                }
            }
        } catch (error) {
            console.log('Status check error (normal):', error);
        }
    }, 2000);

    // Stop checking after QR expires (12 seconds)
    setTimeout(() => {
        if (config.statusCheckInterval) {
            clearInterval(config.statusCheckInterval);
        }
    }, 12000);
}

// Handle successful attendance recording
function handleAttendanceSuccess(data) {
    // Clear intervals
    if (config.countdownInterval) clearInterval(config.countdownInterval);
    if (config.statusCheckInterval) clearInterval(config.statusCheckInterval);

    // Update QR status
    const statusText = document.getElementById('qr-status-text');
    if (statusText) {
        statusText.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>Attendance Recorded!';
        statusText.className = 'text-success fw-bold mt-2';
    }

    // Close modal after 2 seconds
    setTimeout(() => {
        if (config.qrModal) {
            config.qrModal.hide();
        }

        // Update attendance display
        updateAttendanceDisplay(data);

        // Show success message
        showAlert(`${config.currentAction === 'punch_in' ? 'Punch In' : 'Punch Out'} successful for ${data.employee_name}!`, 'success');

        // Reset variables
        config.currentQrIdentifier = null;
        config.currentEmployeeId = null;
        config.currentAction = null;
    }, 2000);
}

// Update attendance display after successful scan
function updateAttendanceDisplay(data) {
    // Update punched in/out counts
    const punchedInCount = document.getElementById('punched-in-count');
    const punchedOutCount = document.getElementById('punched-out-count');

    if (data.action === 'punch_in' && punchedInCount) {
        punchedInCount.textContent = parseInt(punchedInCount.textContent) + 1;
    }
    if (data.action === 'punch_out' && punchedOutCount) {
        punchedOutCount.textContent = parseInt(punchedOutCount.textContent) + 1;
    }

    // Update employee's attendance display
    const checkInElement = document.getElementById(`today-checkin-${data.employee_id}`);
    const checkOutElement = document.getElementById(`today-checkout-${data.employee_id}`);

    if (data.action === 'punch_in' && checkInElement) {
        checkInElement.textContent = new Date(data.timestamp).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        // Disable punch in button
        const punchInBtn = document.querySelector(`.punch-in-btn-${data.employee_id}`);
        if (punchInBtn) {
            punchInBtn.disabled = true;
            punchInBtn.innerHTML += ' <small>(Done)</small>';
        }
    }

    if (data.action === 'punch_out' && checkOutElement) {
        checkOutElement.textContent = new Date(data.timestamp).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        // Add worked hours if available
        if (data.worked_hours) {
            const parentDiv = checkOutElement.closest('.card-body');
            if (parentDiv && !parentDiv.querySelector('.worked-hours-row')) {
                const hoursRow = document.createElement('div');
                hoursRow.className = 'row mt-2 worked-hours-row';
                hoursRow.innerHTML = `
                    <div class="col-12">
                        <small class="text-muted">Worked Hours:</small><br>
                        <span class="small fw-bold text-primary">${data.formatted_hours || data.worked_hours + 'h'}</span>
                    </div>
                `;
                parentDiv.appendChild(hoursRow);
            }
        }

        // Disable punch out button
        const punchOutBtn = document.querySelector(`.punch-out-btn-${data.employee_id}`);
        if (punchOutBtn) {
            punchOutBtn.disabled = true;
            punchOutBtn.innerHTML += ' <small>(Done)</small>';
        }
    }
}

// Cleanup on modal close
document.getElementById('qrModal').addEventListener('hidden.bs.modal', function () {
    if (config.countdownInterval) {
        clearInterval(config.countdownInterval);
    }
    if (config.statusCheckInterval) {
        clearInterval(config.statusCheckInterval);
    }
    config.currentQrIdentifier = null;
    config.currentEmployeeId = null;
    config.currentAction = null;
});
</script>

<style>
/* Table Layout Styles */
.attendance-table-photo {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border: 2px solid var(--primary-color);
}

.attendance-table-photo-placeholder {
    width: 50px;
    height: 50px;
    font-size: 20px;
    font-weight: bold;
}

.employee-row {
    padding: 1rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.employee-row:hover {
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.employee-details-row {
    margin-top: 1rem;
}

.employee-status-badge {
    font-size: 0.75rem;
    transition: all 0.3s ease;
}

.punch-table-btn {
    min-width: 70px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.punch-table-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.toggle-details-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.8rem;
    min-width: 30px;
}

.toggle-details-btn i {
    transition: transform 0.3s ease;
}

/* Legacy styles (keeping for compatibility) */
.attendance-photo {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border: 3px solid var(--primary-color);
}

.attendance-photo-placeholder {
    width: 80px;
    height: 80px;
    font-size: 32px;
}

.employee-attendance-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.employee-attendance-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.employee-status .badge {
    font-size: 0.75rem;
}

.modal-dialog {
    max-width: 600px;
}

.employee-info-section, .qr-code-section {
    padding: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .attendance-photo {
        width: 60px;
        height: 60px;
    }

    .punch-btn span {
        display: none !important;
    }

    .punch-btn {
        padding: 8px 12px;
    }

    .attendance-table-photo {
        width: 40px;
        height: 40px;
    }

    .attendance-table-photo-placeholder {
        width: 40px;
        height: 40px;
        font-size: 16px;
    }

    .employee-row {
        padding: 0.75rem;
    }
}

/* Row separators */
.employee-row + .employee-row {
    margin-top: 1rem;
}
</style>
@endsection
