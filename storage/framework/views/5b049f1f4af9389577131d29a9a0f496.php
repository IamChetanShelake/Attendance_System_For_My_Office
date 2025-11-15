

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-calendar-check me-2"></i>Manage Attendance - Manual Entry
                </h1>
                <p class="page-subtitle">Record attendance manually for employees working from home or those unable to use QR scanning</p>
            </div>

            <!-- Today's Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3><?php echo e($employees->count()); ?></h3>
                        <p>Total Employees</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h3><?php echo e($todayAttendances->where('punch_in_source', 'manual')->count()); ?></h3>
                        <p>Manual Punch In Today</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <h3><?php echo e($todayAttendances->where('punch_out_source', 'manual')->count()); ?></h3>
                        <p>Manual Punch Out Today</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h3><?php echo e($employees->where('wfh_pin', '!=', null)->count()); ?></h3>
                        <p>Employees with PIN</p>
                    </div>
                </div>
            </div>

            <!-- Manual Attendance Recording Actions -->
            <div class="section-card mb-4">
                <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-hand-paper me-2"></i>
                        <span>Manual Attendance Recording</span>
                    </h5>
                    <div class="header-actions">
                        <a href="<?php echo e(route('admin.manual-attendance')); ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-edit me-2"></i>Go to Manual Recording Form
                        </a>
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#attendanceHelpModal">
                            <i class="fas fa-question-circle me-1"></i>Help
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-3">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                For recording attendance of employees who cannot use QR scanning (Work From Home, special cases, etc.).
                                This feature requires employees to have PIN numbers set in their profiles.
                            </p>
                            <div class="row text-center">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light border-primary">
                                        <div class="card-body p-3">
                                            <i class="fas fa-user-plus fa-2x text-primary mb-2"></i>
                                            <h6>Select Employee</h6>
                                            <small class="text-muted">Choose from employee list</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light border-success">
                                        <div class="card-body p-3">
                                            <i class="fas fa-clock fa-2x text-success mb-2"></i>
                                            <h6>Enter PIN & Action</h6>
                                            <small class="text-muted">Verify with 6-digit PIN</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light border-info">
                                        <div class="card-body p-3">
                                            <i class="fas fa-save fa-2x text-info mb-2"></i>
                                            <h6>Record Attendance</h6>
                                            <small class="text-muted">Secure data storage</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-gradient-primary text-white p-4 rounded">
                                <h6 class="mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>Security Features
                                </h6>
                                <ul class="small mb-0">
                                    <li>6-digit PIN verification</li>
                                    <li>Employee photo confirmation</li>
                                    <li>Fraud prevention measures</li>
                                    <li>Audit trail logging</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Help Modal -->
            <div class="modal fade" id="attendanceHelpModal" tabindex="-1" aria-labelledby="attendanceHelpModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="attendanceHelpModalLabel">
                                <i class="fas fa-question-circle me-2"></i>How to Record Manual Attendance
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">
                                        <i class="fas fa-user-tag me-2"></i>Employee Selection
                                    </h6>
                                    <ul class="small mb-4">
                                        <li>Select an employee from the dropdown</li>
                                        <li>Employees without PINs are disabled</li>
                                        <li>Employee photo and details appear for verification</li>
                                    </ul>

                                    <h6 class="text-primary">
                                        <i class="fas fa-clock me-2"></i>Attendance Action
                                    </h6>
                                    <ul class="small mb-4">
                                        <li><strong>Punch In:</strong> Records start of work session</li>
                                        <li><strong>Punch Out:</strong> Records end of work session</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">
                                        <i class="fas fa-lock me-2"></i>PIN Verification
                                    </h6>
                                    <ul class="small mb-4">
                                        <li>6-digit PIN required for security</li>
                                        <li>Find PIN in employee profile ("WFH PIN" section)</li>
                                        <li>PIN is masked by default, click eye icon to reveal</li>
                                    </ul>

                                    <h6 class="text-primary">
                                        <i class="fas fa-building me-2"></i>Work Type
                                    </h6>
                                    <ul class="small">
                                        <li><strong>WFH:</strong> Work From Home</li>
                                        <li><strong>Office:</strong> In-Office Work</li>
                                        <li><strong>Hybrid:</strong> Mixed working arrangement</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Tip:</strong> Always verify the employee photo and details before recording attendance to ensure accuracy.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Attendance Overview -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>Employee Attendance Overview - Manual Management
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar-day me-1"></i><?php echo e(now()->format('l, F j, Y')); ?>

                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="employee-attendance-card border rounded p-3 mb-3 <?php echo e($employee->photo ? 'has-photo' : ''); ?>"
                             data-employee-id="<?php echo e($employee->id); ?>">
                            <div class="row align-items-center">
                                <!-- Employee Photo & Basic Info -->
                                <div class="col-lg-3 col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <?php if($employee->photo): ?>
                                                <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>"
                                                     class="rounded-circle"
                                                     style="width: 50px; height: 50px; object-fit: cover;"
                                                     alt="<?php echo e($employee->full_name); ?>">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                     style="width: 50px; height: 50px; font-weight: bold;">
                                                    <?php echo e(strtoupper(substr($employee->first_name, 0, 1))); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold"><?php echo e($employee->full_name); ?></h6>
                                            <small class="text-muted"><?php echo e($employee->position); ?></small>
                                            <br>
                                            <small class="badge bg-secondary"><?php echo e($employee->employee_id); ?></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- PIN Status -->
                                <div class="col-lg-2 col-md-2 text-center">
                                    <div class="pin-status">
                                        <?php if($employee->wfh_pin): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-lock me-1"></i>PIN Active
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">
                                                <i class="fas fa-lock-open me-1"></i>No PIN
                                            </span>
                                            <br>
                                            <small class="text-muted">Add PIN to allow manual attendance</small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Today's Attendance Status -->
                                <div class="col-lg-3 col-md-3">
                                    <div class="attendance-status">
                                        <?php if(isset($todayAttendances[$employee->id])): ?>
                                            <?php $attendance = $todayAttendances[$employee->id] ?>
                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="d-block text-muted">Check In:</small>
                                                    <span class="badge <?php echo e($attendance->punch_in_source === 'manual' ? 'bg-info' : 'bg-primary'); ?>">
                                                        <?php echo e($attendance->punch_in_time?->format('H:i') ?? '--:--'); ?>

                                                        <?php if($attendance->punch_in_source === 'manual'): ?>
                                                            <small>(Manual)</small>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="d-block text-muted">Check Out:</small>
                                                    <span class="badge <?php echo e($attendance->punch_out_source === 'manual' ? 'bg-info' : 'bg-danger'); ?>">
                                                        <?php echo e($attendance->punch_out_time?->format('H:i') ?? '--:--'); ?>

                                                        <?php if($attendance->punch_out_source === 'manual'): ?>
                                                            <small>(Manual)</small>
                                                        <?php endif; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <?php if($attendance->worked_hours): ?>
                                                <div class="mt-1">
                                                    <small class="d-block text-muted">Worked Hours:</small>
                                                    <span class="badge bg-info"><?php echo e($attendance->formatted_worked_hours); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge bg-light text-dark">Not punched yet</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Attendance Type & Source -->
                                <div class="col-lg-2 col-md-2 text-center">
                                    <?php if(isset($todayAttendances[$employee->id])): ?>
                                        <?php $attendance = $todayAttendances[$employee->id] ?>
                                        <div>
                                            <div class="badge bg-secondary mb-1">
                                                <i class="fas fa-<?php echo e($attendance->attendance_type === 'wfh' ? 'home' : ($attendance->attendance_type === 'hybrid' ? 'exchange-alt' : 'building')); ?> me-1"></i>
                                                <?php echo e(ucfirst($attendance->attendance_type)); ?>

                                            </div>
                                            <br>
                                            <small class="text-muted">
                                                <?php if($attendance->punch_in_source || $attendance->punch_out_source): ?>
                                                    <?php echo e($attendance->punch_in_source === 'manual' || $attendance->punch_out_source === 'manual' ? 'Manual Entry' : 'Auto Entry'); ?>

                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <small class="text-muted">Not available</small>
                                    <?php endif; ?>
                                </div>

                                <!-- Quick Actions -->
                                <div class="col-lg-2 col-md-1 text-end">
                                    <div class="btn-group" role="group">
                                        <button class="btn btn-sm btn-outline-primary quick-select"
                                                data-employee-id="<?php echo e($employee->id); ?>"
                                                data-employee-name="<?php echo e($employee->full_name); ?>"
                                                data-has-pin="<?php echo e($employee->wfh_pin ? 'yes' : 'no'); ?>"
                                                title="Select for Manual Attendance">
                                            <i class="fas fa-hand-pointer"></i>
                                        </button>
                                        <a href="<?php echo e(route('admin.employees.edit', $employee->id)); ?>"
                                           class="btn btn-sm btn-outline-secondary"
                                           title="Edit Employee PIN">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted mb-2">No Employees Found</h4>
                            <p class="text-muted">Add employees first to start managing attendance.</p>
                            <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Add First Employee
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Instructions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Instructions</h6>
                                <ul class="mb-0">
                                    <li>Manual attendance is typically used for employees working from home or those unable to use QR scanning.</li>
                                    <li>Each employee must have a 6-digit PIN set in their profile to allow manual attendance recording.</li>
                                    <li><strong>Action Legend:</strong> Blue badges = QR scanned, Light blue badges = Manual entry</li>
                                    <li>Use the "Select" button to quickly fill the form above with employee details.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Global state
