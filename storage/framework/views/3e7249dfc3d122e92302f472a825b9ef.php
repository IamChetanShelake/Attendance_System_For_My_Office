

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-user-shield me-2"></i>Employee Login Management
                </h1>
                <p class="page-subtitle">Manage employee login credentials for mobile app access</p>
            </div>

            <!-- Action Buttons -->
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <?php if($employeesWithoutLogin->count() > 0): ?>
                        <a href="<?php echo e(route('admin.employee-logins.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Generate Login Credentials
                        </a>
                    <?php endif; ?>
                </div>
                <div class="d-flex align-items-center">
                    <span class="badge bg-info me-3"><?php echo e($employeeLogins->total()); ?> Total Logins</span>
                </div>
            </div>

            <!-- Existing Login Credentials -->
            <?php if($employeeLogins->count() > 0): ?>
                <div class="section-card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-key me-2"></i>Active Login Credentials (<?php echo e($employeeLogins->total()); ?>)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th><i class="fas fa-user me-1"></i>Employee</th>
                                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                                        <th><i class="fas fa-id-card me-1"></i>Employee ID</th>
                                        <th><i class="fas fa-calendar-alt me-1"></i>Last Login</th>
                                        <th><i class="fas fa-shield-alt me-1"></i>Status</th>
                                        <th><i class="fas fa-cogs me-1"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $employeeLogins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $login): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($login->employee && $login->employee->photo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $login->employee->photo)); ?>"
                                                             class="rounded-circle me-2"
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                             style="width: 40px; height: 40px; font-size: 16px; font-weight: bold;">
                                                            <?php echo e(strtoupper(substr($login->employee->first_name ?? 'N', 0, 1))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <p class="mb-0 fw-bold"><?php echo e($login->employee->full_name ?? 'N/A'); ?></p>
                                                        <small class="text-muted"><?php echo e($login->employee->position ?? ''); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-mono"><?php echo e($login->email); ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($login->employee->employee_id ?? 'N/A'); ?></span>
                                            </td>
                                            <td>
                                                <span class="text-muted"><?php echo e($login->formatted_last_login); ?></span>
                                            </td>
                                            <td>
                                                <?php echo $login->status_badge; ?>

                                                <?php if($login->login_attempts >= 3): ?>
                                                    <br><small class="text-danger">Failed attempts: <?php echo e($login->login_attempts); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('admin.employee-logins.show', $login->id)); ?>"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.employee-logins.edit', $login->id)); ?>"
                                                       class="btn btn-sm btn-outline-warning"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm <?php echo e($login->is_active ? 'btn-outline-danger' : 'btn-outline-success'); ?> toggle-status"
                                                            data-id="<?php echo e($login->id); ?>"
                                                            title="<?php echo e($login->is_active ? 'Deactivate' : 'Activate'); ?>">
                                                        <i class="fas fa-<?php echo e($login->is_active ? 'ban' : 'check'); ?>"></i>
                                                    </button>
                                                    <form action="<?php echo e(route('admin.employee-logins.reset-password', $login->id)); ?>" method="POST" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('POST'); ?>
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-primary"
                                                                title="Reset Password"
                                                                onclick="return confirm('Are you sure you want to reset the password? This will generate a new random password.')">
                                                            <i class="fas fa-key"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if($employeeLogins->hasPages()): ?>
                            <div class="d-flex justify-content-center mt-4">
                                <?php echo e($employeeLogins->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="section-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-key fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Login Credentials Yet</h4>
                        <p class="text-muted mb-4">No employees have login credentials generated yet.</p>
                        <?php if($employeesWithoutLogin->count() > 0): ?>
                            <a href="<?php echo e(route('admin.employee-logins.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Generate First Login Credentials
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Employees Without Login Credentials -->
            <?php if($employeesWithoutLogin->count() > 0): ?>
                <div class="section-card mt-4">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Employees Without Login Credentials (<?php echo e($employeesWithoutLogin->count()); ?>)</h5>
                        <a href="<?php echo e(route('admin.employee-logins.create')); ?>" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>Generate for Selected
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php $__currentLoopData = $employeesWithoutLogin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <?php if($employee->photo): ?>
                                                    <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>"
                                                         class="rounded-circle"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px; font-size: 20px; font-weight: bold;">
                                                        <?php echo e(strtoupper(substr($employee->first_name, 0, 1))); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1"><?php echo e($employee->full_name); ?></h6>
                                                <p class="mb-1 text-muted"><?php echo e($employee->position); ?></p>
                                                <small class="text-muted">ID: <?php echo e($employee->employee_id); ?></small>
                                            </div>
                                            <div>
                                                <form action="<?php echo e(route('admin.employee-logins.generate-for-employee', $employee->id)); ?>" method="POST" style="display: inline;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('POST'); ?>
                                                    <button type="submit"
                                                            class="btn btn-sm btn-warning"
                                                            title="Generate Login Credentials">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const loginId = this.getAttribute('data-id');
            const isActive = this.querySelector('i').classList.contains('fa-ban');

            if (confirm(`Are you sure you want to ${isActive ? 'deactivate' : 'activate'} this login account?`)) {
                // Create a form to submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/employee-logins/${loginId}/toggle-status`;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfToken);

                // Add method field for PUT request
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);

                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>

<style>
.fw-mono {
    font-family: 'Courier New', monospace;
    font-size: 0.9em;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/employee-logins/index.blade.php ENDPATH**/ ?>