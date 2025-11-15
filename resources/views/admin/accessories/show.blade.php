@extends('admin.layout.master')

@section('content')
<main>
    <!-- Main Content Wrapper -->
    <div class="main-content-wrapper">
        <div class="main-content" id="mainContent">
        <!-- Accessory Details Section -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-eye me-2"></i>Accessory Details
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Accessory Name:</strong><br>
                                    <span class="text-primary">{{ $accessory->accessory_name }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Employee:</strong><br>
                                    <a href="{{ route('admin.employees.show', $accessory->employee->id) }}" class="text-decoration-none">
                                        {{ $accessory->employee->full_name }} ({{ $accessory->employee->employee_id }})
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Serial Number:</strong><br>
                                    {{ $accessory->serial_number ?: 'Not specified' }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Model Number:</strong><br>
                                    {{ $accessory->model_number ?: 'Not specified' }}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Value/Cost:</strong><br>
                                    {{ $accessory->formatted_value }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Status:</strong><br>
                                    {!! $accessory->status_badge !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Allocation Date:</strong><br>
                                    {{ $accessory->allocation_date->format('M d, Y') }}
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Return Date:</strong><br>
                                    {{ $accessory->return_date ? $accessory->return_date->format('M d, Y') : 'Not returned yet' }}
                                </div>
                            </div>

                            @if($accessory->allocation_days > 0)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Days Allocated:</strong><br>
                                    {{ $accessory->allocation_days }} days
                                </div>
                            </div>
                            @endif

                            @if($accessory->description)
                            <div class="mb-3">
                                <strong>Description:</strong><br>
                                <p class="mt-1">{{ $accessory->description }}</p>
                            </div>
                            @endif

                            @if($accessory->condition_notes)
                            <div class="mb-3">
                                <strong>Condition Notes:</strong><br>
                                <p class="mt-1">{{ $accessory->condition_notes }}</p>
                            </div>
                            @endif

                            <div class="mb-3">
                                <strong>Created:</strong><br>
                                {{ $accessory->created_at->format('M d, Y \a\t h:i A') }}
                            </div>

                            @if($accessory->updated_at != $accessory->created_at)
                            <div class="mb-3">
                                <strong>Last Updated:</strong><br>
                                {{ $accessory->updated_at->format('M d, Y \a\t h:i A') }}
                            </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            @if($accessory->photo)
                                <div class="text-center mb-4">
                                    <img src="{{ asset('storage/' . $accessory->photo) }}" alt="Accessory Photo" class="img-fluid rounded shadow" style="max-width: 100%; max-height: 300px;">
                                    <p class="text-muted mt-2 small">Accessory Photo</p>
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded d-inline-flex align-items-center justify-content-center" style="width: 200px; height: 200px;">
                                        <i class="fas fa-tools fa-3x text-muted"></i>
                                    </div>
                                    <p class="text-muted mt-2 small">No photo available</p>
                                </div>
                            @endif

                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.accessories.edit', $accessory->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Accessory
                                </a>
                                <a href="{{ route('admin.accessories.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Accessories
                                </a>
                                <form action="{{ route('admin.accessories.destroy', $accessory->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to delete this accessory allocation? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash me-2"></i>Delete Accessory
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
