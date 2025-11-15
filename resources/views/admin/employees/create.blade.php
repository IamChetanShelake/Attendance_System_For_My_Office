@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Add New Employee Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-user-plus me-2"></i>Add New Employee
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Personal Information -->
                        <h5 class="mb-3"><i class="fas fa-user me-2"></i>Personal Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name') }}">
                                @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                                <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror" required>
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob') }}" required>
                                @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" class="form-control @error('aadhaar_number') is-invalid @enderror" value="{{ old('aadhaar_number') }}" placeholder="123456789012" maxlength="12">
                                <div class="form-text">12-digit Aadhaar number (optional)</div>
                                @error('aadhaar_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">6-Digit WFH PIN <span class="text-danger">*</span></label>
                                <input type="password" name="wfh_pin" class="form-control @error('wfh_pin') is-invalid @enderror" value="{{ old('wfh_pin') }}" pattern="[0-9]{6}" maxlength="6" placeholder="000000" required>
                                <div class="form-text">6-digit numeric PIN for WFH attendance verification</div>
                                @error('wfh_pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address') }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Professional Information -->
                        <h5 class="mb-3"><i class="fas fa-briefcase me-2"></i>Professional Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department') }}" required>
                                @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="intern" {{ old('type') == 'intern' ? 'selected' : '' }}>Intern</option>
                                    <option value="onrole" {{ old('type') == 'onrole' ? 'selected' : '' }}>On Role</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select name="position" class="form-select @error('position') is-invalid @enderror" required>
                                    <option value="">Select Position</option>
                                    <option value="php developer" {{ old('position') == 'php developer' ? 'selected' : '' }}>PHP Developer</option>
                                    <option value="react developer" {{ old('position') == 'react developer' ? 'selected' : '' }}>React Developer</option>
                                    <option value="uiux designer" {{ old('position') == 'uiux designer' ? 'selected' : '' }}>UI/UX Designer</option>
                                    <option value="node js developer" {{ old('position') == 'node js developer' ? 'selected' : '' }}>Node.js Developer</option>
                                    <option value="support engineer" {{ old('position') == 'support engineer' ? 'selected' : '' }}>Support Engineer</option>
                                    <option value="hr management" {{ old('position') == 'hr management' ? 'selected' : '' }}>HR Management</option>
                                    <option value="project manager" {{ old('position') == 'project manager' ? 'selected' : '' }}>Project Manager</option>
                                    <option value="business analyst" {{ old('position') == 'business analyst' ? 'selected' : '' }}>Business Analyst</option>
                                    <option value="qa engineer" {{ old('position') == 'qa engineer' ? 'selected' : '' }}>QA Engineer</option>
                                    <option value="devops engineer" {{ old('position') == 'devops engineer' ? 'selected' : '' }}>DevOps Engineer</option>
                                    <option value="data analyst" {{ old('position') == 'data analyst' ? 'selected' : '' }}>Data Analyst</option>
                                    <option value="office boy" {{ old('position') == 'office boy' ? 'selected' : '' }}>Office Boy</option>
                                </select>
                                @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">On Role Date</label>
                                <input type="date" name="onrole_date" class="form-control @error('onrole_date') is-invalid @enderror" value="{{ old('onrole_date') }}">
                                @error('onrole_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <h5 class="mb-3"><i class="fas fa-camera me-2"></i>Photo</h5>
                        <div class="mb-4">
                            <label class="form-label">Employee Photo</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            <div class="form-text">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Documents -->
                        <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Documents</h5>
                        <div id="documents-container">
                            <div class="document-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Document Title</label>
                                        <input type="text" name="document_titles[]" class="form-control @error('document_titles.*') is-invalid @enderror" placeholder="e.g., Resume, ID Proof, etc.">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">File</label>
                                        <input type="file" name="documents[]" class="form-control @error('documents.*') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger remove-document" style="display: none;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-custom mb-4" id="add-document">
                            <i class="fas fa-plus me-2"></i>Add Another Document
                        </button>

                        <!-- Submit Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-custom btn-lg me-2">
                                <i class="fas fa-save me-2"></i>Create Employee
                            </button>
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const documentsContainer = document.getElementById('documents-container');
    const addDocumentBtn = document.getElementById('add-document');

    // Add new document field
    addDocumentBtn.addEventListener('click', function() {
        const documentItem = document.querySelector('.document-item').cloneNode(true);
        documentItem.querySelectorAll('input').forEach(input => {
            input.value = '';
            input.classList.remove('is-invalid');
        });
        documentItem.querySelector('.remove-document').style.display = 'block';
        documentsContainer.appendChild(documentItem);
    });

    // Remove document field
    documentsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-document') || e.target.closest('.remove-document')) {
            e.target.closest('.document-item').remove();
        }
    });

    // Show remove button for first document if more than one exists
    function updateRemoveButtons() {
        const documentItems = document.querySelectorAll('.document-item');
        documentItems.forEach((item, index) => {
            const removeBtn = item.querySelector('.remove-document');
            removeBtn.style.display = documentItems.length > 1 ? 'block' : 'none';
        });
    }

    // Update remove buttons when documents change
    const observer = new MutationObserver(updateRemoveButtons);
    observer.observe(documentsContainer, { childList: true });

    updateRemoveButtons();
});
</script>
@endsection
