@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Allocate Accessory Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-plus me-2"></i>Allocate Accessory to Employee
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.accessories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Employee <span class="text-danger">*</span></label>
                                <select class="form-select @error('employee_id') is-invalid @enderror" id="employee_id" name="employee_id" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->employee_id }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="accessory_name" class="form-label">Accessory Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('accessory_name') is-invalid @enderror" id="accessory_name" name="accessory_name" value="{{ old('accessory_name') }}" placeholder="e.g., Laptop, Monitor, Keyboard" required>
                                @error('accessory_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="serial_number" class="form-label">Serial Number</label>
                                <input type="text" class="form-control @error('serial_number') is-invalid @enderror" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" placeholder="Serial/Model number">
                                @error('serial_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="model_number" class="form-label">Model Number</label>
                                <input type="text" class="form-control @error('model_number') is-invalid @enderror" id="model_number" name="model_number" value="{{ old('model_number') }}" placeholder="Model number">
                                @error('model_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="value" class="form-label">Value/Cost (₹)</label>
                                <input type="number" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" step="0.01" min="0" placeholder="Enter value in rupees">
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="allocation_date" class="form-label">Allocation Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('allocation_date') is-invalid @enderror" id="allocation_date" name="allocation_date" value="{{ old('allocation_date', date('Y-m-d')) }}" required>
                                @error('allocation_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" class="form-control @error('return_date') is-invalid @enderror" id="return_date" name="return_date" value="{{ old('return_date') }}">
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty if not returned yet</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="allocated" {{ old('status') == 'allocated' ? 'selected' : '' }}>Allocated</option>
                                    <option value="returned" {{ old('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                                    <option value="lost" {{ old('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                                    <option value="damaged" {{ old('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Describe the accessory">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="condition_notes" class="form-label">Condition Notes</label>
                            <textarea class="form-control @error('condition_notes') is-invalid @enderror" id="condition_notes" name="condition_notes" rows="2" placeholder="Any notes about the condition when allocated">{{ old('condition_notes') }}</textarea>
                            @error('condition_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Accessory Photo</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload a photo of the accessory (JPEG, PNG, JPG, GIF - Max 2MB)</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.accessories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Accessories
                            </a>
                            <button type="submit" class="btn btn-custom">
                                <i class="fas fa-save me-2"></i>Allocate Accessory
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Auto-fill return date when status is changed to returned
document.getElementById('status').addEventListener('change', function() {
    const returnDateField = document.getElementById('return_date');
    if (this.value === 'returned' && !returnDateField.value) {
        returnDateField.value = new Date().toISOString().split('T')[0];
    }
});

// Set minimum date for allocation date to today
document.getElementById('allocation_date').min = new Date().toISOString().split('T')[0];
</script>
@endsection