let pinVisible = false;
let selectedEmployee = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeAttendanceManagement();
});

function initializeAttendanceManagement() {
    // Initialize PIN visibility toggle
    initializePinToggle();

    // Handle employee selection changes with enhanced functionality
    const employeeSelect = document.getElementById('employeeSelect');
    if (employeeSelect) {
        employeeSelect.addEventListener('change', handleEmployeeSelection);
    }

    // Handle action button selection
    document.querySelectorAll('input[name="action"]').forEach(radio => {
        radio.addEventListener('change', handleActionSelection);
    });

    // Handle PIN input validation
    const pinInput = document.getElementById('pinInput');
    if (pinInput) {
        pinInput.addEventListener('input', validatePinInput);
    }

    // Handle form submission with visual feedback
    const attendanceForm = document.getElementById('attendanceForm');
    if (attendanceForm) {
        attendanceForm.addEventListener('submit', handleFormSubmission);
    }

    // Handle quick select buttons
    document.querySelectorAll('.quick-select').forEach(btn => {
        btn.addEventListener('click', function() {
            quickSelectEmployee(this.dataset.employeeId);
        });
    });

    // Update current time periodically
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);

    updateDailyStats();

    // Initialize form status
    updateFormStatus();
}

function initializePinToggle() {
    const toggleBtn = document.getElementById('togglePinVisibility');
    const pinInput = document.getElementById('pinInput');
    const icon = document.getElementById('pinVisibilityIcon');

    if (toggleBtn && pinInput && icon) {
        toggleBtn.addEventListener('click', function(e) {
            e.preventDefault();
            pinVisible = !pinVisible;

            pinInput.type = pinVisible ? 'text' : 'password';
            icon.className = pinVisible ? 'fas fa-eye-slash' : 'fas fa-eye';

            // Add visual feedback
            toggleBtn.classList.add('btn-clicked');
            setTimeout(() => toggleBtn.classList.remove('btn-clicked'), 200);
        });
    }
}

