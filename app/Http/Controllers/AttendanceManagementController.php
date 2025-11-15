<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceManagementController extends Controller
{
    public function index()
    {
        // Get employees with their PIN status for WFH attendance
        $employees = Employee::select(['id', 'first_name', 'last_name', 'employee_id', 'photo', 'email', 'wfh_pin'])
            ->orderBy('first_name')
            ->get();

        // Get today's attendance data
        $todayAttendances = Attendance::with('employee')
            ->where('date', today())
            ->get()
            ->keyBy('employee_id');

        return view('admin.attendance.manage', compact('employees', 'todayAttendances'));
    }

    // Separate page for manual attendance recording
    public function recordForm()
    {
        // Get employees with their PIN status for WFH attendance
        $employees = Employee::select(['id', 'first_name', 'last_name', 'employee_id', 'photo', 'email', 'wfh_pin', 'position'])
            ->orderBy('first_name')
            ->get();

        return view('admin.attendance.record-manual', compact('employees'));
    }

    // Manual Attendance Recording with PIN Verification
    public function recordManualAttendance(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'action' => 'required|in:punch_in,punch_out',
            'pin' => 'required|string|size:6',
            'attendance_type' => 'nullable|in:office,wfh,hybrid',
        ]);

        try {
            $employee = Employee::find($request->employee_id);
            $attendanceType = $request->attendance_type ?: 'wfh'; // Default to WFH for manual entries

            // Verify PIN
            if (!$employee->wfh_pin || $employee->wfh_pin !== $request->pin) {
                return redirect()->back()
                    ->withInput($request->all())
                    ->with('error', 'Invalid PIN. Please check the 6-digit PIN assigned to this employee.');
            }

            if ($request->action === 'punch_in') {
                $attendance = Attendance::recordManualPunchIn(
                    $employee->id,
                    $request->pin,
                    $attendanceType
                );
                $message = 'Manual Punch In recorded successfully for ' . $employee->full_name;
            } else {
                $attendance = Attendance::recordManualPunchOut(
                    $employee->id,
                    $request->pin,
                    $attendanceType
                );
                $message = 'Manual Punch Out recorded successfully for ' . $employee->full_name;
            }

            // Log the manual attendance recording
            Log::info("Manual attendance recorded", [
                'employee_id' => $employee->id,
                'employee_name' => $employee->full_name,
                'action' => $request->action,
                'attendance_type' => $attendanceType,
                'recorded_by' => 'System Admin',
            ]);

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error("Manual attendance recording failed", [
                'employee_id' => $request->employee_id,
                'action' => $request->action,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput($request->all())
                ->with('error', $e->getMessage() ?: 'Failed to record attendance. Please try again.');
        }
    }

    // Get employee PIN status for validation
    public function getEmployeePinStatus($employeeId)
    {
        $employee = Employee::select(['id', 'wfh_pin', 'employee_id', 'first_name', 'last_name'])->find($employeeId);

        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        return response()->json([
            'success' => true,
            'has_pin' => !empty($employee->wfh_pin),
            'employee_id' => $employee->employee_id,
            'employee_name' => $employee->full_name,
        ]);
    }

    // Get today's attendance for a specific employee
    public function getEmployeeTodayAttendance($employeeId)
    {
        $attendance = Attendance::where('employee_id', $employeeId)
            ->where('date', today())
            ->first();

        if ($attendance) {
            return response()->json([
                'success' => true,
                'attendance' => [
                    'id' => $attendance->id,
                    'date' => $attendance->date->format('Y-m-d'),
                    'punch_in_time' => $attendance->punch_in_time?->format('H:i:s'),
                    'punch_out_time' => $attendance->punch_out_time?->format('H:i:s'),
                    'worked_hours' => $attendance->formatted_worked_hours,
                    'attendance_type' => $attendance->attendance_type,
                    'punch_in_source' => $attendance->punch_in_source,
                    'punch_out_source' => $attendance->punch_out_source,
                    'status' => $attendance->status,
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'attendance' => null,
        ]);
    }
}
