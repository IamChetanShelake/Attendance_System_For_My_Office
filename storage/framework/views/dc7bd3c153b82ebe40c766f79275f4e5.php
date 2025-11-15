

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-cogs me-2"></i>Office Rules Management
                </h1>
                <p class="page-subtitle">Comprehensive office policies for attendance, leave, and employee conduct</p>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-list"></i>
                        </div>
                        <h3><?php echo e($stats['total_rules']); ?></h3>
                        <p>Total Rules</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3><?php echo e($stats['active_rules']); ?></h3>
                        <p>Active Rules</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <h3><?php echo e($stats['attendance_rules']); ?></h3>
                        <p>Attendance Rules</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3><?php echo e($stats['leave_rules'] + $stats['holiday_rules']); ?></h3>
                        <p>Leave & Holiday Rules</p>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="section-card mb-4">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-list me-2"></i>
                        <span>Office Rules</span>
                        <span class="badge bg-light text-dark ms-2"><?php echo e($rules->total()); ?></span>
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.office-rules.create')); ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-plus me-2"></i>Add New Rule
                        </a>
                        <button class="btn btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterSection">
                            <i class="fas fa-filter me-2"></i>Filters
                        </button>
                        <?php if($rules->isEmpty()): ?>
                            <form method="POST" action="<?php echo e(route('admin.office-rules.create-default')); ?>" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-info btn-lg">
                                    <i class="fas fa-magic me-2"></i>Create Default Rules
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="collapse show" id="filterSection">
                    <div class="card-body border-bottom bg-light">
                        <form method="GET" action="<?php echo e(route('admin.office-rules.index')); ?>" class="row g-3" id="filterForm">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Rule Category</label>
                                <select name="rule_category" class="form-select">
                                    <option value="">All Categories</option>
                                    <?php $__currentLoopData = $ruleCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('rule_category') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Rule Type</label>
                                <select name="rule_type" class="form-select">
                                    <option value="">All Types</option>
                                    <?php $__currentLoopData = $ruleTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(request('rule_type') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="inactive" <?php echo e(request('status') == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="<?php echo e(route('admin.office-rules.index')); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i>Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Rules List -->
                <div class="card-body">
                    <?php $__empty_1 = true; $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="rule-card-item mb-3 <?php echo e($rule->isCurrentlyEffective() ? 'border-primary' : 'border-secondary'); ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <h5 class="mb-0 me-3">
                                            <i class="fas fa-<?php echo e(getRuleIcon($rule->rule_type)); ?> me-2"></i>
                                            <?php echo e($rule->rule_name); ?>

                                        </h5>
                                        <?php echo $rule->status_badge; ?>

                                        <?php if($rule->priority > 0): ?>
                                            <span class="badge bg-info ms-2">
                                                Priority: <?php echo e($rule->priority); ?>

                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-2">
                                        <span class="badge bg-secondary me-2"><?php echo e($rule->category_name); ?></span>
                                        <span class="badge bg-info"><?php echo e($rule->rule_type_name); ?></span>
                                    </div>

                                    <?php if($rule->rule_description): ?>
                                        <p class="text-muted mb-2"><?php echo e(Str::limit($rule->rule_description, 150)); ?></p>
                                    <?php endif; ?>

                                    <div class="settings-summary small text-muted mb-2">
                                        <i class="fas fa-cog me-1"></i>
                                        <strong>Settings:</strong>
                                        <?php echo e(generateSettingsSummary($rule)); ?>

                                    </div>

                                    <div class="rule-meta small text-muted">
                                        <?php if($rule->effective_period !== 'Always'): ?>
                                            <div class="mb-1">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                <strong>Effective:</strong> <?php echo e($rule->effective_period); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <i class="fas fa-building me-1"></i>
                                            <strong>Company Policy</strong> - Updated <?php echo e($rule->updated_at->format('M j, Y')); ?>

                                        </div>
                                    </div>
                                </div>

                                <div class="action-buttons ms-3">
                                    <div class="btn-group-vertical" role="group">
                                        <a href="<?php echo e(route('admin.office-rules.show', $rule)); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.office-rules.edit', $rule)); ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.office-rules.toggle-status', $rule)); ?>" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm <?php echo e($rule->is_active ? 'btn-outline-warning' : 'btn-outline-success'); ?>">
                                                <i class="fas fa-<?php echo e($rule->is_active ? 'pause' : 'play'); ?>"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.office-rules.destroy', $rule)); ?>"
                                              class="d-inline"
                                              onsubmit="return confirmDelete('<?php echo e($rule->rule_name); ?>')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-cogs fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Office Rules Found</h4>
                            <p class="text-muted mb-4">
                                <?php if(request()->has('rule_type') || request()->has('rule_category') || request()->has('status')): ?>
                                    No rules match your filter criteria.
                                    <a href="<?php echo e(route('admin.office-rules.index')); ?>" class="ms-1">Clear filters</a>
                                <?php else: ?>
                                    Get started by creating your first office rule or use the default rules template.
                                <?php endif; ?>
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="<?php echo e(route('admin.office-rules.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Add First Rule
                                </a>
                                <?php if($rules->isEmpty()): ?>
                                    <form method="POST" action="<?php echo e(route('admin.office-rules.create-default')); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-magic me-2"></i>Use Default Rules
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <?php if($rules->hasPages()): ?>
                    <div class="card-footer bg-light">
                        <?php echo e($rules->appends(request()->query())->links()); ?>

                    </div>
                <?php endif; ?>
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

<?php
    function getRuleIcon($ruleType) {
        return match($ruleType) {
            'office_timing' => 'clock',
            'late_mark' => 'exclamation-triangle',
            'half_day' => 'stopwatch',
            'weekend_policy' => 'calendar-week',
            'consecutive_leave' => 'link',
            'holiday_consecutive' => 'calendar-alt',
            default => 'cog'
        };
    }

    function generateSettingsSummary($rule) {
        $settings = $rule->rule_settings;

        return match($rule->rule_type) {
            'office_timing' => "Hours: {$settings['start_time']} - {$settings['end_time']}, Working: {$settings['working_hours']}hrs",
            'late_mark' => "Late threshold: {$settings['late_threshold_minutes']}min, Max late marks: {$settings['max_late_marks_per_month']}/month",
            'half_day' => "Half-day hours: {$settings['half_day_hours']}, Threshold: {$settings['half_day_threshold']}",
            'weekend_policy' => "2nd Sat Off: " . ($settings['second_saturday_off'] ? 'Yes' : 'No') . ", 4th Sat Off: " . ($settings['fourth_saturday_off'] ? 'Yes' : 'No'),
            'consecutive_leave' => "Weekend consecutive: " . ($settings['weekend_consecutive']['enabled'] ? 'Enabled' : 'Disabled') . ", Max: {$settings['weekend_consecutive']['max_consecutive_days']} days",
            'holiday_consecutive' => "Days before: {$settings['before_holiday_consecutive']['days_before']}, Days after: {$settings['after_holiday_consecutive']['days_after']}",
            default => 'Custom configuration'
        };
    }
?>

<script>
function confirmDelete(ruleName) {
    return confirm(`Are you sure you want to delete the rule "${ruleName}"?\n\nThis action cannot be undone.`);
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #0069d9 100%) !important;
}

.rule-card-item {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    background: white;
}

.rule-card-item:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(78, 180, 230, 0.1);
    transform: translateY(-2px);
}

.rule-card-item.border-primary {
    border-color: var(--primary-color) !important;
    background: rgba(78, 180, 230, 0.02);
}

.rule-card-item h5 {
    color: var(--secondary-color);
    font-weight: 600;
}

.badge {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 0.375rem 0.5rem;
}

.btn-group-vertical .btn {
    border-radius: 0.25rem !important;
    margin-bottom: 0.25rem;
}

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

.settings-summary {
    font-style: italic;
}

.rule-meta {
    border-top: 1px solid #f8f9fa;
    padding-top: 0.5rem;
    margin-top: 0.5rem;
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
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/rules/index.blade.php ENDPATH**/ ?>