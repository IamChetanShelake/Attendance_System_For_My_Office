@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Event Details Section -->
            <div class="section-card">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Event Details</h5>
                    <div>
                        <a href="{{ route('admin.academic-calendar.edit', $event->id) }}" class="btn btn-outline-light btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Edit Event
                        </a>
                        <a href="{{ route('admin.academic-calendar.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to Calendar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Event Image -->
                        <div class="col-md-4 text-center mb-4">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" class="img-fluid rounded shadow mb-3" style="max-width: 100%; max-height: 300px;">
                            @else
                                <div class="bg-light rounded d-inline-flex align-items-center justify-content-center mb-3" style="width: 200px; height: 200px;">
                                    <i class="fas fa-calendar fa-4x text-muted"></i>
                                </div>
                            @endif
                            <h4>{{ $event->title }}</h4>
                            {!! $event->type_badge !!}
                        </div>

                        <!-- Event Information -->
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Event Information</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Event ID:</td>
                                            <td>{{ $event->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Title:</td>
                                            <td>{{ $event->title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Type:</td>
                                            <td>{!! $event->type_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Date:</td>
                                            <td>{{ $event->formatted_date }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                @if($event->is_today)
                                                    <span class="badge bg-success">Today</span>
                                                @elseif($event->is_future)
                                                    <span class="badge bg-primary">Upcoming</span>
                                                @else
                                                    <span class="badge bg-secondary">Past</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($event->is_recurring)
                                        <tr>
                                            <td class="fw-bold">Recurring:</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($event->recurrence_type) }}</span>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>

                                <!-- Additional Details -->
                                <div class="col-md-6">
                                    <h5 class="mb-3"><i class="fas fa-clock me-2"></i>Timeline</h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold">Created:</td>
                                            <td>{{ $event->created_at->format('d M Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Updated:</td>
                                            <td>{{ $event->updated_at->format('d M Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Time Until:</td>
                                            <td>{{ $event->days_until }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($event->description)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-sticky-note me-2"></i>Description</h5>
                                    <div class="alert alert-light border">
                                        {{ $event->description }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Recurring Information -->
                            @if($event->is_recurring && $event->recurrence_data)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-repeat me-2"></i>Recurring Information</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <p class="mb-1"><strong>Recurrence Type:</strong> {{ ucfirst($event->recurrence_type) }}</p>
                                            <p class="mb-0"><strong>Pattern:</strong> This event repeats {{ $event->recurrence_type }}ly</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3"><i class="fas fa-cogs me-2"></i>Actions</h5>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.academic-calendar.edit', $event->id) }}" class="btn btn-primary">
                                            <i class="fas fa-edit me-1"></i>Edit Event
                                        </a>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash me-1"></i>Delete Event
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this event?</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This action cannot be undone. The event will be permanently removed from the calendar.
                </div>
                <p class="mb-0"><strong>Event:</strong> {{ $event->title }}</p>
                <p class="mb-0"><strong>Date:</strong> {{ $event->formatted_date }}</p>
                <p class="mb-0"><strong>Type:</strong> {{ ucfirst($event->type) }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <form action="{{ route('admin.academic-calendar.destroy', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Event
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Professional Show Page Styling */
.bg-gradient-primary {
    background: linear-gradient(135deg, #4eb4e6 0%, #3a9bc7 100%) !important;
}

.card {
    border-radius: 15px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none !important;
}

.table {
    border-radius: 10px !important;
    overflow: hidden !important;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1) !important;
    border-color: rgba(255, 255, 255, 0.8) !important;
}

/* Event Image Styling */
.event-image-container {
    transition: all 0.3s ease;
}

.event-image-container:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* Status Badge Styling */
.badge {
    font-size: 0.8em;
    font-weight: 500;
    padding: 0.5em 0.75em;
}

/* Timeline Styling */
.timeline-item {
    position: relative;
    padding-left: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #4eb4e6;
}

/* Alert Styling */
.alert {
    border-radius: 10px;
    border: none;
}

/* Modal Styling */
.modal-content {
    border-radius: 15px;
    border: none;
}

.modal-header {
    border-radius: 15px 15px 0 0;
    border-bottom: none;
}
</style>
@endsection
