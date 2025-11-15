<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccessoriesController extends Controller
{
    public function index()
    {
        $accessories = Accessory::with('employee')->orderBy('created_at', 'desc')->get();
        return view('admin.accessories.index', compact('accessories'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('admin.accessories.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'accessory_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255',
            'model_number' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'allocation_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:allocation_date',
            'status' => 'required|in:allocated,returned,lost,damaged',
            'condition_notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $accessory = new Accessory();
        $accessory->fill($request->except(['photo']));

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('accessories/photos', 'public');
            $accessory->photo = $photoPath;
        }

        $accessory->save();

        return redirect()->route('admin.accessories.index')
            ->with('success', 'Accessory allocated successfully!');
    }

    public function show($id)
    {
        $accessory = Accessory::with('employee')->findOrFail($id);
        return view('admin.accessories.show', compact('accessory'));
    }

    public function edit($id)
    {
        $accessory = Accessory::with('employee')->findOrFail($id);
        $employees = Employee::orderBy('first_name')->get();
        return view('admin.accessories.edit', compact('accessory', 'employees'));
    }

    public function update(Request $request, $id)
    {
        $accessory = Accessory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'accessory_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255',
            'model_number' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'allocation_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:allocation_date',
            'status' => 'required|in:allocated,returned,lost,damaged',
            'condition_notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $accessory->fill($request->except(['photo']));

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($accessory->photo && Storage::disk('public')->exists($accessory->photo)) {
                Storage::disk('public')->delete($accessory->photo);
            }
            $photoPath = $request->file('photo')->store('accessories/photos', 'public');
            $accessory->photo = $photoPath;
        }

        $accessory->save();

        return redirect()->route('admin.accessories.index')
            ->with('success', 'Accessory updated successfully!');
    }

    public function destroy($id)
    {
        $accessory = Accessory::findOrFail($id);

        // Delete photo if exists
        if ($accessory->photo && Storage::disk('public')->exists($accessory->photo)) {
            Storage::disk('public')->delete($accessory->photo);
        }

        $accessory->delete();

        return redirect()->route('admin.accessories.index')
            ->with('success', 'Accessory deleted successfully!');
    }
}
