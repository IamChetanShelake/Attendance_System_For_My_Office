@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Academic Calendar Management Section -->
            <div class="section-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-calendar-alt me-2"></i>Academic Calendar Management</span>
                    <a href="{{ route('admin.academic-calendar.create') }}" class="btn btn-custom">
                        <i class="fas fa-plus me-2"></i>Add Event
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Calendar View -->
                        <div class="col-lg-8 mb-4">
                            <div class="card shadow-lg border-0">
                                <div class="card-header bg-gradient-primary text-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Interactive Calendar</h5>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-light btn-sm" id="today-btn">
                                                <i class="fas fa-calendar-day me-1"></i>Today
                                            </button>
                                            <button class="btn btn-outline-light btn-sm" id="refresh-btn">
                                                <i class="fas fa-sync-alt me-1"></i>Refresh
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="calendar" class="modern-calendar"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Sidebar -->
                        <div class="col-lg-4">
                            <!-- Quick Stats -->
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="stats-card-modern">
                                        <div class="stats-icon">
                                            <i class="fas fa-calendar-check text-success"></i>
                                        </div>
                                        <div class="stats-content">
                                            <h4 class="stats-number">{{ $upcomingEvents->count() }}</h4>
                                            <p class="stats-label">Upcoming</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stats-card-modern">
                                        <div class="stats-icon">
                                            <i class="fas fa-calendar-week text-primary"></i>
                                        </div>
                                        <div class="stats-content">
                                            <h4 class="stats-number">{{ $thisMonthEvents->count() }}</h4>
                                            <p class="stats-label">This Month</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Events -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-dark"><i class="fas fa-clock text-warning me-2"></i>Upcoming Events</h6>
                                        <span class="badge bg-warning">{{ $upcomingEvents->count() }}</span>
                                    </div>
                                </div>
                                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                    @if($upcomingEvents->count() > 0)
                                        @foreach($upcomingEvents as $event)
                                        <div class="event-item-modern mb-3 p-3 rounded-3 event-type-{{ $event->type }}">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="event-icon-modern event-icon-{{ $event->type }}">
                                                        {!! $event->type_icon !!}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($event->title, 25) }}</h6>
                                                    <div class="event-meta">
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-calendar me-1"></i>{{ $event->formatted_date }}
                                                        </small>
                                                        <small class="text-primary fw-semibold">
                                                            <i class="fas fa-hourglass-half me-1"></i>{{ $event->days_until }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No upcoming events</p>
                                            <small class="text-muted">All caught up!</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- This Month Events -->
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-dark"><i class="fas fa-calendar-week text-info me-2"></i>This Month</h6>
                                        <span class="badge bg-info">{{ $thisMonthEvents->count() }}</span>
                                    </div>
                                </div>
                                <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                    @if($thisMonthEvents->count() > 0)
                                        @foreach($thisMonthEvents as $event)
                                        <div class="month-event-item d-flex align-items-center mb-2 p-2 rounded-2 month-event-{{ $event->type }}">
                                            <div class="event-dot me-3 event-dot-{{ $event->type }}"></div>
                                            <div class="flex-grow-1">
                                                <small class="fw-bold text-dark d-block">{{ Str::limit($event->title, 20) }}</small>
                                                <small class="text-muted">{{ $event->event_date->format('M d, D') }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-3">
                                            <i class="fas fa-calendar-alt fa-2x text-muted mb-2"></i>
                                            <p class="text-muted small mb-0">No events this month</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Event Types Legend -->
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-dark"><i class="fas fa-palette text-success me-2"></i>Event Legend</h6>
                                </div>
                                <div class="card-body">
                                    <div class="legend-grid">
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: #dc3545;"></div>
                                            <span class="legend-text">Holiday</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: #28a745;"></div>
                                            <span class="legend-text">Celebration</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: #007bff;"></div>
                                            <span class="legend-text">Event</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: #ffc107;"></div>
                                            <span class="legend-text">Deadline</span>
                                        </div>
                                        <div class="legend-item">
                                            <div class="legend-color" style="background: #17a2b8;"></div>
                                            <span class="legend-text">Meeting</span>
                                        </div>
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

<!-- Event Details Modal -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="eventDetails">
                    <!-- Event details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="editEventBtn" class="btn btn-primary">Edit Event</a>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
// Auto-dismiss alerts after 2 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 2000); // 2000ms = 2 seconds
    });
});

// Initialize FullCalendar
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            fetch('{{ route("admin.academic-calendar.events.data") }}?start=' + fetchInfo.startStr + '&end=' + fetchInfo.endStr)
                .then(response => response.json())
                .then(data => successCallback(data))
                .catch(error => failureCallback(error));
        },
        eventClick: function(info) {
            // Load event details
            fetch('{{ route("admin.academic-calendar.show", ":id") }}'.replace(':id', info.event.id))
                .then(response => response.text())
                .then(html => {
                    // Extract the content from the show view
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const content = doc.querySelector('.card-body');
                    if (content) {
                        document.getElementById('eventDetails').innerHTML = content.innerHTML;
                        document.getElementById('editEventBtn').href = '{{ route("admin.academic-calendar.edit", ":id") }}'.replace(':id', info.event.id);
                        const modal = new bootstrap.Modal(document.getElementById('eventModal'));
                        modal.show();
                    }
                })
                .catch(error => console.error('Error loading event details:', error));
        },
        eventDidMount: function(info) {
            // Add tooltip
            info.el.setAttribute('title', info.event.title + (info.event.extendedProps.description ? '\n' + info.event.extendedProps.description : ''));
        },
        height: 'auto',
        dayMaxEvents: true,
        moreLinkClick: 'popover'
    });
    calendar.render();
});
</script>