function handleEmployeeSelection(e) {
    const selectedOption = e.target.selectedOptions[0];
    if (!selectedOption || !selectedOption.value) {
        selectedEmployee = null;
        hideEmployeePreview();
        updateFormStatus();
        return;
    }

    selectedEmployee = {
        id: selectedOption.value,
        name: selectedOption.dataset.name,
        empId: selectedOption.dataset.empId,
        hasPin: selectedOption.dataset.pin === 'yes',
        photo: selectedOption.dataset.photo,
        position: selectedOption.dataset.position
    };

    // Show employee preview
    showEmployeePreview(selectedEmployee);

    // Handle PIN availability
    updatePinAvailability(selectedEmployee.hasPin);

    // Update form status
    updateFormStatus();

    // Clear previous form messages
    const formMessages = document.getElementById('formMessages');
    if (formMessages) {
        formMessages.innerHTML = '';
    }
}

function showEmployeePreview(employee) {
    const previewDiv = document.getElementById('employeePreview');
    const previewName = document.getElementById('previewName');
    const previewEmpId = document.getElementById('previewEmpId');
    const previewPhoto = document.getElementById('previewPhoto');

    if (previewDiv && previewName && previewEmpId && previewPhoto) {
        // Update text content
        previewName.textContent = employee.name || 'Unknown';
        previewEmpId.textContent = employee.empId || 'N/A';

        // Show appropriate photo
        if (employee.photo) {
            previewPhoto.innerHTML = `
                <img src="${employee.photo}" alt="${employee.name}"
                     class="rounded-circle"
                     style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--primary-color);">
            `;
        } else {
            const initial = employee.name ? employee.name.charAt(0).toUpperCase() : '?';
            previewPhoto.innerHTML = `
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                     style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
                    ${initial}
                </div>
            `;
        }

        // Show preview with animation
        previewDiv.classList.remove('d-none');
        previewDiv.style.opacity = '0';
        previewDiv.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            previewDiv.style.transition = 'all 0.3s ease';
            previewDiv.style.opacity = '1';
            previewDiv.style.transform = 'translateY(0)';
        }, 10);
    }
}

