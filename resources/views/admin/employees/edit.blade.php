 @extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Edit Employee Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-user-edit me-2"></i>Edit Employee
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <h5 class="mb-3"><i class="fas fa-user me-2"></i>Personal Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $employee->first_name) }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control @error('middle_name') is-invalid @enderror" value="{{ old('middle_name', $employee->middle_name) }}">
                                @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $employee->last_name) }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $employee->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                                <select name="marital_status" class="form-select @error('marital_status') is-invalid @enderror" required>
                                    <option value="">Select Status</option>
                                    <option value="single" {{ old('marital_status', $employee->marital_status) == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('marital_status', $employee->marital_status) == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="divorced" {{ old('marital_status', $employee->marital_status) == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="widowed" {{ old('marital_status', $employee->marital_status) == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                                @error('marital_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" class="form-control @error('dob') is-invalid @enderror" value="{{ old('dob', $employee->dob ? $employee->dob->format('Y-m-d') : '') }}" required>
                                @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $employee->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" class="form-control @error('aadhaar_number') is-invalid @enderror" value="{{ old('aadhaar_number', $employee->aadhaar_number) }}" placeholder="123456789012" maxlength="12">
                                <div class="form-text">12-digit Aadhaar number (optional)</div>
                                @error('aadhaar_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">6-Digit WFH PIN <span class="text-danger">*</span></label>
                                <input type="password" name="wfh_pin" class="form-control @error('wfh_pin') is-invalid @enderror" value="{{ old('wfh_pin', $employee->wfh_pin) }}" pattern="[0-9]{6}" maxlength="6" placeholder="000000" required>
                                <div class="form-text">6-digit numeric PIN for WFH attendance verification</div>
                                @error('wfh_pin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3" required>{{ old('address', $employee->address) }}</textarea>
                            @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Professional Information -->
                        <h5 class="mb-3"><i class="fas fa-briefcase me-2"></i>Professional Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <input type="text" name="department" class="form-control @error('department') is-invalid @enderror" value="{{ old('department', $employee->department) }}" required>
                                @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">Select Type</option>
                                    <option value="intern" {{ old('type', $employee->type) == 'intern' ? 'selected' : '' }}>Intern</option>
                                    <option value="onrole" {{ old('type', $employee->type) == 'onrole' ? 'selected' : '' }}>On Role</option>
                                </select>
                                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Position <span class="text-danger">*</span></label>
                                <select name="position" class="form-select @error('position') is-invalid @enderror" required>
                                    <option value="">Select Position</option>
                                    <option value="php developer" {{ old('position', $employee->position) == 'php developer' ? 'selected' : '' }}>PHP Developer</option>
                                    <option value="react developer" {{ old('position', $employee->position) == 'react developer' ? 'selected' : '' }}>React Developer</option>
                                    <option value="uiux designer" {{ old('position', $employee->position) == 'uiux designer' ? 'selected' : '' }}>UI/UX Designer</option>
                                    <option value="node js developer" {{ old('position', $employee->position) == 'node js developer' ? 'selected' : '' }}>Node.js Developer</option>
                                    <option value="support engineer" {{ old('position', $employee->position) == 'support engineer' ? 'selected' : '' }}>Support Engineer</option>
                                    <option value="hr management" {{ old('position', $employee->position) == 'hr management' ? 'selected' : '' }}>HR Management</option>
                                    <option value="project manager" {{ old('position', $employee->position) == 'project manager' ? 'selected' : '' }}>Project Manager</option>
                                    <option value="business analyst" {{ old('position', $employee->position) == 'business analyst' ? 'selected' : '' }}>Business Analyst</option>
                                    <option value="qa engineer" {{ old('position', $employee->position) == 'qa engineer' ? 'selected' : '' }}>QA Engineer</option>
                                    <option value="devops engineer" {{ old('position', $employee->position) == 'devops engineer' ? 'selected' : '' }}>DevOps Engineer</option>
                                    <option value="data analyst" {{ old('position', $employee->position) == 'data analyst' ? 'selected' : '' }}>Data Analyst</option>
                                    <option value="office boy" {{ old('position', $employee->position) == 'office boy' ? 'selected' : '' }}>Office Boy</option>
                                </select>
                                @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $employee->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">On Role Date</label>
                                <input type="date" name="onrole_date" class="form-control @error('onrole_date') is-invalid @enderror" value="{{ old('onrole_date', $employee->onrole_date ? $employee->onrole_date->format('Y-m-d') : '') }}">
                                @error('onrole_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <h5 class="mb-3"><i class="fas fa-camera me-2"></i>Photo</h5>
                        <div class="mb-4">
                            @if($employee->photo)
                                <div class="mb-3">
                                    <label class="form-label">Current Photo:</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $employee->photo) }}" alt="Current Photo" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                </div>
                            @endif
                            <label class="form-label">Update Photo (leave empty to keep current)</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            <div class="form-text">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- Documents -->
                        <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Documents</h5>

                        <!-- Existing Documents -->
                        @if($employee->documents && count($employee->documents) > 0)
                        <div class="mb-4">
                            <label class="form-label">Current Documents:</label>
                            <div class="row">
                                @foreach($employee->documents as $index => $document)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center p-2 border rounded">
                                        <i class="fas fa-file me-2 text-primary"></i>
                                        <div class="flex-grow-1">
                                            <strong>{{ $document['title'] ?? 'Document' }}</strong><br>
                                            <small class="text-muted">{{ $document['original_name'] ?? 'File' }}</small>
                                        </div>
                                        <a href="{{ asset('storage/' . $document['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Add New Documents -->
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
                                <i class="fas fa-save me-2"></i>Update Employee
                            </button>
                            <a href="{{ route('admin.employees.show', $employee->id) }}" class="btn btn-outline-secondary btn-lg me-2">
                                <i class="fas fa-eye me-2"></i>View Details
                            </a>
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
