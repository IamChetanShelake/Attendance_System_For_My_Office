@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Rules Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-cogs me-2"></i>Attendance Rules & Policies
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="rule-card">
                                <h5><i class="fas fa-clock me-2"></i>Punch In / Punch Out</h5>
                                <p>Standard working hours: 9:00 AM - 6:00 PM</p>
                                <button class="btn btn-outline-custom btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rule-card">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i>Half Day / Late Mark</h5>
                                <p>Late after 9:30 AM, Half day if check out before 2:00 PM</p>
                                <button class="btn btn-outline-custom btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="rule-card">
                                <h5><i class="fas fa-calendar-week me-2"></i>Saturday / Sunday Rule</h5>
                                <p>Saturday: Optional, Sunday: Off day</p>
                                <button class="btn btn-outline-custom btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="rule-card">
                                <h5><i class="fas fa-link me-2"></i>Consecutive Rules</h5>
                                <p>Maximum 3 consecutive late marks allowed</p>
                                <button class="btn btn-outline-custom btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="rule-card">
                                <h5><i class="fas fa-stopwatch me-2"></i>Timings and Conditions</h5>
                                <p>Break time: 1 hour, Overtime calculation: 1.5x after 8 hours</p>
                                <button class="btn btn-outline-custom btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>
@endsection
