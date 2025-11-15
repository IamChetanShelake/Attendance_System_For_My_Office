<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountabilityController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('employee')->orderBy('created_at', 'desc')->get();
        return view('admin.accountability.index', compact('salaries'));
    }

    public function create()
    {
        $employees = Employee::whereDoesntHave('salary')->orderBy('first_name')->get();
        return view('admin.accountability.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id|unique:salaries,employee_id',
            'salary_amount' => 'required|numeric|min:0',
            'pan_number' => 'nullable|string|max:10',
            'aadhaar_number' => 'nullable|string|size:12|regex:/^[0-9]+$/',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:11',
            'branch_name' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'basic_salary' => 'nullable|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'conveyance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'lta' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Salary::create($request->all());

        return redirect()->route('admin.accountability.index')->with('success', 'Salary details created successfully!');
    }

    public function show($id)
    {
        $salary = Salary::with('employee')->findOrFail($id);
        return view('admin.accountability.show', compact('salary'));
    }

    public function edit($id)
    {
        $salary = Salary::with('employee')->findOrFail($id);
        return view('admin.accountability.edit', compact('salary'));
    }

    public function update(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'salary_amount' => 'required|numeric|min:0',
            'pan_number' => 'nullable|string|max:10',
            'aadhaar_number' => 'nullable|string|size:12|regex:/^[0-9]+$/',
            'account_holder_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:20',
            'bank_name' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:11',
            'branch_name' => 'nullable|string|max:255',
            'effective_date' => 'required|date',
            'status' => 'required|in:active,inactive',
            'basic_salary' => 'nullable|numeric|min:0',
            'hra' => 'nullable|numeric|min:0',
            'conveyance' => 'nullable|numeric|min:0',
            'medical_allowance' => 'nullable|numeric|min:0',
            'lta' => 'nullable|numeric|min:0',
            'special_allowance' => 'nullable|numeric|min:0',
            'provident_fund' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $salary->update($request->all());

        return redirect()->route('admin.accountability.index')->with('success', 'Salary details updated successfully!');
    }

    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return redirect()->route('admin.accountability.index')->with('success', 'Salary details deleted successfully!');
    }
}
