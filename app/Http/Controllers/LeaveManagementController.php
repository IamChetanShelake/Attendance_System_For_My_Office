<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LeaveManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Leave::with(['employee', 'approver'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by leave type
        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        // Filter by employee
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }

        $leaves = $query->paginate(15);

        // Get statistics
        $stats = [
            'total_leaves' => Leave::count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'approved_leaves' => Leave::where('status', 'approved')->count(),
            'rejected_leaves' => Leave::where('status', 'rejected')->count(),
            'this_month_leaves' => Leave::whereMonth('start_date', now()->month)
                                        ->whereYear('start_date', now()->year)->count()
        ];

        $employees = Employee::select(['id', 'first_name', 'last_name', 'employee_id'])
            ->orderBy('first_name')
            ->get();

        return view('admin.leave.index', compact('leaves', 'stats', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        $leaveTypes = Leave::getLeaveTypes();

        return view('admin.leave.create', compact('employees', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:' . implode(',', array_keys(Leave::getLeaveTypes())),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'is_half_day' => 'nullable|boolean',
            'emergency' => 'nullable|boolean',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $leave = Leave::create([
                'employee_id' => $request->employee_id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'is_half_day' => $request->boolean('is_half_day'),
                'emergency' => $request->boolean('emergency'),
                'admin_notes' => $request->admin_notes,
                'submitted_at' => now(),
                'days_count' => $this->calculateDaysCount($request->start_date, $request->end_date, $request->boolean('is_half_day'))
            ]);

            DB::commit();

            Log::info("Leave record created", [
                'leave_id' => $leave->id,
                'employee_id' => $request->employee_id,
                'leave_type' => $request->leave_type
            ]);

            return redirect()->route('admin.employee-leave.index')
                ->with('success', 'Leave record created successfully for ' . $leave->employee->full_name);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Leave creation failed", ['error' => $e->getMessage()]);

            return redirect()->back()
                ->withInput($request->all())
                ->with('error', 'Failed to create leave record. Please try again.');
        }
    }

    public function show(Leave $leave)
    {
        $leave->load(['employee', 'approver']);

        return view('admin.leave.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        $employees = Employee::all();
        $leaveTypes = Leave::getLeaveTypes();

        return view('admin.leave.edit', compact('leave', 'employees', 'leaveTypes'));
    }

    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|in:' . implode(',', array_keys(Leave::getLeaveTypes())),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'is_half_day' => 'nullable|boolean',
            'emergency' => 'nullable|boolean',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        try {
            $leave->update([
                'employee_id' => $request->employee_id,
                'leave_type' => $request->leave_type,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
                'is_half_day' => $request->boolean('is_half_day'),
                'emergency' => $request->boolean('emergency'),
                'admin_notes' => $request->admin_notes,
                'days_count' => $this->calculateDaysCount($request->start_date, $request->end_date, $request->boolean('is_half_day'))
            ]);

            Log::info("Leave record updated", ['leave_id' => $leave->id]);

            return redirect()->route('admin.employee-leave.index')
                ->with('success', 'Leave record updated successfully');

        } catch (\Exception $e) {
            Log::error("Leave update failed", ['error' => $e->getMessage(), 'leave_id' => $leave->id]);

            return redirect()->back()
                ->withInput($request->all())
                ->with('error', 'Failed to update leave record. Please try again.');
        }
    }

    public function destroy(Leave $leave)
    {
        try {
            $leave->delete();

            Log::info("Leave record deleted", ['leave_id' => $leave->id]);

            return redirect()->route('admin.employee-leave.index')
                ->with('success', 'Leave record deleted successfully');

        } catch (\Exception $e) {
            Log::error("Leave deletion failed", ['error' => $e->getMessage(), 'leave_id' => $leave->id]);

            return redirect()->back()->with('error', 'Failed to delete leave record. Please try again.');
        }
    }

    public function approve(Request $request, Leave $leave)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500'
        ]);

        // Get current logged-in admin (you may need to implement authentication)
        // For now, we'll assume employee with ID 1 is the admin
        $approver = Employee::first(); // You should replace this with proper authentication

        try {
            $leave->approve($approver, $request->admin_notes);

            Log::info("Leave approved", ['leave_id' => $leave->id, 'approved_by' => $approver->id]);

            return redirect()->back()->with('success', 'Leave approved successfully');

        } catch (\Exception $e) {
            Log::error("Leave approval failed", ['error' => $e->getMessage(), 'leave_id' => $leave->id]);

            return redirect()->back()->with('error', 'Failed to approve leave. Please try again.');
        }
    }

    public function reject(Request $request, Leave $leave)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $rejector = Employee::first(); // You should replace this with proper authentication

        try {
            $leave->reject($rejector, $request->reason, $request->admin_notes);

            Log::info("Leave rejected", ['leave_id' => $leave->id, 'rejected_by' => $rejector->id]);

            return redirect()->back()->with('success', 'Leave rejected successfully');

        } catch (\Exception $e) {
            Log::error("Leave rejection failed", ['error' => $e->getMessage(), 'leave_id' => $leave->id]);

            return redirect()->back()->with('error', 'Failed to reject leave. Please try again.');
        }
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'leave_ids' => 'required|array',
            'leave_ids.*' => 'exists:leaves,id',
            'action' => 'required|in:approve,reject,delete',
            'reason' => 'required_if:action,reject|string|max:500'
        ]);

        $approver = Employee::first(); // You should replace this with proper authentication

        try {
            DB::beginTransaction();

            $leaves = Leave::whereIn('id', $request->leave_ids)->get();
            $count = 0;

            foreach ($leaves as $leave) {
                switch ($request->action) {
                    case 'approve':
                        $leave->approve($approver, $request->admin_notes ?? null);
                        $count++;
                        break;
                    case 'reject':
                        $leave->reject($approver, $request->reason, $request->admin_notes ?? null);
                        $count++;
                        break;
                    case 'delete':
                        $leave->delete();
                        $count++;
                        break;
                }
            }

            DB::commit();

            Log::info("Bulk leave action completed", [
                'action' => $request->action,
                'count' => $count
            ]);

            return redirect()->back()->with('success', "Successfully {$request->action}d {$count} leave request(s)");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Bulk leave action failed", ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Bulk action failed. Please try again.');
        }
    }

    private function calculateDaysCount($startDate, $endDate, $isHalfDay = false)
    {
        if ($isHalfDay) {
            return 0.5;
        }

        $start = \Carbon\Carbon::parse($startDate);
        $end = \Carbon\Carbon::parse($endDate);

        return $start->diffInDays($end) + 1; // Including both start and end dates
    }
}
