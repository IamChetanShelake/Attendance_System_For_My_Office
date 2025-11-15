@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Edit Event Section -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Event</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.academic-calendar.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $event->title) }}" placeholder="e.g., Diwali Celebration, Team Meeting, Project Deadline" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">Event Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    <option value="holiday" {{ old('type', $event->type) == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    <option value="celebration" {{ old('type', $event->type) == 'celebration' ? 'selected' : '' }}>Celebration</option>
                                    <option value="event" {{ old('type', $event->type) == 'event' ? 'selected' : '' }}>Event</option>
                                    <option value="deadline" {{ old('type', $event->type) == 'deadline' ? 'selected' : '' }}>Deadline</option>
                                    <option value="meeting" {{ old('type', $event->type) == 'meeting' ? 'selected' : '' }}>Meeting</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label">Event Image</label>
                                @if($event->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                        <small class="text-muted d-block">Current image</small>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Upload a new image to replace the current one (JPEG, PNG, JPG, GIF - Max 2MB)</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" placeholder="Describe the event, holiday, or celebration...">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Recurring Event Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" value="1" {{ old('is_recurring', $event->is_recurring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_recurring">
                                        <strong>Recurring Event</strong>
                                    </label>
                                </div>
                            </div>
                            <div class="card-body" id="recurring-options" style="display: {{ old('is_recurring', $event->is_recurring) ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="recurrence_type" class="form-label">Recurrence Type</label>
                                        <select class="form-select @error('recurrence_type') is-invalid @enderror" id="recurrence_type" name="recurrence_type">
                                            <option value="">Select Type</option>
                                            <option value="yearly" {{ old('recurrence_type', $event->recurrence_type) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            <option value="monthly" {{ old('recurrence_type', $event->recurrence_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="weekly" {{ old('recurrence_type', $event->recurrence_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        </select>
                                        @error('recurrence_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Recurring events will automatically create future instances based on the selected pattern.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Event Preview -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-eye me-2"></i>Event Preview</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 id="preview-title">{{ $event->title }}</h5>
                                        <p id="preview-description" class="text-muted">{{ $event->description ?: 'Event description will appear here...' }}</p>
                                        <div class="mt-2">
                                            <span id="preview-badge" class="badge bg-{{ $event->type == 'holiday' ? 'danger' : ($event->type == 'celebration' ? 'success' : ($event->type == 'event' ? 'primary' : ($event->type == 'deadline' ? 'warning' : ($event->type == 'meeting' ? 'info' : 'secondary')))) }}">{{ $event->type == 'holiday' ? 'Holiday' : ($event->type == 'celebration' ? 'Celebration' : ($event->type == 'event' ? 'Event' : ($event->type == 'deadline' ? 'Deadline' : ($event->type == 'meeting' ? 'Meeting' : 'Event')))) }}</span>
                                            <small class="text-muted ms-2" id="preview-date">Date: {{ $event->formatted_date }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div id="preview-image" class="bg-light rounded d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                            @if($event->image)
                                                <img src="{{ asset('storage/' . $event->image) }}" class="img-fluid rounded" style="width: 120px; height: 120px; object-fit: cover;">
                                            @else
                                                <i class="fas fa-calendar fa-3x text-muted"></i>
                                            @endif
                                        </div>
                                        <p class="text-muted mt-2 small">Event Image Preview</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.academic-calendar.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Calendar
                            </a>
                            <button type="submit" class="btn btn-custom">
                                <i class="fas fa-save me-2"></i>Update Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Toggle recurring options
document.getElementById('is_recurring').addEventListener('change', function() {
    const recurringOptions = document.getElementById('recurring-options');
    recurringOptions.style.display = this.checked ? 'block' : 'none';

    if (!this.checked) {
        document.getElementById('recurrence_type').value = '';
    }
});

// Live preview
function updatePreview() {
    const title = document.getElementById('title').value || 'Event Title';
    const description = document.getElementById('description').value || 'Event description will appear here...';
    const type = document.getElementById('type').value;
    const date = document.getElementById('event_date').value;

    document.getElementById('preview-title').textContent = title;
    document.getElementById('preview-description').textContent = description;

    // Update badge
    const badge = document.getElementById('preview-badge');
    badge.className = 'badge ' + getBadgeClass(type);
    badge.textContent = getBadgeText(type);

    // Update date
    if (date) {
        const dateObj = new Date(date);
        document.getElementById('preview-date').textContent = 'Date: ' + dateObj.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });
    }
}

function getBadgeClass(type) {
    switch(type) {
        case 'holiday': return 'bg-danger';
        case 'celebration': return 'bg-success';
        case 'event': return 'bg-primary';
        case 'deadline': return 'bg-warning';
        case 'meeting': return 'bg-info';
        default: return 'bg-secondary';
    }
}

function getBadgeText(type) {
    switch(type) {
        case 'holiday': return 'Holiday';
        case 'celebration': return 'Celebration';
        case 'event': return 'Event';
        case 'deadline': return 'Deadline';
        case 'meeting': return 'Meeting';
        default: return 'Event';
    }
}

// Add event listeners for live preview
document.getElementById('title').addEventListener('input', updatePreview);
document.getElementById('description').addEventListener('input', updatePreview);
document.getElementById('type').addEventListener('change', updatePreview);
document.getElementById('event_date').addEventListener('change', updatePreview);

// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview-image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="width: 120px; height: 120px; object-fit: cover;">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '<i class="fas fa-calendar fa-3x text-muted"></i>';
    }
});

// Initialize preview
updatePreview();
</script>

<style>
/* Professional Form Styling */
.form-control:focus {
    border-color: #4eb4e6;
    box-shadow: 0 0 0 0.2rem rgba(78, 180, 230, 0.25);
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.8);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #4eb4e6 0%, #3a9bc7 100%) !important;
}

.card {
    border-radius: 15px !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none !important;
}

/* Event Preview Styling */
#preview-image {
    transition: all 0.3s ease;
}

#preview-image:hover {
    transform: scale(1.05);
}
</style>
@endsection
