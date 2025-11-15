

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-calendar-times me-2"></i>Leave Management System
                </h1>
                <p class="page-subtitle">Comprehensive leave tracking and management for employee ensurity</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card" data-stat="total">
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3><?php echo e($stats['total_leaves']); ?></h3>
                        <p>Total Leaves</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" data-stat="pending">
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3><?php echo e($stats['pending_leaves']); ?></h3>
                        <p>Pending Approval</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" data-stat="approved">
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3><?php echo e($stats['approved_leaves']); ?></h3>
                        <p>Approved</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card" data-stat="month">
                        <div class="icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                        <h3><?php echo e($stats['this_month_leaves']); ?></h3>
                        <p>This Month</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="section-card mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-list me-2"></i>
                        <span>Leave Records</span>
                        <span class="badge bg-light text-dark ms-2"><?php echo e($leaves->total()); ?></span>
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.employee-leave.create')); ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Add Leave Record
                        </a>
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection">
                            <i class="fas fa-filter me-2"></i>Filters
                        </button>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="collapse show" id="filterSection">
                    <div class="card-body border-bottom bg-light">
                        <form method="GET" action="<?php echo e(route('admin.employee-leave.index')); ?>" class="row g-3" id="filterForm">
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                                    <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Leave Type</label>
                                <select name="leave_type" class="form-select">
                                    <option value="">All Types</option>
                                    <?php $__currentLoopData = \App\Models\Leave::getLeaveTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('leave_type') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Employee</label>
                                <select name="employee_id" class="form-select">
                                    <option value="">All Employees</option>
                                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($employee->id); ?>" <?php echo e(request('employee_id') == $employee->id ? 'selected' : ''); ?>>
                                            <?php echo e($employee->employee_id); ?> - <?php echo e($employee->full_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">From Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?php echo e(request('start_date')); ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">To Date</label>
                                <input type="date" name="end_date" class="form-control" value="<?php echo e(request('end_date')); ?>">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label fw-bold">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12 text-end">
                                <a href="<?php echo e(route('admin.employee-leave.index')); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-undo me-1"></i>Clear Filters
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Bulk Actions -->
                <form method="POST" action="<?php echo e(route('admin.employee-leave.bulk-action')); ?>" id="bulkActionForm">
                    <?php echo csrf_field(); ?>
                    <div class="card-body border-bottom bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <div class="form-check d-inline-block me-3">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                                <label class="form-check-label fw-bold" for="selectAll">
                                    Select All
                                </label>
                            </div>
                            <div class="d-inline-block" id="bulkActions" style="display: none;">
                                <button type="button" class="btn btn-sm btn-success me-2" onclick="bulkAction('approve')">
                                    <i class="fas fa-check me-1"></i>Approve Selected
                                </button>
                                <button type="button" class="btn btn-sm btn-danger me-2" onclick="bulkAction('reject')">
                                    <i class="fas fa-times me-1"></i>Reject Selected
                                </button>
                                <button type="button" class="btn btn-sm btn-dark" onclick="bulkAction('delete')">
                                    <i class="fas fa-trash me-1"></i>Delete Selected
                                </button>
                            </div>
                        </div>
                        <div>
                            <small class="text-muted">
                                Showing <?php echo e($leaves->firstItem()); ?>-<?php echo e($leaves->lastItem()); ?> of <?php echo e($leaves->total()); ?> results
                            </small>
                        </div>
                    </div>

                    <!-- Leave Records Table -->
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30">
                                            <input class="form-check-input" type="checkbox" id="selectAllHeader">
                                        </th>
                                        <th><i class="fas fa-user me-2"></i>Employee</th>
                                        <th><i class="fas fa-tag me-2"></i>Leave Type</th>
                                        <th><i class="fas fa-calendar me-2"></i>Duration</th>
                                        <th><i class="fas fa-calendar-day me-2"></i>Date Range</th>
                                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                        <th><i class="fas fa-clock me-2"></i>Submitted</th>
                                        <th width="200"><i class="fas fa-cogs me-2"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr class="position-relative">
                                            <td>
                                                <?php if($leave->status === 'pending'): ?>
                                                    <input class="form-check-input leave-checkbox" type="checkbox"
                                                           name="leave_ids[]" value="<?php echo e($leave->id); ?>">
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if($leave->employee->photo): ?>
                                                        <img src="<?php echo e(asset('storage/' . $leave->employee->photo)); ?>"
                                                             class="rounded-circle me-2"
                                                             style="width: 35px; height: 35px; object-fit: cover;">
                                                    <?php else: ?>
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                                             style="width: 35px; height: 35px; font-weight: bold; font-size: 0.9rem;">
                                                            <?php echo e(strtoupper(substr($leave->employee->first_name, 0, 1))); ?>

                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-bold"><?php echo e($leave->employee->full_name); ?></div>
                                                        <small class="text-muted"><?php echo e($leave->employee->employee_id); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary"><?php echo e($leave->formatted_leave_type); ?></span>
                                                <?php if($leave->emergency): ?>
                                                    <br><span class="badge bg-danger mt-1">🚨 Emergency</span>
                                                <?php endif; ?>
                                                <?php if($leave->is_half_day): ?>
                                                    <br><span class="badge bg-info mt-1">⏱️ Half Day</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary"><?php echo e($leave->days_count); ?></span>
                                                <small class="d-block text-muted"><?php echo e($leave->is_half_day ? 'half day' : 'day' . ($leave->days_count > 1 ? 's' : '')); ?></small>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <span class="fw-bold"><?php echo e($leave->date_range); ?></span>
                                                </div>
                                                <?php if($leave->approved_at): ?>
                                                    <br><small class="text-muted">Approved: <?php echo e($leave->approved_at->format('M j, Y')); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $leave->status_badge; ?></td>
                                            <td>
                                                <div class="small">
                                                    <?php echo e($leave->submitted_at ? $leave->submitted_at->format('M j, Y') : 'N/A'); ?>

                                                    <br><small class="text-muted"><?php echo e($leave->submitted_at ? $leave->submitted_at->diffForHumans() : ''); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="<?php echo e(route('admin.employee-leave.show', $leave)); ?>"
                                                       class="btn btn-outline-primary btn-sm"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo e(route('admin.employee-leave.edit', $leave)); ?>"
                                                       class="btn btn-outline-secondary btn-sm"
                                                       title="Edit Record">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if($leave->status === 'pending'): ?>
                                                        <button type="button" class="btn btn-outline-success btn-sm"
                                                                onclick="showApprovalModal('<?php echo e($leave->id); ?>', 'approve', '<?php echo e($leave->employee->full_name); ?>')"
                                                                title="Approve Leave">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="showApprovalModal('<?php echo e($leave->id); ?>', 'reject', '<?php echo e($leave->employee->full_name); ?>')"
                                                                title="Reject Leave">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if($leave->status === 'approved' || $leave->status === 'rejected'): ?>
                                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                                onclick="printLeaveRecord('<?php echo e($leave->id); ?>')"
                                                                title="Print Record">
                                                            <i class="fas fa-print"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted mb-2">No Leave Records Found</h5>
                                                <p class="text-muted mb-4">
                                                    <?php if(request()->has('status') || request()->has('leave_type') || request()->has('employee_id')): ?>
                                                        No records match your filter criteria.
                                                        <a href="<?php echo e(route('admin.employee-leave.index')); ?>" class="ms-1">Clear filters</a>
                                                    <?php else: ?>
                                                        No leave records have been added yet.
                                                    <?php endif; ?>
                                                </p>
                                                <a href="<?php echo e(route('admin.employee-leave.create')); ?>" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i>Add First Leave Record
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <?php if($leaves->hasPages()): ?>
                        <div class="card-footer bg-light">
                            <?php echo e($leaves->appends(request()->query())->links()); ?>

                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Approval/Rejection Modal -->
            <div class="modal fade" id="approvalModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approvalModalTitle">
                                <i class="fas fa-question-circle me-2"></i>Confirm Action
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="approvalForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="modal-body">
                                <p id="approvalMessage"></p>
                                <div id="rejectionReasonDiv" style="display: none;">
                                    <label for="reason" class="form-label fw-bold">Rejection Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" id="reason" class="form-control" rows="3"
                                              placeholder="Please provide a reason for rejection..."></textarea>
                                </div>
                                <div>
                                    <label for="admin_notes" class="form-label fw-bold">HR Notes (Optional)</label>
                                    <textarea name="admin_notes" class="form-control" rows="2"
                                              placeholder="Additional notes from HR..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn" id="approvalBtn">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
                    <i class="fas fa-check-circle me-2"></i><strong>Success!</strong> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050;">
                    <i class="fas fa-exclamation-triangle me-2"></i><strong>Error!</strong> <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
// Global variables
let currentLeaveId = null;
let currentAction = null;

// Bulk selection functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.leave-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = this.checked;
    });
    updateBulkActionsVisibility();
});

