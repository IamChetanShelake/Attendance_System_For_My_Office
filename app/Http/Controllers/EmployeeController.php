<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('created_at', 'desc')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'dob' => 'required|date|before:today',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:employees,email',
            'department' => 'required|string|max:255',
            'type' => 'required|in:intern,onrole',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'onrole_date' => 'nullable|date|after_or_equal:start_date',
            'aadhaar_number' => 'nullable|string|size:12|unique:employees,aadhaar_number',
            'wfh_pin' => 'required|string|size:6|regex:/^[0-9]+$/|unique:employees,wfh_pin',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee = new Employee();
        $employee->employee_id = $employee->generateEmployeeId();
        $employee->fill($request->except(['photo']));

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
            $employee->photo = $photoPath;
        }

        $employee->save();

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully!');
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'dob' => 'required|date|before:today',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'department' => 'required|string|max:255',
            'type' => 'required|in:intern,onrole',
            'position' => 'required|string|max:255',
            'start_date' => 'required|date',
            'onrole_date' => 'nullable|date|after_or_equal:start_date',
            'aadhaar_number' => 'nullable|string|size:12|unique:employees,aadhaar_number,' . $employee->id,
            'wfh_pin' => 'required|string|size:6|regex:/^[0-9]+$/|unique:employees,wfh_pin,' . $employee->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $employee->fill($request->except(['photo']));

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                Storage::disk('public')->delete($employee->photo);
            }
            $photoPath = $request->file('photo')->store('employees/photos', 'public');
            $employee->photo = $photoPath;
        }

        $employee->save();

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        // Delete photo if exists
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }

        // Documents will be cascade deleted by database foreign key constraint

        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully!');
    }

    public function updateProbation(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'probation_start_date' => 'required|date',
            'probation_end_date' => 'required|date|after:probation_start_date',
            'probation_status' => 'nullable|in:active,completed,extended,failed',
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $employee->update([
            'probation_start_date' => $request->probation_start_date,
            'probation_end_date' => $request->probation_end_date,
            'probation_status' => $request->probation_status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Probation details updated successfully!'
        ]);
    }
}
