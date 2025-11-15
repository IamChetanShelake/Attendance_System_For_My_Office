

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Documents Management Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-file-alt me-2"></i>Documents Management
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i><?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <a href="<?php echo e(route('admin.documents.create')); ?>" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Upload Documents
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

                    <div class="row">
                        <?php $__empty_1 = true; $__currentLoopData = $employeesWithDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 employee-document-card">
                                <div class="card-body text-center">
                                    <?php if($employee->photo): ?>
                                        <img src="<?php echo e(asset('storage/' . $employee->photo)); ?>" alt="Employee Photo" class="rounded-circle mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="fas fa-user fa-2x text-muted"></i>
                                        </div>
                                    <?php endif; ?>

                                    <h5 class="card-title mb-1"><?php echo e($employee->full_name); ?></h5>
                                    <p class="text-muted mb-2"><?php echo e($employee->position); ?></p>
                                    <p class="text-muted small mb-3">ID: <?php echo e($employee->employee_id); ?></p>

                                    <div class="document-count mb-3">
                                        <span class="badge bg-primary fs-6">
                                            <i class="fas fa-file me-1"></i><?php echo e($employee->documents_count); ?> Document<?php echo e($employee->documents_count !== 1 ? 's' : ''); ?>

                                        </span>
                                    </div>

                                    <div class="recent-documents mb-3">
                                        <?php if($employee->documents?->count()): ?>
                                            <small class="text-muted">Recent documents:</small>
                                            <ul class="list-unstyled small mt-1">
                                                <?php $__currentLoopData = $employee->documents->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="text-truncate" title="<?php echo e($document->document_name); ?>">
                                                    <i class="fas <?php echo e($document->file_icon); ?> me-1 text-primary"></i>
                                                    <?php echo e($document->document_name); ?>

                                                </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($employee->documents->count() > 2): ?>
                                                <li class="text-muted">+<?php echo e($employee->documents->count() - 2); ?> more...</li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?php echo e(route('admin.documents.employee', $employee->id)); ?>" class="btn btn-custom btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Documents
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-file-alt fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">No Documents Found</h4>
                            <p class="text-muted mb-4">No employees have uploaded documents yet.</p>
                            <a href="<?php echo e(route('admin.documents.create')); ?>" class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Upload First Document
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.employee-document-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.employee-document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.employee-document-card .card-body {
    padding: 2rem 1.5rem;
}

.document-count .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.recent-documents ul li {
    margin-bottom: 0.25rem;
    padding: 0.125rem 0;
}
</style>

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
    const cards = document.querySelectorAll('.employee-document-card');

    cards.forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/documents/index.blade.php ENDPATH**/ ?>