<style>
/* Modern Calendar Styles */
.modern-calendar {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    border-radius: 0 0 15px 15px;
    overflow: hidden;
}

#calendar {
    max-width: 100%;
    background: white;
    border-radius: 0 0 15px 15px;
}

.fc-event {
    cursor: pointer;
    border-radius: 8px;
    font-size: 0.85em;
    font-weight: 500;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.fc-event:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    opacity: 0.9;
}

.fc-button {
    border-radius: 8px !important;
    font-weight: 500 !important;
    transition: all 0.3s ease !important;
}

.fc-button:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
}

.fc-header-toolbar {
    padding: 1rem 2rem !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}

/* Stats Cards */
.stats-card-modern {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.stats-card-modern:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stats-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.8;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    color: #495057;
    margin-bottom: 0.25rem;
}

.stats-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0;
}

/* Event Items */
.event-item-modern {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.event-item-modern:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.event-item-modern::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 20px 20px 0;
    border-color: transparent rgba(0,0,0,0.05) transparent transparent;
}

.event-icon-modern {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.event-meta {
    margin-top: 0.5rem;
}

/* Event Type Specific Styles */
.event-type-holiday {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
}

.event-icon-holiday {
    background: #dc3545;
}

.event-type-celebration {
    border-left-color: #28a745;
    background: linear-gradient(135deg, #f0fff4 0%, #c6f6d5 100%);
}

.event-icon-celebration {
    background: #28a745;
}

.event-type-event {
    border-left-color: #007bff;
    background: linear-gradient(135deg, #ebf8ff 0%, #bee3f8 100%);
}

.event-icon-event {
    background: #007bff;
}

.event-type-deadline {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
}

.event-icon-deadline {
    background: #ffc107;
}

.event-type-meeting {
    border-left-color: #17a2b8;
    background: linear-gradient(135deg, #e6fffa 0%, #b2f5ea 100%);
}

.event-icon-meeting {
    background: #17a2b8;
}

/* Month Events */
.month-event-item {
    background: rgba(0, 123, 255, 0.1);
    border: 1px solid rgba(0, 123, 255, 0.2);
    transition: all 0.3s ease;
}

.month-event-item:hover {
    background: rgba(0, 123, 255, 0.15);
    transform: translateX(3px);
}

.month-event-holiday {
    background: rgba(220, 53, 69, 0.1);
    border-color: rgba(220, 53, 69, 0.2);
}

.month-event-celebration {
    background: rgba(40, 167, 69, 0.1);
    border-color: rgba(40, 167, 69, 0.2);
}

.month-event-event {
    background: rgba(0, 123, 255, 0.1);
    border-color: rgba(0, 123, 255, 0.2);
}

.month-event-deadline {
    background: rgba(255, 193, 7, 0.1);
    border-color: rgba(255, 193, 7, 0.2);
}

.month-event-meeting {
    background: rgba(23, 162, 184, 0.1);
    border-color: rgba(23, 162, 184, 0.2);
}

.event-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #007bff;
    flex-shrink: 0;
}

.event-dot-holiday { background: #dc3545; }
.event-dot-celebration { background: #28a745; }
.event-dot-event { background: #007bff; }
.event-dot-deadline { background: #ffc107; }
.event-dot-meeting { background: #17a2b8; }

/* Legend */
.legend-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-color {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    flex-shrink: 0;
}

.legend-text {
    font-size: 0.85rem;
    font-weight: 500;
    color: #495057;
}

/* Gradient Background */
.bg-gradient-primary {
    background: linear-gradient(135deg, #4eb4e6 0%, #3a9bc7 100%) !important;
}

/* Card Enhancements */
.card {
    border-radius: 15px !important;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
    border: none !important;
}

/* Scrollbar Styling */
.card-body::-webkit-scrollbar {
    width: 6px;
}

.card-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.card-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.card-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stats-card-modern {
        margin-bottom: 1rem;
    }

    .legend-grid {
        grid-template-columns: 1fr;
    }

    .fc-header-toolbar {
        padding: 0.75rem 1rem !important;
        flex-direction: column;
        gap: 0.5rem;
    }

    .event-item-modern {
        margin-bottom: 1rem !important;
    }
}

/* Animation Classes */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.event-item-modern {
    animation: fadeInUp 0.5s ease-out;
}

/* Loading States */
.btn-outline-light:disabled {
    opacity: 0.6;
}

/* Focus States */
.fc-button:focus,
.btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(78, 180, 230, 0.25) !important;
}
</style>
@endsection