document.getElementById('selectAllHeader').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.leave-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = this.checked;
    });
    updateBulkActionsVisibility();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('leave-checkbox')) {
        updateBulkActionsVisibility();
    }
});

function updateBulkActionsVisibility() {
    const selected = document.querySelectorAll('.leave-checkbox:checked').length;
    const bulkActions = document.getElementById('bulkActions');
    const selectAll = document.getElementById('selectAll');

    bulkActions.style.display = selected > 0 ? 'inline-block' : 'none';
    selectAll.textContent = selected > 0 ? `Selected (${selected})` : 'Select All';
}

function showApprovalModal(leaveId, action, employeeName) {
    currentLeaveId = leaveId;
    currentAction = action;

    const modal = new bootstrap.Modal(document.getElementById('approvalModal'));
    const title = document.getElementById('approvalModalTitle');
    const message = document.getElementById('approvalMessage');
    const reasonDiv = document.getElementById('rejectionReasonDiv');
    const btn = document.getElementById('approvalBtn');
    const form = document.getElementById('approvalForm');

    if (action === 'approve') {
        title.textContent = 'Approve Leave Request';
        message.textContent = `Are you sure you want to approve the leave request for ${employeeName}?`;
        reasonDiv.style.display = 'none';
        btn.className = 'btn btn-success';
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Approve';
        form.action = `/admin/employee-leave/${leaveId}/approve`;
    } else {
        title.textContent = 'Reject Leave Request';
        message.textContent = `Are you sure you want to reject the leave request for ${employeeName}?`;
        reasonDiv.style.display = 'block';
        btn.className = 'btn btn-danger';
        btn.innerHTML = '<i class="fas fa-times me-1"></i>Reject';
        form.action = `/admin/employee-leave/${leaveId}/reject`;
    }

    modal.show();
}

