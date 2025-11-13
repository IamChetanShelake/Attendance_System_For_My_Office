

<?php $__env->startSection('content'); ?>
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Set Annual Holidays Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt me-2"></i>Set Annual Holidays
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-gift me-2"></i>Holiday Name
                                </label>
                                <input type="text" class="form-control" placeholder="Enter holiday name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar-day me-2"></i>Holiday Date
                                </label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="text-center mb-4">
                        <button class="btn btn-custom">
                            <i class="fas fa-plus me-2"></i>Add Holiday
                        </button>
                    </div>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>2025 Holiday Calendar
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-calendar me-2"></i>Month</th>
                                        <th><i class="fas fa-gift me-2"></i>Holidays</th>
                                        <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>January</td>
                                        <td>New Year's Day (1st)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-custom me-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>August</td>
                                        <td>Independence Day (15th)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-custom me-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>December</td>
                                        <td>Christmas (25th)</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-custom me-1">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\LENOVO\Desktop\Tms_attendance_system\tms_attendance\resources\views/admin/holidays/index.blade.php ENDPATH**/ ?>