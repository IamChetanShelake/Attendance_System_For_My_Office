<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeLoginController extends Controller
{
    public function index()
    {
        $employeeLogins = EmployeeLogin::with('employee')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get employees who don't have login credentials yet
        $employeesWithoutLogin = Employee::whereDoesntHave('loginCredentials')
            ->orderBy('first_name')
            ->get();

        return view('admin.employee-logins.index', compact('employeeLogins', 'employeesWithoutLogin'));
    }

    public function create()
    {
        // Get employees who don't have login credentials yet
        $availableEmployees = Employee::whereDoesntHave('loginCredentials')
            ->orderBy('first_name')
            ->get();

        return view('admin.employee-logins.create', compact('availableEmployees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'required|integer|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $created = 0;

        foreach ($request->employee_ids as $employeeId) {
            // Check if login already exists for this employee
            $existingLogin = EmployeeLogin::where('employee_id', $employeeId)->first();

            if ($existingLogin) {
                continue; // Skip if already exists
            }

            // Get the employee
            $employee = Employee::find($employeeId);

            if ($employee) {
                // Generate email (use company email format)
                $email = $this->generateEmployeeEmail($employee);

                // Generate password in format: name#(employeeid)
                $password = EmployeeLogin::generateSecurePassword($employee);

                // Create login credentials
                EmployeeLogin::create([
                    'employee_id' => $employeeId,
                    'email' => $email,
                    'password' => $password, // This will be hashed by the model mutator
                    'is_active' => true,
                ]);

                $created++;
            }
        }

        $message = $created === 1
            ? 'Login credentials created successfully!'
            : "{$created} login credentials created successfully!";

        return redirect()->route('admin.employee-logins.index')
            ->with('success', $message);
    }

    public function show($id)
    {
        $employeeLogin = EmployeeLogin::with('employee')->findOrFail($id);
        return view('admin.employee-logins.show', compact('employeeLogin'));
    }

    public function edit($id)
    {
        $employeeLogin = EmployeeLogin::with('employee')->findOrFail($id);
        return view('admin.employee-logins.edit', compact('employeeLogin'));
    }

    public function update(Request $request, $id)
    {
        $employeeLogin = EmployeeLogin::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:employee_logins,email,' . $id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'email' => $request->email,
            'is_active' => $request->is_active,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = $request->password; // This will be hashed by the model mutator
        }

        $employeeLogin->update($updateData);

        return redirect()->route('admin.employee-logins.index')
            ->with('success', 'Login credentials updated successfully!');
    }

    public function destroy($id)
    {
        $employeeLogin = EmployeeLogin::findOrFail($id);
        $employeeLogin->delete();

        return redirect()->route('admin.employee-logins.index')
            ->with('success', 'Login credentials deleted successfully!');
    }

    // Additional methods for specific actions
    public function generateForEmployee(Request $request, $employeeId)
    {
        // Check if login already exists
        $existingLogin = EmployeeLogin::where('employee_id', $employeeId)->first();

        if ($existingLogin) {
            return redirect()->route('admin.employee-logins.index')
                ->with('error', 'Login credentials already exist for this employee!');
        }

        // Get the employee
        $employee = Employee::findOrFail($employeeId);

        // Generate email and password
        $email = $this->generateEmployeeEmail($employee);
        $password = EmployeeLogin::generateSecurePassword($employee);

        // Create login credentials
        EmployeeLogin::create([
            'employee_id' => $employeeId,
            'email' => $email,
            'password' => $password,
            'is_active' => true,
        ]);

        return redirect()->route('admin.employee-logins.index')
            ->with('success', 'Login credentials generated successfully!');
    }

    public function resetPassword($id)
    {
        $employeeLogin = EmployeeLogin::with('employee')->findOrFail($id);
        $newPassword = EmployeeLogin::generateSecurePassword($employeeLogin->employee);

        $employeeLogin->update(['password' => $newPassword]);

        return redirect()->route('admin.employee-logins.show', $id)
            ->with('success', 'Password reset successfully! New password: ' . $newPassword);
    }

    public function toggleStatus($id)
    {
        $employeeLogin = EmployeeLogin::findOrFail($id);

        $employeeLogin->update([
            'is_active' => !$employeeLogin->is_active,
            'login_attempts' => 0, // Reset attempts when toggling status
        ]);

        $status = $employeeLogin->fresh()->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.employee-logins.index')
            ->with('success', "Account {$status} successfully!");
    }

    // Helper method to generate employee email
    private function generateEmployeeEmail($employee)
    {
        // Create email using first name and last name: name.surname@techmetworks.com
        $baseEmail = strtolower($employee->first_name) . '.' . strtolower($employee->last_name) . '@techmetworks.com';

        // Ensure uniqueness
        $originalEmail = $baseEmail;
        $counter = 1;

        while (EmployeeLogin::where('email', $baseEmail)->exists()) {
            $baseEmail = strtolower($employee->first_name) . '.' . strtolower($employee->last_name) . $counter . '@techmetworks.com';
            $counter++;
        }

        return $baseEmail;
    }
}