function hideEmployeePreview() {
    const previewDiv = document.getElementById('employeePreview');
    if (previewDiv && !previewDiv.classList.contains('d-none')) {
        previewDiv.style.transition = 'all 0.3s ease';
        previewDiv.style.opacity = '0';
        previewDiv.style.transform = 'translateY(-10px)';

        setTimeout(() => {
            previewDiv.classList.add('d-none');
            previewDiv.style.transition = '';
        }, 300);
    }
}

function updatePinAvailability(hasPin) {
    const pinInput = document.getElementById('pinInput');
    const recordBtn = document.getElementById('recordAttendanceBtn');
    const pinStatus = document.getElementById('previewPinStatus');

    if (hasPin) {
        pinInput.disabled = false;
        recordBtn.disabled = false;
        recordBtn.title = '';

        if (pinStatus) {
            pinStatus.innerHTML = '<i class="fas fa-lock text-success me-1"></i>PIN Active';
            pinStatus.className = 'badge bg-success';
        }
    } else {
        pinInput.disabled = true;
        recordBtn.disabled = true;
        recordBtn.title = 'Employee must have a PIN set to record manual attendance';

        if (pinStatus) {
            pinStatus.innerHTML = '<i class="fas fa-lock-open text-warning me-1"></i>No PIN Set';
            pinStatus.className = 'badge bg-warning';
        }

        showMessage('This employee does not have a PIN set. Please add a PIN to the employee profile first.', 'warning');
    }
}

function handleActionSelection(e) {
    const selectedAction = e.target.value;
    const actionButtons = document.querySelectorAll('.action-btn');

    // Remove active state from all buttons
    actionButtons.forEach(btn => {
        btn.classList.remove('active');
        btn.classList.add('btn-outline-success', 'btn-outline-danger');
        btn.classList.remove('btn-success', 'btn-danger');
    });

    // Add active state to selected button
    const selectedBtn = e.target.closest('.action-btn');
    if (selectedBtn) {
        selectedBtn.classList.add('active');
        selectedBtn.classList.toggle('btn-success', selectedAction === 'punch_in');
        selectedBtn.classList.toggle('btn-danger', selectedAction === 'punch_out');
        selectedBtn.classList.remove('btn-outline-success', 'btn-outline-danger');
    }

    // Update form status
    updateFormStatus();

    // Show/hide confirmation panel
    const confirmationPanel = document.getElementById('confirmationPanel');
    if (confirmationPanel && selectedEmployee) {
        updateConfirmationPanel(selectedEmployee, selectedAction);
        confirmationPanel.classList.remove('d-none');
        setTimeout(() => {
            confirmationPanel.style.opacity = '0';
            confirmationPanel.style.transform = 'scale(0.95)';
            setTimeout(() => {
                confirmationPanel.style.transition = 'all 0.3s ease';
                confirmationPanel.style.opacity = '1';
                confirmationPanel.style.transform = 'scale(1)';
            }, 10);
        }, 100);
    }
}

