@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Upload Documents Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-upload me-2"></i>Upload Documents
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data">
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
                                            No employees available. <a href="{{ route('admin.employees.create') }}">Add employees first</a>.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Documents Upload -->
                        <h5 class="mb-3"><i class="fas fa-file-upload me-2"></i>Document Details</h5>
                        <div id="documents-container">
                            <div class="document-item mb-3 p-3 border rounded">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="form-label">Document Name <span class="text-danger">*</span></label>
                                        <input type="text" name="document_names[]" class="form-control @error('document_names.*') is-invalid @enderror" placeholder="e.g., Resume, ID Proof, Certificate, etc." required>
                                        @error('document_names.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">File <span class="text-danger">*</span></label>
                                        <input type="file" name="documents[]" class="form-control @error('documents.*') is-invalid @enderror" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xls,.xlsx,.ppt,.pptx,.txt" required>
                                        @error('documents.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-outline-danger remove-document" style="display: none;">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-11">
                                        <label class="form-label">Description (Optional)</label>
                                        <textarea name="descriptions[]" class="form-control" rows="2" placeholder="Add any additional notes about this document..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-custom mb-4" id="add-document">
                            <i class="fas fa-plus me-2"></i>Add Another Document
                        </button>

                        <!-- File Requirements -->
                        <div class="alert alert-light border mb-4">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>File Requirements</h6>
                            <ul class="mb-0 small">
                                <li><strong>Supported formats:</strong> PDF, DOC, DOCX, JPG, JPEG, PNG, XLS, XLSX, PPT, PPTX, TXT</li>
                                <li><strong>Maximum file size:</strong> 10MB per file</li>
                                <li><strong>Multiple files:</strong> You can upload multiple documents at once</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-custom btn-lg me-2">
                                <i class="fas fa-upload me-2"></i>Upload Documents
                            </button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-outline-secondary btn-lg">
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
            if (input.type !== 'file') {
                input.value = '';
            }
            input.classList.remove('is-invalid');
        });
        documentItem.querySelector('textarea').value = '';
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
