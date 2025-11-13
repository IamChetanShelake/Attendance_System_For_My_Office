@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Leave Management Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-calendar-times me-2"></i>Leave Management
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <button class="btn btn-custom">
                                <i class="fas fa-plus me-2"></i>Approve Leave Request
                            </button>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-outline-custom">
                                <i class="fas fa-list me-2"></i>View All Requests
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user me-2"></i>Employee</th>
                                    <th><i class="fas fa-tag me-2"></i>Leave Type</th>
                                    <th><i class="fas fa-calendar-plus me-2"></i>Start Date</th>
                                    <th><i class="fas fa-calendar-minus me-2"></i>End Date</th>
                                    <th><i class="fas fa-info-circle me-2"></i>Status</th>
                                    <th><i class="fas fa-cogs me-2"></i>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>John Doe</td>
                                    <td>Sick Leave</td>
                                    <td>2023-10-15</td>
                                    <td>2023-10-16</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jane Smith</td>
                                    <td>Annual Leave</td>
                                    <td>2023-10-20</td>
                                    <td>2023-10-25</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mike Johnson</td>
                                    <td>Casual Leave</td>
                                    <td>2023-10-18</td>
                                    <td>2023-10-18</td>
                                    <td><span class="badge bg-danger">Rejected</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-eye"></i>
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
</main>
@endsection