function updateConfirmationPanel(employee, action) {
    const confirmEmployee = document.getElementById('confirmEmployee');
    const confirmAction = document.getElementById('confirmAction');
    const confirmType = document.getElementById('confirmType');

    if (confirmEmployee) confirmEmployee.textContent = employee.name;
    if (confirmAction) {
        confirmAction.textContent = action === 'punch_in' ? 'PUNCH IN' : 'PUNCH OUT';
        confirmAction.className = action === 'punch_in' ? 'text-success' : 'text-danger';
    }

    const typeSelect = document.getElementById('attendanceTypeSelect');
    if (confirmType && typeSelect) {
        const typeIcons = { 'wfh': '🏠', 'office': '🏢', 'hybrid': '🔄' };
        const typeLabels = { 'wfh': 'Work From Home', 'office': 'Office', 'hybrid': 'Hybrid' };
        const selectedType = typeSelect.value;
        confirmType.innerHTML = `${typeIcons[selectedType] || '🏢'} ${typeLabels[selectedType] || 'Office'}`;
    }
}

function validatePinInput(e) {
    const pin = e.target.value;
    const pinHelp = document.getElementById('pinHelp');

    if (pinHelp) {
        if (pin.length === 0) {
            pinHelp.classList.add('d-none');
        } else if (pin.length === 6 && /^\d+$/.test(pin)) {
            pinHelp.innerHTML = '<i class="fas fa-check-circle text-success me-1"></i>PIN format correct';
            pinHelp.classList.remove('d-none');
        } else {
            pinHelp.innerHTML = '<i class="fas fa-exclamation-circle text-warning me-1"></i>Enter 6 digits only';
            pinHelp.classList.remove('d-none');
        }
    }

    updateFormStatus();
}

