@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Make Attendance Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-clock me-2"></i>Make Attendance
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user me-2"></i>Select Employee
                                </label>
                                <select class="form-select">
                                    <option selected>Choose employee...</option>
                                    <option value="1">John Doe</option>
                                    <option value="2">Jane Smith</option>
                                    <option value="3">Mike Johnson</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar me-2"></i>Date
                                </label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sign-in-alt me-2"></i>Check In Time
                                </label>
                                <input type="time" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-sign-out-alt me-2"></i>Check Out Time
                                </label>
                                <input type="time" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-custom btn-lg">
                            <i class="fas fa-save me-2"></i>Submit Attendance
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</main>
@endsection
