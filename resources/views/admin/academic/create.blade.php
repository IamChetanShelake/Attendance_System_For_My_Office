@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Add New Event Section -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Add New Events</h5>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark me-3" id="event-count">1 Event</span>
                        <button type="button" class="btn btn-outline-light btn-sm" id="add-another-event">
                            <i class="fas fa-plus me-1"></i>Add Another
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.academic-calendar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div id="events-container">
                            <!-- Event Template -->
                            <div class="event-item-modern mb-4 p-4 border rounded event-card" data-event-index="0">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0 text-primary"><i class="fas fa-calendar-plus me-2"></i>Event #1</h5>
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-event" style="display: none;">
                                        <i class="fas fa-times me-1"></i>Remove
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control event-title" name="events[0][title]" placeholder="e.g., Diwali Celebration, Team Meeting, Project Deadline" required>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Event Type <span class="text-danger">*</span></label>
                                        <select class="form-select event-type" name="events[0][type]" required>
                                            <option value="">Select Type</option>
                                            <option value="holiday">Holiday</option>
                                            <option value="celebration">Celebration</option>
                                            <option value="event">Event</option>
                                            <option value="deadline">Deadline</option>
                                            <option value="meeting">Meeting</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Event Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control event-date" name="events[0][event_date]" value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Event Image</label>
                                        <input type="file" class="form-control event-image" name="events[0][image]" accept="image/*">
                                        <div class="form-text">Upload an image for the event (JPEG, PNG, JPG, GIF - Max 2MB)</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control event-description" name="events[0][description]" rows="3" placeholder="Describe the event, holiday, or celebration..."></textarea>
                                </div>

                                <!-- Recurring Event Section -->
                                <div class="card">
                                    <div class="card-header">
                                        <div class="form-check">
                                            <input class="form-check-input event-recurring" type="checkbox" name="events[0][is_recurring]" value="1">
                                            <label class="form-check-label">
                                                <strong>Recurring Event</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body recurring-options" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">Recurrence Type</label>
                                                <select class="form-select" name="events[0][recurrence_type]">
                                                    <option value="">Select Type</option>
                                                    <option value="yearly">Yearly</option>
                                                    <option value="monthly">Monthly</option>
                                                    <option value="weekly">Weekly</option>
                                                </select>
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
                            </div>
                        </div>

                        <!-- Summary -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0"><i class="fas fa-list me-2"></i>Events Summary</h6>
                            </div>
                            <div class="card-body">
                                <div id="events-summary">
                                    <p class="text-muted mb-0">No events added yet.</p>
                                </div>
                            </div>
                        </div>



                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.academic-calendar.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Calendar
                            </a>
                            <button type="submit" class="btn btn-custom">
                                <i class="fas fa-save me-2"></i>Add Events
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let eventIndex = 1;

document.addEventListener('DOMContentLoaded', function() {
    // Add another event
    document.getElementById('add-another-event').addEventListener('click', function() {
        addNewEvent();
    });

    // Remove event
    document.getElementById('events-container').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-event') || e.target.closest('.remove-event')) {
            e.target.closest('.event-item-modern').remove();
            updateEventNumbers();
            updateSummary();
        }
    });

    // Toggle recurring options for each event
    document.getElementById('events-container').addEventListener('change', function(e) {
        if (e.target.classList.contains('event-recurring')) {
            const recurringOptions = e.target.closest('.card').querySelector('.recurring-options');
            recurringOptions.style.display = e.target.checked ? 'block' : 'none';

            if (!e.target.checked) {
                const recurrenceType = e.target.closest('.card').querySelector('select[name*="[recurrence_type]"]');
                if (recurrenceType) recurrenceType.value = '';
            }
        }
    });

    // Update summary when inputs change
    document.getElementById('events-container').addEventListener('input', updateSummary);
    document.getElementById('events-container').addEventListener('change', updateSummary);

    updateSummary();
});

