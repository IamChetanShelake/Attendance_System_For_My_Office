@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">

            <!-- Employee Profile Header -->
            <div class="employee-profile-header">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}"
                                     alt="Employee Photo"
                                     class="employee-profile-photo">
                            @else
                                <div class="employee-profile-photo-placeholder">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <h1 class="employee-name">{{ $employee->full_name }}</h1>
                            <p class="employee-position">
                                <i class="fas fa-briefcase me-2"></i>{{ $employee->position }}
                            </p>
                            <p class="employee-department text-muted">
                                <i class="fas fa-building me-2"></i>{{ $employee->department }}
                            </p>
                            <div class="employee-badges">
                                <span class="badge bg-primary employee-id-badge">
                                    <i class="fas fa-id-badge me-1"></i>ID: {{ $employee->employee_id }}
                                </span>
                                <span class="badge bg-{{ $employee->type === 'onrole' ? 'success' : 'info' }} employee-type-badge">
                                    <i class="fas fa-user-check me-1"></i>{{ ucfirst($employee->type) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="action-buttons">
                                <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-primary btn-lg me-2">
                                    <i class="fas fa-edit me-2"></i>Edit Profile
                                </a>
                                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3>{{ $employee->start_date->diffInDays(now()) + 1 }}</h3>
                        <p>Days Employed</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-birthday-cake"></i>
                        </div>
                        <h3>{{ $employee->dob->diffInYears(now()) }}</h3>
                        <p>Years Old</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="text-success">ACTIVE</h3>
                        <p>Status</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="pin-display-card">
                        <div class="pin-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3 class="pin-number">{{ $employee->wfh_pin ?? '000000' }}</h3>
                        <p>WFH PIN</p>
                        <small class="text-muted">For manual attendance</small>
                    </div>
                </div>
            </div>

            <!-- Main Content Sections -->
            <div class="row">
                <!-- Personal Information -->
                <div class="col-lg-6 mb-4">
                    <div class="profile-section-card">
                        <div class="section-header">
                            <i class="fas fa-user section-icon"></i>
                            <h4 class="section-title">Personal Information</h4>
                        </div>
                        <div class="section-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label class="info-label">Full Name</label>
                                    <p class="info-value">{{ $employee->full_name }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Gender</label>
                                    <p class="info-value">{{ ucfirst($employee->gender) }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Date of Birth</label>
                                    <p class="info-value">{{ $employee->dob->format('d M Y') }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Marital Status</label>
                                    <p class="info-value">{{ ucfirst($employee->marital_status) }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Phone Number</label>
                                    <p class="info-value">{{ $employee->phone }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Address</label>
                                    <p class="info-value">{{ $employee->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="col-lg-6 mb-4">
                    <div class="profile-section-card">
                        <div class="section-header">
                            <i class="fas fa-briefcase section-icon"></i>
                            <h4 class="section-title">Professional Information</h4>
                        </div>
                        <div class="section-content">
                            <div class="info-grid">
                                <div class="info-item">
                                    <label class="info-label">Employee ID</label>
                                    <p class="info-value highlight">{{ $employee->employee_id }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Email Address</label>
                                    <p class="info-value">{{ $employee->email }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Department</label>
                                    <p class="info-value">{{ $employee->department }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Position</label>
                                    <p class="info-value">{{ $employee->position }}</p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Employment Type</label>
                                    <p class="info-value">
                                        <span class="badge bg-{{ $employee->type === 'onrole' ? 'success' : 'info' }} badge-lg">
                                            {{ ucfirst($employee->type) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="info-item">
                                    <label class="info-label">Start Date</label>
                                    <p class="info-value">{{ $employee->start_date->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Row -->
            <div class="row">
                <!-- Employment Timeline -->
                <div class="col-lg-6 mb-4">
                    <div class="profile-section-card">
                        <div class="section-header">
                            <i class="fas fa-calendar-alt section-icon"></i>
                            <h4 class="section-title">Employment Timeline</h4>
                        </div>
                        <div class="section-content">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Joined Organization</h6>
                                        <p class="timeline-date">{{ $employee->start_date->format('d F Y') }}</p>
                                        <p class="timeline-description">Started as {{ $employee->type === 'intern' ? 'an Intern' : $employee->position }}</p>
                                    </div>
                                </div>
                                @if($employee->onrole_date)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">On Role Confirmation</h6>
                                        <p class="timeline-date">{{ $employee->onrole_date->format('d F Y') }}</p>
                                        <p class="timeline-description">Confirmed as {{ $employee->position }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">WELCOME MESSAGE</h6>
                                        <p class="timeline-date">Welcome to TechMET!</p>
                                        <p class="timeline-description">Keep up the great work!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WFH & Security Information -->
                <div class="col-lg-6 mb-4">
                    <div class="profile-section-card">
                        <div class="section-header">
                            <i class="fas fa-shield-alt section-icon"></i>
                            <h4 class="section-title">WFH & Security Information</h4>
                        </div>
                        <div class="section-content">
                            <div class="security-info">
                                <div class="security-item">
                                    <div class="security-icon">
                                        <i class="fas fa-lock text-success"></i>
                                    </div>
                                    <div class="security-details">
                                        <h6 class="security-title">WFH PIN</h6>
                                        <div class="pin-display-large">
                                            <span class="pin-digits">{{ $employee->wfh_pin ? substr($employee->wfh_pin, 0, 1) . str_repeat('*', 4) . substr($employee->wfh_pin, -1) : '000000' }}</span>
                                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="togglePinVisibility()">
                                                <i class="fas fa-eye" id="pinToggleIcon"></i>
                                            </button>
                                        </div>
                                        <p class="security-description text-muted">6-digit PIN for manual attendance verification</p>
                                    </div>
                                </div>

                                <div class="security-alert">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>PIN Usage:</strong> Provide this PIN to HR when working from home for manual attendance recording.
                                    </div>
                                </div>

                                @if($employee->aadhaar_number)
                                <div class="security-item mt-3">
                                    <div class="security-icon">
                                        <i class="fas fa-id-card text-primary"></i>
                                    </div>
                                    <div class="security-details">
                                        <h6 class="security-title">Aadhaar Number</h6>
                                        <p class="security-value">{{ substr($employee->aadhaar_number, 0, 4) . '****' . substr($employee->aadhaar_number, -4) }}</p>
                                        <p class="security-description text-muted">Government ID verification</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            @if($employee->documents && count($employee->documents) > 0)
            <div class="row">
                <div class="col-12">
                    <div class="profile-section-card">
                        <div class="section-header">
                            <i class="fas fa-file-alt section-icon"></i>
                            <h4 class="section-title">Documents</h4>
                        </div>
                        <div class="section-content">
                            <div class="documents-grid">
                                @foreach($employee->documents as $document)
                                <div class="document-card">
                                    <div class="document-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="document-info">
                                        <h6 class="document-title">{{ $document['title'] ?? 'Document' }}</h6>
                                        <p class="document-filename">{{ $document['original_name'] ?? 'File' }}</p>
                                        <small class="document-size text-muted">
                                            @if(isset($document['size']))
                                                {{ number_format($document['size'] / 1024, 1) }} KB
                                            @endif
                                        </small>
                                    </div>
                                    <div class="document-actions">
                                        <a href="{{ asset('storage/' . $document['path']) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        <a href="{{ asset('storage/' . $document['path']) }}" download class="btn btn-outline-success btn-sm ms-1">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</main>

<script>
// Global PIN visibility state
let pinVisible = false;

function togglePinVisibility() {
    const pinElement = document.querySelector('.pin-digits');
    const toggleIcon = document.getElementById('pinToggleIcon');
    const fullPin = '{{ $employee->wfh_pin ?? "000000" }}';

    pinVisible = !pinVisible;

    if (pinVisible) {
        pinElement.textContent = fullPin;
        toggleIcon.className = 'fas fa-eye-slash';
    } else {
        pinElement.textContent = fullPin ? fullPin.substring(0, 1) + '****' + fullPin.substring(fullPin.length - 1) : '000000';
        toggleIcon.className = 'fas fa-eye';
    }
}
</script>

<style>
/* Employee Profile Header */
.employee-profile-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #3a9bc7 100%);
    color: white;
    padding: 2rem 0;
    margin: -2rem -2rem 2rem -2rem;
    box-shadow: 0 4px 20px rgba(78, 180, 230, 0.3);
}

.employee-profile-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease;
}

.employee-profile-photo:hover {
    transform: scale(1.05);
}

.employee-profile-photo-placeholder {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    border: 4px solid white;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    color: white;
}

.employee-name {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.employee-position {
    font-size: 1.2rem;
    margin: 0.5rem 0;
    opacity: 0.9;
}

.employee-department {
    font-size: 1rem;
    margin: 0;
}

.employee-badges {
    margin-top: 1rem;
}

.employee-id-badge, .employee-type-badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    margin-right: 0.5rem;
}

.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.action-buttons .btn {
    border: 2px solid white;
    color: white;
}

.action-buttons .btn:hover {
    background-color: white;
    color: var(--primary-color);
    border-color: white;
}

.action-buttons .btn-outline-secondary {
    border-color: rgba(255, 255, 255, 0.5);
    color: white;
}

.action-buttons .btn-outline-secondary:hover {
    background-color: white;
    color: var(--primary-color);
}

/* PIN Display Card */
.pin-display-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    color: white;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    border: none;
    position: relative;
    overflow: hidden;
}

.pin-display-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
    opacity: 0.1;
}

.pin-display-card .pin-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: rgba(255, 255, 255, 0.8);
}

.pin-display-card h3 {
    font-size: 2.2rem;
    font-weight: 900;
    margin: 0;
    letter-spacing: 3px;
}

.pin-display-card p {
    margin: 0.5rem 0 0.2rem 0;
    font-weight: 500;
}

/* Profile Section Cards */
.profile-section-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border: none;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.profile-section-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.section-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
}

.section-icon {
    font-size: 1.5rem;
    color: var(--primary-color);
    margin-right: 1rem;
}

.section-title {
    margin: 0;
    font-weight: 600;
    color: #495057;
}

.section-content {
    padding: 2rem;
}

/* Info Grid */
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.info-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.2rem;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.info-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: block;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 500;
    color: #495057;
    margin: 0;
}

.info-value.highlight {
    color: var(--primary-color);
    font-weight: 700;
}

/* Timeline */
.timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 40px;
    bottom: -2rem;
    width: 2px;
    background: #dee2e6;
}

.timeline-marker {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
    z-index: 1;
    position: relative;
}

.timeline-marker i {
    font-size: 1rem;
}

.timeline-content {
    flex: 1;
}

.timeline-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #495057;
}

.timeline-date {
    font-size: 0.95rem;
    color: var(--primary-color);
    font-weight: 500;
    margin: 0 0 0.5rem 0;
}

.timeline-description {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0;
}

/* Security Information */
.security-info {
    space-y: 1.5rem;
}

.security-item {
    display: flex;
    align-items: flex-start;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
}

.security-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.security-icon {
    font-size: 2rem;
    color: var(--primary-color);
    margin-right: 1rem;
    margin-top: 0.25rem;
}

.security-details {
    flex: 1;
}

.security-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: #495057;
}

.security-value {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: var(--primary-color);
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}

.security-description {
    font-size: 0.85rem;
    margin: 0;
}

.pin-display-large {
    display: flex;
    align-items: center;
}

.pin-digits {
    font-size: 1.4rem;
    font-weight: 900;
    font-family: 'Courier New', monospace;
    letter-spacing: 3px;
    color: #495057;
}

/* Documents Grid */
.documents-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.document-card {
    display: flex;
    align-items: center;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.document-card:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.document-icon {
    font-size: 2rem;
    color: var(--primary-color);
    margin-right: 1rem;
}

.document-info {
    flex: 1;
}

.document-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0 0 0.3rem 0;
    color: #495057;
}

.document-filename {
    font-size: 0.9rem;
    color: #6c757d;
    margin: 0 0 0.2rem 0;
}

.document-size {
    font-size: 0.8rem;
}

.document-actions {
    display: flex;
    gap: 0.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .employee-profile-header {
        padding: 1.5rem 0;
    }

    .employee-name {
        font-size: 2rem;
    }

    .action-buttons {
        text-align: center;
        margin-top: 1rem;
    }

    .action-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .documents-grid {
        grid-template-columns: 1fr;
    }

    .timeline-item {
        flex-direction: column;
        text-align: center;
    }

    .timeline-marker {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .security-item {
        flex-direction: column;
        text-align: center;
    }

    .security-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }

    .document-card {
        flex-direction: column;
        text-align: center;
    }

    .document-icon {
        margin-right: 0;
        margin-bottom: 1rem;
    }
}
</style>
@endsection
