@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Salary Details Section -->
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-money-bill-wave me-2"></i>Salary Details</span>
                    <div>
                        <a href="{{ route('admin.accountability.edit', $salary->id) }}" class="btn btn-outline-custom me-2">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                        <a href="{{ route('admin.accountability.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Employee Information -->
                        <div class="col-md-4 text-center mb-4">
                            @if($salary->employee->photo)
                                <img src="{{ asset('storage/' . $salary->employee->photo) }}" alt="Employee Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid var(--primary-color);">
                            @else
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; border: 3px solid var(--primary-color);">
                                    <i class="fas fa-user fa-3x text-muted"></i>
                                </div>
                            @endif
                            <h4>{{ $salary->employee->full_name }}</h4>
                            <p class="text-muted mb-1">{{ $salary->employee->position }}</p>
                            <p class="text-muted">ID: {{ $salary->employee->employee_id }}</p>
                            <div class="mt-3">
                                <span class="badge bg-{{ $salary->status === 'active' ? 'success' : 'danger' }} fs-6">
                                    {{ ucfirst($salary->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Salary Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Basic Salary Info -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Salary Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Total Salary:</td>
                                            <td class="text-success fw-bold">₹{{ number_format($salary->salary_amount, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Effective Date:</td>
                                            <td>{{ $salary->effective_date->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">PAN Number:</td>
                                            <td>{{ $salary->pan_number ?: 'Not provided' }}</td>
                                        </tr>
                                        @if($salary->aadhaar_number)
                                        <tr>
                                            <td class="fw-bold">Aadhaar Number:</td>
                                            <td><span class="font-monospace">{{ $salary->aadhaar_number }}</span></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $salary->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($salary->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Bank Details -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-university me-2"></i>Bank Details</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Account Holder:</td>
                                            <td>{{ $salary->account_holder_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Account Number:</td>
                                            <td>{{ $salary->account_number }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Bank Name:</td>
                                            <td>{{ $salary->bank_name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">IFSC Code:</td>
                                            <td>{{ $salary->ifsc_code }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Branch:</td>
                                            <td>{{ $salary->branch_name ?: 'Not specified' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Salary Breakdown -->
                            @if($salary->basic_salary || $salary->hra || $salary->conveyance || $salary->medical_allowance || $salary->lta || $salary->special_allowance)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-calculator me-2"></i>Salary Breakdown</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>Earnings</h6>
                                            <table class="table table-sm">
                                                @if($salary->basic_salary)
                                                <tr>
                                                    <td>Basic Salary:</td>
                                                    <td class="text-end">₹{{ number_format($salary->basic_salary, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($salary->hra)
                                                <tr>
                                                    <td>HRA:</td>
                                                    <td class="text-end">₹{{ number_format($salary->hra, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($salary->conveyance)
                                                <tr>
                                                    <td>Conveyance:</td>
                                                    <td class="text-end">₹{{ number_format($salary->conveyance, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($salary->medical_allowance)
                                                <tr>
                                                    <td>Medical Allowance:</td>
                                                    <td class="text-end">₹{{ number_format($salary->medical_allowance, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($salary->lta)
                                                <tr>
                                                    <td>LTA:</td>
                                                    <td class="text-end">₹{{ number_format($salary->lta, 2) }}</td>
                                                </tr>
                                                @endif
                                                @if($salary->special_allowance)
                                                <tr>
                                                    <td>Special Allowance:</td>
                                                    <td class="text-end">₹{{ number_format($salary->special_allowance, 2) }}</td>
                                                </tr>
                                                @endif
                                                <tr class="table-primary fw-bold">
                                                    <td>Total Earnings:</td>
                                                    <td class="text-end">₹{{ number_format($salary->total_earnings, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Deductions</h6>
                                            <table class="table table-sm">
                                                @if($salary->provident_fund)
                                                <tr>
                                                    <td>Provident Fund:</td>
                                                    <td class="text-end">₹{{ number_format($salary->provident_fund, 2) }}</td>
                                                </tr>
                                                @endif
                                                <tr class="table-warning fw-bold">
                                                    <td>Total Deductions:</td>
                                                    <td class="text-end">₹{{ number_format($salary->total_deductions, 2) }}</td>
                                                </tr>
                                                <tr class="table-success fw-bold">
                                                    <td>Net Salary:</td>
                                                    <td class="text-end">₹{{ number_format($salary->net_salary, 2) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Remarks -->
                            @if($salary->remarks)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-sticky-note me-2"></i>Remarks</h5>
                                    <div class="alert alert-info">
                                        {{ $salary->remarks }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