function handleFormSubmission(e) {
    const submitBtn = document.getElementById('recordAttendanceBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const processingText = submitBtn.querySelector('.processing-text');

    if (btnText && processingText) {
        // Add processing state
        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        processingText.classList.remove('d-none');

        // Add visual feedback
        submitBtn.classList.add('processing');
    }
}

function updateFormStatus() {
    const statusBadge = document.getElementById('formStatus');
    const employeeSelected = selectedEmployee !== null;
    const pinEntered = document.getElementById('pinInput')?.value.length === 6;
    const actionSelected = document.querySelector('input[name="action"]:checked') !== null;

    if (!employeeSelected) {
        statusBadge.innerHTML = '<i class="fas fa-info-circle me-1"></i>Select Employee';
        statusBadge.className = 'badge bg-secondary text-white ms-2';
    } else if (selectedEmployee && !selectedEmployee.hasPin) {
        statusBadge.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>PIN Required';
        statusBadge.className = 'badge bg-warning text-dark ms-2';
    } else if (!actionSelected) {
        statusBadge.innerHTML = '<i class="fas fa-clock me-1"></i>Choose Action';
        statusBadge.className = 'badge bg-info text-white ms-2';
    } else if (!pinEntered) {
        statusBadge.innerHTML = '<i class="fas fa-lock me-1"></i>Enter PIN';
        statusBadge.className = 'badge bg-warning text-dark ms-2';
    } else {
        statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i>Ready to Record';
        statusBadge.className = 'badge bg-success text-white ms-2';
    }
}

function quickSelectEmployee(employeeId) {
    const employeeSelect = document.getElementById('employeeSelect');
    employeeSelect.value = employeeId;

    // Trigger change event
    const changeEvent = new Event('change');
    employeeSelect.dispatchEvent(changeEvent);

    // Scroll to form smoothly
    const targetSection = document.querySelector('.section-card');
    if (targetSection) {
        targetSection.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }

    // Focus PIN input after a brief delay
    setTimeout(() => {
        const pinInput = document.getElementById('pinInput');
        if (pinInput && !pinInput.disabled) {
            pinInput.focus();
            // Highlight the input briefly
            pinInput.style.boxShadow = '0 0 0 0.2rem rgba(13, 110, 253, 0.25)';
            setTimeout(() => {
                pinInput.style.boxShadow = '';
            }, 1000);
        }
    }, 800);
}

function updateCurrentTime() {
    const timeElement = document.querySelector('.current-time');
    if (timeElement) {
        const now = new Date();
        timeElement.textContent = now.toLocaleString('en-IN', {
            timeZone: 'Asia/Kolkata',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }
}

function updateDailyStats() {
    // Simplified stats update - could be enhanced with AJAX for real-time data
    const totalPunchedIn = document.getElementById('total-punched-in');
    const totalPunchedOut = document.getElementById('total-punched-out');

    // Count manual attendance records in the current view
    let punchedIn = 0;
    let punchedOut = 0;

    document.querySelectorAll('.employee-attendance-card').forEach(card => {
        const checkInBadge = card.querySelector('.badge.bg-info, .badge.bg-primary');
        const checkOutBadge = card.querySelector('.badge.bg-danger');

        if (checkInBadge && !checkInBadge.textContent.includes('--:--')) punchedIn++;
        if (checkOutBadge && !checkOutBadge.textContent.includes('--:--')) punchedOut++;
    });

    if (totalPunchedIn) totalPunchedIn.textContent = punchedIn;
    if (totalPunchedOut) totalPunchedOut.textContent = punchedOut;
}

function showMessage(message, type) {
    // Create or replace temporary message div for inline alerts
    let messageDiv = document.getElementById('tempInlineAlert');
    if (!messageDiv) {
        messageDiv = document.createElement('div');
        messageDiv.id = 'tempInlineAlert';
        document.getElementById('formMessages').appendChild(messageDiv);
    }

    messageDiv.innerHTML = `
        <div class="alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} alert-dismissible fade show mt-2">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (messageDiv && messageDiv.parentNode) {
            messageDiv.remove();
        }
    }, 5000);
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Alt + R to focus record button
    if (e.altKey && e.key === 'r') {
        e.preventDefault();
        const recordBtn = document.getElementById('recordAttendanceBtn');
        if (recordBtn && !recordBtn.disabled) {
            recordBtn.click();
        }
    }

    // Alt + E to focus employee select
    if (e.altKey && e.key === 'e') {
        e.preventDefault();
        const employeeSelect = document.getElementById('employeeSelect');
        if (employeeSelect) employeeSelect.focus();
    }
});
</script>

<style>
/* Gradient backgrounds for enhanced UX */
.bg-gradient-manual {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%) !important;
    color: white;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0069d9 100%) !important;
    color: white;
}

/* Form sections with improved styling */
.form-section {
    padding: 1.5rem;
    margin-bottom: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid var(--primary-color);
}

.form-section:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

/* Action buttons with visual feedback */
.action-buttons .btn {
    margin-right: 0.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid;
    position: relative;
    overflow: hidden;
}

.action-buttons .btn:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.action-buttons .btn:hover:before {
    left: 100%;
}

.action-btn.active {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: scale(1.02);
}

/* Employee preview with animations */
.employee-preview {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.preview-card {
    animation: fadeInScale 0.4s ease-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* PIN validation and input styling */
.pin-validation .fas {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Form status badge animations */
#formStatus {
    animation: statusChange 0.5s ease-out;
}

@keyframes statusChange {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Button click feedback */
.btn-clicked {
    animation: clickFeedback 0.2s ease-out;
}

@keyframes clickFeedback {
    0% { transform: scale(1); }
    50% { transform: scale(0.98); }
    100% { transform: scale(1); }
}

/* Processing button state */
.btn.processing {
    animation: processingPulse 1.5s ease-in-out infinite;
}

@keyframes processingPulse {
    0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(0,123,255,0.5); }
    50% { opacity: 0.8; box-shadow: 0 0 0 8px rgba(0,123,255,0.1); }
}

/* Alert animations */
.success-animation {
    animation: successAlert 0.5s ease-out;
}

@keyframes successAlert {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.error-animation {
    animation: errorAlert 0.5s ease-out;
}

@keyframes errorAlert {
    from {
        opacity: 0;
        transform: translateX(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateX(0) scale(1);
    }
}

/* Employee attendance cards */
.employee-attendance-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
}

.employee-attendance-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateY(-4px) scale(1.01);
}

.employee-attendance-card.has-photo {
    border-left: 4px solid var(--primary-color);
    border-image: linear-gradient(135deg, var(--primary-color), #6f42c1) 1;
}

/* Stats cards with better appearance */
.stats-card {
    background: linear-gradient(135deg, #4facfe 0%, rgba(79, 172, 254, 0.8) 100%);
    border: none;
    border-radius: 15px;
    color: white;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(79, 172, 254, 0.4);
}

.stats-card .icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #000000 !important;
}

.stats-card h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

/* PIN input with better styling */
#pinInput {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5rem;
    font-size: 1.1rem;
    text-align: center;
}

/* Confirmation panel */
.confirmation-panel {
    animation: confirmationReveal 0.5s ease-out;
}

@keyframes confirmationReveal {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.confirmation-details {
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

/* Large employee count badge */
.employee-count {
    font-weight: bold;
    color: var(--primary-color);
}

/* Enhanced input group styling */
.input-group-text {
    background: linear-gradient(135deg, var(--primary-color), #0056b3);
    color: white;
    border: none;
}

.input-group-text i {
    animation: breathe 2s ease-in-out infinite;
}

@keyframes breathe {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Section titles with animated underlines */
.section-title::after {
    content: '';
    display: block;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), #6f42c1);
    border-radius: 2px;
    margin-top: 0.5rem;
    animation: expandLine 0.8s ease-out forwards;
}

@keyframes expandLine {
    from { width: 0; }
    to { width: 50px; }
}

/* Focus states for better UX */
.employee-preview img,
.form-control:focus,
.btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

/* Real-time clock styling */
.current-time {
    font-variant-numeric: tabular-nums;
    animation: timeUpdate 1s ease-in-out infinite;
}

@keyframes timeUpdate {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

/* Quick select button hover effect */
.quick-select {
    border-color: var(--primary-color);
    color: var(--primary-color);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.quick-select::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(13, 110, 253, 0.2), transparent);
    transition: left 0.5s;
}

.quick-select:hover::before {
    left: 100%;
}

.quick-select:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
}

/* Enhanced form validation states */
.form-control:valid {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.form-control:invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

/* Mobile responsiveness for action buttons */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column !important;
    }

    .action-btn {
        width: 100% !important;
        margin-right: 0 !important;
        margin-bottom: 0.5rem !important;
    }

    .form-section {
        padding: 1rem;
    }

    .confirmation-details {
        font-size: 0.9rem;
    }
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .stats-card {
        border: 2px solid #000;
    }

    .action-btn {
        border-width: 3px;
    }

    .badge {
        border: 1px solid #000;
    }
}

/* Print styles */
@media print {
    .btn, .form-section:hover, .employee-attendance-card:hover {
        box-shadow: none !important;
        transform: none !important;
    }

    .current-time {
        color: #000;
        background: #f0f0f0;
        padding: 2px 4px;
        border-radius: 3px;
    }
}

/* Loading states */
.loading-fade {
    opacity: 0.6;
    pointer-events: none;
}

/* Enhanced fade class override */
.fade {
    transition: opacity 0.3s ease;
}

.pin-status .badge {
    font-size: 0.75rem;
    font-weight: 600;
}

.attendance-status .badge {
    font-size: 0.7rem;
    font-weight: 500;
}

.employee-attendance-card .row {
    align-items: center;
}

/* Improved modal styling for help */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(var(--primary-color), #6f42c1);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(#6f42c1, var(--primary-color));
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/attendance/manage.blade.php ENDPATH**/ ?>