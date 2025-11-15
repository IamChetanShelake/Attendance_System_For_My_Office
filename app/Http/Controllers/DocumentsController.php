<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentsController extends Controller
{
    public function index()
    {
        $employeesWithDocuments = Employee::whereHas('documents')
            ->with(['documents' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->withCount('documents')
            ->orderBy('first_name')
            ->get();

        return view('admin.documents.index', compact('employeesWithDocuments'));
    }

    public function employeeDocuments($employeeId)
    {
        $employee = Employee::with(['documents' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($employeeId);

        return view('admin.documents.employee-documents', compact('employee'));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('admin.documents.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'document_names.*' => 'required|string|max:255',
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,ppt,pptx,txt|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $uploadedDocuments = [];

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $documentName = $request->document_names[$index] ?? 'Document ' . ($index + 1);
                $originalName = $file->getClientOriginalName();
                $fileSize = $file->getSize();
                $mimeType = $file->getMimeType();

                // Store file
                $filePath = $file->store('documents', 'public');

                // Create document record
                Document::create([
                    'employee_id' => $request->employee_id,
                    'document_name' => $documentName,
                    'document_path' => $filePath,
                    'original_filename' => $originalName,
                    'file_size' => $fileSize,
                    'mime_type' => $mimeType,
                    'description' => $request->descriptions[$index] ?? null,
                ]);

                $uploadedDocuments[] = $documentName;
            }
        }

        return redirect()->route('admin.documents.index')
            ->with('success', 'Documents uploaded successfully! (' . count($uploadedDocuments) . ' files)');
    }

    public function show($id)
    {
        $document = Document::with('employee')->findOrFail($id);
        return view('admin.documents.show', compact('document'));
    }

    public function edit($id)
    {
        $document = Document::with('employee')->findOrFail($id);
        return view('admin.documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'document_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx,ppt,pptx,txt|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $document->document_name = $request->document_name;
        $document->description = $request->description;

        // Handle file replacement
        if ($request->hasFile('document')) {
            // Delete old file
            if ($document->document_path && Storage::disk('public')->exists($document->document_path)) {
                Storage::disk('public')->delete($document->document_path);
            }

            // Store new file
            $file = $request->file('document');
            $filePath = $file->store('documents', 'public');

            $document->document_path = $filePath;
            $document->original_filename = $file->getClientOriginalName();
            $document->file_size = $file->getSize();
            $document->mime_type = $file->getMimeType();
        }

        $document->save();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document updated successfully!');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Delete file from storage
        if ($document->document_path && Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted successfully!');
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);

        if (!$document->document_path || !Storage::disk('public')->exists($document->document_path)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        $filePath = storage_path('app/public/' . $document->document_path);
        return response()->download($filePath, $document->original_filename);
    }
}