function bulkAction(action) {
    const selected = document.querySelectorAll('.leave-checkbox:checked');
    if (selected.length === 0) {
        alert('Please select at least one leave record.');
        return;
    }

    if (action === 'delete' && !confirm(`Are you sure you want to delete ${selected.length} leave record(s)?`)) {
        return;
    }

    if (action === 'approve' && !confirm(`Are you sure you want to approve ${selected.length} leave request(s)?`)) {
        return;
    }

    if (action === 'reject') {
        const reason = prompt(`Please provide a reason for rejecting ${selected.length} leave request(s):`);
        if (!reason || reason.trim() === '') {
            alert('Rejection reason is required.');
            return;
        }

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'reason';
        hiddenInput.value = reason;
        document.getElementById('bulkActionForm').appendChild(hiddenInput);
    }

    // Create hidden input for action
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = action;
    document.getElementById('bulkActionForm').appendChild(actionInput);

    document.getElementById('bulkActionForm').submit();
}

function printLeaveRecord(leaveId) {
    window.open(`/admin/employee-leave/${leaveId}/print`, '_blank');
}

// Real-time stats update
function updateStats() {
    // Could be enhanced with AJAX for real-time updates
    fetch('/admin/api/leave-stats', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(response => response.json())
        .then(data => {
            // Update stats cards with new data
            document.querySelector('[data-stat="total"] h3').textContent = data.total;
            document.querySelector('[data-stat="pending"] h3').textContent = data.pending;
            document.querySelector('[data-stat="approved"] h3').textContent = data.approved;
        })
        .catch(error => console.log('Stats update failed'));
}

// Update stats every 30 seconds
setInterval(updateStats, 30000);

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    updateBulkActionsVisibility();
});
</script>

<style>
/* Custom styles for leave management */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0069d9 100%) !important;
    color: white;
}

.position-relative {
    position: relative;
}

.badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.375rem 0.5rem;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 1rem 0.5rem;
}

.table tbody td {
    vertical-align: middle;
    padding: 1rem 0.5rem;
    transition: background-color 0.2s ease;
}

.table tbody tr:hover {
    background-color: rgba(78, 180, 230, 0.03);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.375rem;
    font-size: 0.8rem;
}

.stats-card {
    background: white;
    border-radius: 15px;
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.stats-card .icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.stats-card h3 {
    color: #495057;
    font-weight: 700;
    margin-bottom: 0.5rem;
    font-size: 2.2rem;
}

.stats-card p {
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0;
}

[data-stat="pending"] .icon { color: #ffc107; }
[data-stat="approved"] .icon { color: #28a745; }
[data-stat="rejected"] .icon { color: #dc3545; }
[data-stat="month"] .icon { color: #17a2b8; }

.alert-dismissible {
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Modal improvements */
.modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a9bc7 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modal-footer {
    border: none;
    border-radius: 0 0 15px 15px;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.9rem;
    }

    .btn-group {
        display: flex;
        flex-direction: column;
    }

    .btn-group .btn {
        border-radius: 0.25rem !important;
        margin-bottom: 0.25rem;
    }

    .stats-card h3 {
        font-size: 1.8rem;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/leave/index.blade.php ENDPATH**/ ?>