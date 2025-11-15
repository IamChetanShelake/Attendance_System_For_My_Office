<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DailyAttendanceController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('first_name')->get();

        // Get today's attendance data
        $todayAttendances = Attendance::with('employee')
            ->where('date', today())
            ->get()
            ->keyBy('employee_id');

        // Calculate stats
        $punchedInCount = $todayAttendances->filter(function($attendance) {
            return $attendance->punch_in_time !== null;
        })->count();

        $punchedOutCount = $todayAttendances->filter(function($attendance) {
            return $attendance->punch_out_time !== null;
        })->count();

        return view('admin.attendance.index', compact(
            'employees',
            'todayAttendances',
            'punchedInCount',
            'punchedOutCount'
        ));
    }

    public function generateQrCode(Request $request)
    {
        Log::info('QR Code generation request received', [
            'employee_id' => $request->employee_id,
            'action' => $request->action,
            'headers' => $request->headers->all(),
        ]);

        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'action' => 'required|in:punch_in,punch_out',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        Log::info('Employee found for QR generation', ['employee' => $employee->full_name]);

        // Generate unique QR code data
        $timestamp = now()->timestamp;
        $randomString = Str::random(16);
        $qrData = json_encode([
            'employee_id' => $employee->id,
            'employee_email' => $employee->email,
            'action' => $request->action,
            'timestamp' => $timestamp,
            'token' => $randomString,
            'expires_at' => now()->addSeconds(10)->timestamp, // 10 second expiry
        ]);

        // Store QR data temporarily (you can use cache, database, or just return it)
        $qrIdentifier = 'qr_' . $timestamp . '_' . $randomString;

        // Cache the QR data for verification (expires in 10 seconds)
        Cache::put($qrIdentifier, $qrData, 10);

        // For now, return a data URL that can be converted to QR code
        // In production, you'd use a proper QR library
        $qrCodeData = base64_encode($qrData);

        return response()->json([
            'success' => true,
            'qr_identifier' => $qrIdentifier,
            'qr_data' => $qrCodeData,
            'employee_name' => $employee->full_name,
            'employee_photo' => $employee->photo ? asset('storage/' . $employee->photo) : null,
            'action' => $request->action,
            'expires_in_seconds' => 10,
        ]);
    }

    // Method to verify QR code scan (called by mobile app)
    public function verifyQrCode(Request $request)
    {
        $request->validate([
            'qr_identifier' => 'required|string',
            'employee_email' => 'required|email',
        ]);

        // Get QR data from cache
        $qrData = Cache::get($request->qr_identifier);

        if (!$qrData) {
            return response()->json([
                'success' => false,
                'message' => 'QR code expired or invalid',
            ]);
        }

        $qrDetails = json_decode($qrData);

        // Verify the QR code belongs to the requesting employee
        if ($qrDetails->employee_email !== $request->employee_email) {
            return response()->json([
                'success' => false,
                'message' => 'QR code does not match employee',
            ]);
        }

        // Check if QR code is expired
        if (now()->timestamp > $qrDetails->expires_at) {
            return response()->json([
                'success' => false,
                'message' => 'QR code expired',
            ]);
        }

        // Record the attendance
        $timestamp = now();
        $employee = Employee::where('email', $qrDetails->employee_email)->first();

        if ($qrDetails->action === 'punch_in') {
            $attendance = Attendance::recordPunchIn($employee->id, $timestamp, 'qr', 'office');
            $message = 'Punch In recorded successfully at ' . $timestamp->format('H:i:s');
        } else {
            $attendance = Attendance::recordPunchOut($employee->id, $timestamp, 'qr', 'office');
            $message = 'Punch Out recorded successfully at ' . $timestamp->format('H:i:s');
        }

        Cache::forget($request->qr_identifier); // Invalidate QR code after use

        // Log the activity (you can customize this based on your logging needs)
        Log::info("Attendance recorded", [
            'employee_id' => $employee->id,
            'employee_name' => $employee->full_name,
            'action' => $qrDetails->action,
            'timestamp' => $timestamp->toISOString(),
            'worked_hours' => $attendance->worked_hours ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'action' => $qrDetails->action,
            'timestamp' => $timestamp->toISOString(),
            'worked_hours' => $attendance->formatted_worked_hours,
            'employee_name' => $employee->full_name,
        ]);
    }

    // Check if attendance was recorded (for polling)
    public function checkAttendanceStatus($qrIdentifier)
    {
        // If QR identifier exists in cache, attendance hasn't been recorded yet
        $qrExists = Cache::has($qrIdentifier);

        if (!$qrExists) {
            // QR was used/scanned, get the last attendance record for this employee
            // We can determine which employee by looking at recent attendance records
            $recentAttendance = Attendance::where('date', today())
                ->where(function($query) {
                    $query->where('punch_in_time', '>=', now()->subMinutes(2))
                          ->orWhere('punch_out_time', '>=', now()->subMinutes(2));
                })
                ->with('employee')
                ->latest()
                ->first();

            if ($recentAttendance) {
                return response()->json([
                    'success' => true,
                    'attendance_recorded' => true,
                    'employee_id' => $recentAttendance->employee_id,
                    'employee_name' => $recentAttendance->employee->full_name,
                    'action' => $recentAttendance->punch_out_time ? 'punch_out' : 'punch_in',
                    'timestamp' => $recentAttendance->punch_out_time ?: $recentAttendance->punch_in_time,
                    'worked_hours' => $recentAttendance->worked_hours,
                    'formatted_hours' => $recentAttendance->formatted_worked_hours,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'attendance_recorded' => false,
        ]);
    }
}