function addNewEvent() {
    const eventsContainer = document.getElementById('events-container');
    const eventTemplate = document.querySelector('.event-item-modern').cloneNode(true);

    // Update the event index
    eventTemplate.setAttribute('data-event-index', eventIndex);

    // Update form field names
    const inputs = eventTemplate.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        if (input.name) {
            input.name = input.name.replace('[0]', '[' + eventIndex + ']');
            input.value = ''; // Clear values
            input.classList.remove('is-invalid'); // Remove validation classes
        }
    });

    // Update event number
    const eventTitle = eventTemplate.querySelector('h5');
    eventTitle.textContent = 'Event #' + (eventIndex + 1);

    // Show remove button
    const removeBtn = eventTemplate.querySelector('.remove-event');
    removeBtn.style.display = 'block';

    // Reset recurring options
    const recurringCheckbox = eventTemplate.querySelector('.event-recurring');
    const recurringOptions = eventTemplate.querySelector('.recurring-options');
    recurringCheckbox.checked = false;
    recurringOptions.style.display = 'none';

    eventsContainer.appendChild(eventTemplate);
    eventIndex++;

    updateEventNumbers();
    updateSummary();
}

function updateEventNumbers() {
    const eventItems = document.querySelectorAll('.event-item-modern');
    eventItems.forEach((item, index) => {
        const title = item.querySelector('h5');
        title.innerHTML = '<i class="fas fa-calendar-plus me-2"></i>Event #' + (index + 1);
    });

    // Update event count badge
    const eventCountBadge = document.getElementById('event-count');
    eventCountBadge.textContent = eventItems.length + ' Event' + (eventItems.length > 1 ? 's' : '');

    // Show/hide remove buttons based on number of events
    const removeButtons = document.querySelectorAll('.remove-event');
    removeButtons.forEach(button => {
        button.style.display = eventItems.length > 1 ? 'block' : 'none';
    });
}

function updateSummary() {
    const eventItems = document.querySelectorAll('.event-item-modern');
    const summaryDiv = document.getElementById('events-summary');

    if (eventItems.length === 0) {
        summaryDiv.innerHTML = '<p class="text-muted mb-0">No events added yet.</p>';
        return;
    }

    let summaryHTML = '<div class="row">';

    eventItems.forEach((item, index) => {
        const title = item.querySelector('.event-title').value || 'Untitled Event';
        const type = item.querySelector('.event-type').value;
        const date = item.querySelector('.event-date').value;

        const typeText = getTypeText(type);
        const badgeClass = getBadgeClass(type);

        summaryHTML += `
            <div class="col-md-6 mb-2">
                <div class="d-flex align-items-center">
                    <span class="badge ${badgeClass} me-2">${typeText}</span>
                    <div>
                        <small class="fw-bold">${title}</small>
                        ${date ? `<br><small class="text-muted">${new Date(date).toLocaleDateString()}</small>` : ''}
                    </div>
                </div>
            </div>
        `;
    });

    summaryHTML += '</div>';
    summaryHTML += `<div class="mt-2"><small class="text-muted">Total: ${eventItems.length} event${eventItems.length > 1 ? 's' : ''}</small></div>`;

    summaryDiv.innerHTML = summaryHTML;
}

function getTypeText(type) {
    switch(type) {
        case 'holiday': return 'Holiday';
        case 'celebration': return 'Celebration';
        case 'event': return 'Event';
        case 'deadline': return 'Deadline';
        case 'meeting': return 'Meeting';
        default: return 'Event';
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
</script>

<style>
/* Professional Form Styling */
.event-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid #dee2e6 !important;
    transition: all 0.3s ease;
}

.event-card:hover {
    border-color: #4eb4e6 !important;
    box-shadow: 0 4px 15px rgba(78, 180, 230, 0.2);
}

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

/* Event Summary Styling */
.badge {
    font-size: 0.75em;
    font-weight: 500;
}
</style>
@endsection
