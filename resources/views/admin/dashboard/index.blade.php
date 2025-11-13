@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Dashboard Overview -->
        <div class="row mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>150</h3>
                        <p>Total Employees</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3>142</h3>
                        <p>Present Today</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3>8</h3>
                        <p>On Leave</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>95%</h3>
                        <p>Attendance Rate</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="section-card">
                        <div class="card-header">
                            <i class="fas fa-chart-line me-2"></i>Recent Activity
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                Welcome to the HR Admin Panel. Select a section from the navigation above to manage your employee attendance system.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="section-card">
                        <div class="card-header">
                            <i class="fas fa-calendar-day me-2"></i>Today's Summary
                        </div>
                        <div class="card-body">
                            <p><strong>Date:</strong> November 10, 2025</p>
                            <p><strong>Working Hours:</strong> 9:00 AM - 6:00 PM</p>
                            <p><strong>Total Check-ins:</strong> 142</p>
                            <p><strong>Late Arrivals:</strong> 3</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>
@endsection
