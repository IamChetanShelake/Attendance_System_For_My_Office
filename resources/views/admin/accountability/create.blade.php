@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Add Salary Details Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-money-bill-wave me-2"></i>Add Salary Details
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accountability.store') }}" method="POST">
                        @csrf

                        <!-- Employee Selection -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-user me-2"></i>Select Employee</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                                    <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->employee_id }} - {{ $employee->full_name }} ({{ $employee->department }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if($employees->isEmpty())
                                        <div class="alert alert-warning mt-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No employees available without salary details. <a href="{{ route('admin.employees.create') }}">Add employees first</a>.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Salary Information -->
                        <h5 class="mb-3"><i class="fas fa-money-bill-wave me-2"></i>Salary Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Total Salary Amount (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="salary_amount" class="form-control @error('salary_amount') is-invalid @enderror" value="{{ old('salary_amount') }}" step="0.01" min="0" required>
                                @error('salary_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Effective Date <span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" class="form-control @error('effective_date') is-invalid @enderror" value="{{ old('effective_date', date('Y-m-d')) }}" required>
                                @error('effective_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="pan_number" class="form-control @error('pan_number') is-invalid @enderror" value="{{ old('pan_number') }}" maxlength="10" placeholder="AAAAA0000A">
                                @error('pan_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" class="form-control @error('aadhaar_number') is-invalid @enderror" value="{{ old('aadhaar_number') }}" maxlength="12" placeholder="123456789012">
                                <div class="form-text">Enter 12-digit Aadhaar number (optional)</div>
                                @error('aadhaar_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Bank Details -->
                        <h5 class="mb-3"><i class="fas fa-university me-2"></i>Bank Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Account Holder Name <span class="text-danger">*</span></label>
                                <input type="text" name="account_holder_name" class="form-control @error('account_holder_name') is-invalid @enderror" value="{{ old('account_holder_name') }}" required>
                                @error('account_holder_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Account Number <span class="text-danger">*</span></label>
                                <input type="text" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" required>
                                @error('account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Bank Name <span class="text-danger">*</span></label>
                                <input type="text" name="bank_name" class="form-control @error('bank_name') is-invalid @enderror" value="{{ old('bank_name') }}" required>
                                @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">IFSC Code <span class="text-danger">*</span></label>
                                <input type="text" name="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror" value="{{ old('ifsc_code') }}" maxlength="11" required>
                                @error('ifsc_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch Name</label>
                                <input type="text" name="branch_name" class="form-control @error('branch_name') is-invalid @enderror" value="{{ old('branch_name') }}">
                                @error('branch_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Salary Breakdown -->
                        <h5 class="mb-3"><i class="fas fa-calculator me-2"></i>Salary Breakdown (Optional)</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Basic Salary (₹)</label>
                                <input type="number" name="basic_salary" class="form-control @error('basic_salary') is-invalid @enderror" value="{{ old('basic_salary') }}" step="0.01" min="0">
                                @error('basic_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">HRA (₹)</label>
                                <input type="number" name="hra" class="form-control @error('hra') is-invalid @enderror" value="{{ old('hra') }}" step="0.01" min="0">
                                @error('hra')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Conveyance (₹)</label>
                                <input type="number" name="conveyance" class="form-control @error('conveyance') is-invalid @enderror" value="{{ old('conveyance') }}" step="0.01" min="0">
                                @error('conveyance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Medical Allowance (₹)</label>
                                <input type="number" name="medical_allowance" class="form-control @error('medical_allowance') is-invalid @enderror" value="{{ old('medical_allowance') }}" step="0.01" min="0">
                                @error('medical_allowance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">LTA (₹)</label>
                                <input type="number" name="lta" class="form-control @error('lta') is-invalid @enderror" value="{{ old('lta') }}" step="0.01" min="0">
                                @error('lta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Special Allowance (₹)</label>
                                <input type="number" name="special_allowance" class="form-control @error('special_allowance') is-invalid @enderror" value="{{ old('special_allowance') }}" step="0.01" min="0">
                                @error('special_allowance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Provident Fund (₹)</label>
                                <input type="number" name="provident_fund" class="form-control @error('provident_fund') is-invalid @enderror" value="{{ old('provident_fund') }}" step="0.01" min="0">
                                @error('provident_fund')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control @error('remarks') is-invalid @enderror" rows="3">{{ old('remarks') }}</textarea>
                            @error('remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-custom btn-lg me-2">
                                <i class="fas fa-save me-2"></i>Create Salary Record
                            </button>
                            <a href="{{ route('admin.accountability.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
