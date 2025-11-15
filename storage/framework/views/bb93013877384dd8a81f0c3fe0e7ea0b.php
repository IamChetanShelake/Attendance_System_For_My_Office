

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-hand-paper me-2"></i>Record Manual Attendance - Standalone Form
                </h1>
                <p class="page-subtitle">Dedicated page for recording attendance manually for employees working from home or those unable to use QR scanning</p>
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
                        <h3><?php echo e($employees->where('wfh_pin', '!=', null)->count()); ?></h3>
                        <p>Employees with PIN</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card bg-gradient-info text-white p-4 rounded">
                        <h5 class="mb-2">
                            <i class="fas fa-info-circle me-2"></i>Manual Attendance Recording
                        </h5>
                        <p class="mb-0">This dedicated page allows you to record attendance for employees who cannot use the QR scanning system.</p>
                    </div>
                </div>
            </div>

            <!-- Manual Attendance Recording - Enhanced UX -->
            <div class="section-card mb-4">
                <div class="card-header bg-gradient-manual text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-hand-paper me-2"></i>
                        <span>Record Manual Attendance</span>
                        <span class="badge bg-light text-dark ms-2" id="formStatus">
                            <i class="fas fa-info-circle me-1"></i>Ready to Record
                        </span>
                    </h5>
                    <div class="header-actions">
                        <a href="<?php echo e(route('admin.manage-attendance')); ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Overview
                        </a>
                        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#attendanceHelpModal">
                            <i class="fas fa-question-circle me-1"></i>Help
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Status Messages -->
                    <div id="formMessages"></div>

                    <!-- Quick Employee Photo Preview -->
                    <div class="employee-preview d-none" id="employeePreview">
                        <div class="preview-header mb-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-user-circle me-2"></i>Selected Employee:
                                <span id="previewName" class="fw-bold text-dark"></span>
                            </h6>
                        </div>
                        <div class="preview-card d-flex align-items-center p-3 bg-light rounded">
                            <div id="previewPhoto" class="me-3">
                                <!-- Photo will be inserted here -->
                            </div>
                            <div class="preview-info flex-grow-1">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">Employee ID:</small>
                                        <span id="previewEmpId" class="fw-bold text-primary"></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <small class="text-muted d-block">PIN Status:</small>
                                        <span id="previewPinStatus" class="badge">
                                            <i class="fas fa-spin fa-spinner me-1"></i>Loading...
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="<?php echo e(route('admin.manage-attendance.record')); ?>" class="attendance-form" id="attendanceForm">
                        <?php echo csrf_field(); ?>

                        <!-- Step 1: Select Employee -->
                        <div class="form-section mb-4">
                            <div class="section-title mb-3">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-user-plus me-2"></i>Step 1: Select Employee
                                </h6>
                                <small class="text-muted">Choose the employee for whom you want to record attendance</small>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <select class="form-select form-select-lg <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="employeeSelect"
                                                name="employee_id"
                                                required
                                                data-placeholder="Search and select an employee...">
                                            <option value="">Choose Employee...</option>
                                            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($emp->id); ?>"
                                                    data-pin="<?php echo e($emp->wfh_pin ? 'yes' : 'no'); ?>"
                                                    data-emp-id="<?php echo e($emp->employee_id); ?>"
                                                    data-name="<?php echo e($emp->full_name); ?>"
                                                    data-photo="<?php echo e($emp->photo ? asset('storage/' . $emp->photo) : ''); ?>"
                                                    data-position="<?php echo e($emp->position); ?>">
                                                <?php echo e($emp->employee_id); ?> - <?php echo e($emp->full_name); ?>

                                            </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="input-group-text">
                                            <span class="employee-count"><?php echo e($employees->count()); ?></span>
                                            <small class="ms-1">employees</small>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Only employees with PIN numbers can have manual attendance recorded
                                    </small>
                                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Attendance Details -->
                        <div class="form-section mb-4">
                            <div class="section-title mb-3">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-clock me-2"></i>Step 2: Record Attendance
                                </h6>
                                <small class="text-muted">Specify the action and required PIN for verification</small>
                            </div>
                            <div class="row g-3">
                                <!-- Action Selection -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold" for="actionSelect">
                                        <i class="fas fa-play-circle me-1"></i>Attendance Action <span class="text-danger">*</span>
                                    </label>
                                    <div class="action-buttons d-flex gap-2">
                                        <input type="radio" class="btn-check" id="punch_in" name="action" value="punch_in" autocomplete="off" required>
                                        <label class="btn btn-outline-success action-btn flex-fill" for="punch_in">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            <span class="d-block">Punch In</span>
                                            <small class="d-block opacity-75">Start Work</small>
                                        </label>

                                        <input type="radio" class="btn-check" id="punch_out" name="action" value="punch_out" autocomplete="off" required>
                                        <label class="btn btn-outline-danger action-btn flex-fill" for="punch_out">
                                            <i class="fas fa-sign-out-alt me-1"></i>
                                            <span d-block>Punch Out</span>
                                            <small class="d-block opacity-75">End Work</small>
                                        </label>
                                    </div>
                                    <?php $__errorArgs = ['action'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- PIN Input -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold" for="pinInput">
                                        <i class="fas fa-lock me-1"></i>6-Digit PIN <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control form-control-lg <?php $__errorArgs = ['pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               id="pinInput"
                                               name="pin"
                                               pattern="[0-9]{6}"
                                               maxlength="6"
                                               placeholder="123456"
                                               required
                                               value="<?php echo e(old('pin')); ?>"
                                               autocomplete="off">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePinVisibility">
                                            <i class="fas fa-eye" id="pinVisibilityIcon"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Enter the employee's WFH PIN (check employee profile)
                                    </small>
                                    <div class="pin-validation mt-1">
                                        <small id="pinHelp" class="text-muted d-none">
                                            <i class="fas fa-check-circle text-success me-1"></i>PIN format correct
                                        </small>
                                    </div>
                                    <?php $__errorArgs = ['pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Attendance Type -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold" for="attendanceTypeSelect">
                                        <i class="fas fa-briefcase me-1"></i>Work Type
                                    </label>
                                    <select class="form-select form-select-lg" id="attendanceTypeSelect" name="attendance_type">
                                        <option value="wfh">🏠 Work From Home</option>
                                        <option value="office">🏢 Office</option>
                                        <option value="hybrid">🔄 Hybrid</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Select how the employee is working today
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Submit -->
                        <div class="form-section">
                            <div class="text-center">
                                <div class="confirmation-panel d-none" id="confirmationPanel">
                                    <div class="alert alert-info border">
                                        <h6 class="alert-heading mb-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Confirm Attendance Record
                                        </h6>
                                        <div class="confirmation-details text-left">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <strong>Employee:</strong> <span id="confirmEmployee" class="text-primary"></span><br>
                                                    <strong>Action:</strong> <span id="confirmAction" class="text-success"></span><br>
                                                </div>
                                                <div class="col-sm-6">
                                                    <strong>Work Type:</strong> <span id="confirmType" class="text-info"></span><br>
                                                    <strong>Time:</strong> <span id="confirmTime" class="text-muted"><?php echo e(now()->format('H:i:s')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                This action will record attendance and cannot be undone.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg px-5" id="recordAttendanceBtn">
                                    <i class="fas fa-save me-2"></i>
                                    <span class="btn-text">Record Attendance</span>
                                    <span class="processing-text d-none">
                                        <i class="fas fa-spinner fa-spin me-2"></i>Processing...
                                    </span>
                                </button>

                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        Current time: <span class="current-time fw-bold"><?php echo e(now()->format('l, F j, Y \a\t H:i:s')); ?></span>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Success/Error Messages -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show mt-4 success-animation">
                            <i class="fas fa-check-circle fa-lg me-2"></i>
                            <strong>Success!</strong> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show mt-4 error-animation">
                            <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                            <strong>Error!</strong> <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
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
            <button type="button class="btn-close" data-bs-dismiss="alert"></button>
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

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #28a745 100%) !important;
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
    50% { transform: scale(0.8); }
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
    .btn {
        box-shadow: none !important;
        transform: none !important;
    }

    .form-section:hover {
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
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/attendance/record-manual.blade.php ENDPATH**/ ?>