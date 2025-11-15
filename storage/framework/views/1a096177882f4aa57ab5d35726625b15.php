

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Employee Management Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-users me-2"></i>Employee Management
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Add New Employee
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
                                    <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                    <th><i class="fas fa-camera me-2"></i>Photo</th>
                                    <th><i class="fas fa-user me-2"></i>Name</th>
                                    <th><i class="fas fa-at me-2"></i>Email</th>
                                    <th><i class="fas fa-building me-2"></i>Department</th>
                                    <th><i class="fas fa-briefcase me-2"></i>Position</th>
                                    <th><i class="fas fa-user-tag me-2"></i>Type</th>
                                    <th><i class="fas fa-clock me-2"></i>Probation Period</th>
                                    <th><i class="fas fa-ellipsis-h me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($employee->employee_id); ?></td>
                                    <td>
                                        <?php if($employee->photo): ?>
                                            <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>" alt="Employee Photo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($employee->full_name); ?></td>
                                    <td><?php echo e($employee->email); ?></td>
                                    <td><?php echo e($employee->department); ?></td>
                                    <td><?php echo e($employee->position); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo e($employee->type === 'onrole' ? 'primary' : 'info'); ?>">
                                            <?php echo e(ucfirst($employee->type)); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php if($employee->probation_start_date && $employee->probation_end_date): ?>
                                            <div class="small">
                                                <div><?php echo e($employee->probation_start_date->format('d M Y')); ?></div>
                                                <div class="text-muted">to</div>
                                                <div><?php echo e($employee->probation_end_date->format('d M Y')); ?></div>
                                                <?php if($employee->probation_status): ?>
                                                    <span class="badge bg-<?php echo e($employee->probation_status === 'active' ? 'warning' : ($employee->probation_status === 'completed' ? 'success' : ($employee->probation_status === 'extended' ? 'info' : 'danger'))); ?> mt-1">
                                                        <?php echo e(ucfirst($employee->probation_status)); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <button class="btn btn-sm btn-outline-primary mt-1 probation-btn" data-employee-id="<?php echo e($employee->id); ?>" data-employee-name="<?php echo e($employee->full_name); ?>" data-start-date="<?php echo e($employee->probation_start_date ? $employee->probation_start_date->format('Y-m-d') : ''); ?>" data-end-date="<?php echo e($employee->probation_end_date ? $employee->probation_end_date->format('Y-m-d') : ''); ?>" data-status="<?php echo e($employee->probation_status); ?>" title="Manage Probation">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">Not set</span>
                                            <button class="btn btn-sm btn-outline-success ms-2 probation-btn" data-employee-id="<?php echo e($employee->id); ?>" data-employee-name="<?php echo e($employee->full_name); ?>" data-start-date="" data-end-date="" data-status="" title="Set Probation Period">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.employees.show', $employee->id)); ?>" class="btn btn-sm btn-outline-info me-1" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.employees.edit', $employee->id)); ?>" class="btn btn-sm btn-outline-custom me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.employees.destroy', $employee->id)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted mb-2">No Employees Found</h5>
                                        <p class="text-muted mb-4">Start by adding your first employee to the system</p>
                                        <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn-custom">
                                            <i class="fas fa-plus me-2"></i>Add First Employee
                                        </a>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>

<!-- Probation Period Modal -->
<div class="modal fade" id="probationModal" tabindex="-1" aria-labelledby="probationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="probationModalLabel">
                    <i class="fas fa-clock me-2"></i>Manage Probation Period
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="probationForm">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong>Employee:</strong> <span id="modalEmployeeName"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Probation Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="probationStartDate" name="probation_start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Probation End Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="probationEndDate" name="probation_end_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Probation Status</label>
                            <select class="form-select" id="probationStatus" name="probation_status">
                                <option value="">Select Status</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                                <option value="extended">Extended</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Days Remaining</label>
                            <input type="text" class="form-control" id="daysRemaining" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-light border">
                                <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Probation Period Information</h6>
                                <ul class="mb-0 small">
                                    <li><strong>Active:</strong> Employee is currently in probation period</li>
                                    <li><strong>Completed:</strong> Probation period successfully completed</li>
                                    <li><strong>Extended:</strong> Probation period has been extended</li>
                                    <li><strong>Failed:</strong> Employee did not meet probation requirements</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-custom">
                        <i class="fas fa-save me-1"></i>Save Probation Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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

// Probation Modal Functionality
let currentEmployeeId = null;

document.querySelectorAll('.probation-btn').forEach(button => {
    button.addEventListener('click', function() {
        currentEmployeeId = this.getAttribute('data-employee-id');
        const employeeName = this.getAttribute('data-employee-name');
        const startDate = this.getAttribute('data-start-date');
        const endDate = this.getAttribute('data-end-date');
        const status = this.getAttribute('data-status');

        // Populate modal
        document.getElementById('modalEmployeeName').textContent = employeeName;
        document.getElementById('probationStartDate').value = startDate;
        document.getElementById('probationEndDate').value = endDate;
        document.getElementById('probationStatus').value = status;

        // Calculate days remaining
        calculateDaysRemaining();

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('probationModal'));
        modal.show();
    });
});

// Calculate days remaining in probation
function calculateDaysRemaining() {
    const endDate = document.getElementById('probationEndDate').value;
    if (endDate) {
        const today = new Date();
        const end = new Date(endDate);
        const diffTime = end - today;
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

        const daysElement = document.getElementById('daysRemaining');
        if (diffDays > 0) {
            daysElement.value = `${diffDays} days remaining`;
            daysElement.className = 'form-control text-success';
        } else if (diffDays === 0) {
            daysElement.value = 'Ends today';
            daysElement.className = 'form-control text-warning';
        } else {
            daysElement.value = `${Math.abs(diffDays)} days overdue`;
            daysElement.className = 'form-control text-danger';
        }
    } else {
        document.getElementById('daysRemaining').value = '';
    }
}

// Update days remaining when end date changes
document.getElementById('probationEndDate').addEventListener('change', calculateDaysRemaining);

// Handle form submission
document.getElementById('probationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    formData.append('employee_id', currentEmployeeId);

    fetch('<?php echo e(route("admin.employees.update-probation")); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal
            bootstrap.Modal.getInstance(document.getElementById('probationModal')).hide();

            // Show success message
            showAlert('Probation details updated successfully!', 'success');

            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showAlert('Error updating probation details: ' + (data.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while updating probation details.', 'danger');
    });
});

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/employees/index.blade.php ENDPATH**/ ?>