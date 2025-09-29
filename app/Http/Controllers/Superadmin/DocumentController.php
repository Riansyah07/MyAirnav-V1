<?php

namespace App\Http\Controllers\Superadmin;

use App\Notifications\DocumentActionNotification;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\VsmService;
use Illuminate\Http\Response;
use ZipArchive;
use File;

class DocumentController extends Controller
{
    
    public function index(Request $request, VsmService $vsmService)
    {
        $search = $request->input('search');
        $documentsQuery = Document::query();

        if (!empty($search)) {
            // Dapatkan daftar ID dokumen yang sudah diurutkan dari VsmService
            $rankedDocumentIds = $vsmService->getRankedDocuments($search);

            if (!empty($rankedDocumentIds)) {
                // Ambil dokumen dari database berdasarkan urutan ID
                $documentsQuery->whereIn('id', $rankedDocumentIds)
                               ->orderByRaw("FIELD(id, " . implode(',', $rankedDocumentIds) . ")");
            } else {
                // Jika VSM tidak menemukan hasil, jangan tampilkan apa-apa
                $documentsQuery->whereRaw('1 = 0'); 
            }
        } else {
            // Jika tidak ada pencarian, tampilkan dokumen terbaru
            $documentsQuery->latest();
        }
        
        // Logika filter tambahan (jika masih diperlukan)
        $category = $request->input('category');
        if (!empty($category)) {
            $documentsQuery->where('category', $category);
        }

        $documents = $documentsQuery->paginate(10)->withQueryString();
        return view('superadmin.documents.index', compact('documents', 'search', 'category'));
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        $document->file_url = Storage::url($document->file_path);
        return view('superadmin.documents.show', compact('document'));
    }

    public function create()
    {
        return view('superadmin.documents.create');
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('superadmin.documents.edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $document = Document::findOrFail($id);

        // DIPERBAIKI: Validasi disederhanakan
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string', // Sesuaikan dengan enum di database Anda
            'note' => 'nullable|string',
            'file' => 'nullable|mimes:pdf|max:5120', // Hanya PDF
        ]);

        $documentData = $validatedData;

        if ($request->hasFile('file')) {
            Storage::delete('public/' . $document->file_path);
            $documentData['file_path'] = $request->file('file')->store('documents', 'public');
            $documentData['file_type'] = $request->file('file')->getClientOriginalExtension();
        }
        
        $document->update($documentData);
        
        Auth::user()->notify(new DocumentActionNotification('Dokumen "' . $document->title . '" berhasil diperbarui.'));
        return redirect()->route('superadmin.documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Menyimpan dokumen baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string', // Sesuaikan dengan enum di database Anda
            'note' => 'nullable|string',
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        $filePath = $request->file('file')->store('documents', 'public');
        $fileType = $request->file('file')->getClientOriginalExtension();

        Document::create([
            'title' => $validatedData['title'],
            'category' => $validatedData['category'],
            'note' => $validatedData['note'],
            'file_path' => $filePath,
            'file_type' => $fileType,
            'uploaded_by' => Auth::id(),
        ]);
        
        Auth::user()->notify(new DocumentActionNotification('Dokumen "' . $validatedData['title'] . '" berhasil diupload.'));
        return redirect()->route('superadmin.documents.index')->with('success', 'Dokumen berhasil diupload.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        Auth::user()->notify(new DocumentActionNotification('Dokumen "' . $document->title . '" berhasil dihapus.'));

        return redirect()->route('superadmin.documents.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function bulkDownload(Request $request)
    {
        $documentIds = $request->input('document_ids');

        if (!$documentIds || count($documentIds) === 0) {
            return response()->json(['error' => 'Tidak ada dokumen yang dipilih.'], 400);
        }

        if (count($documentIds) > 20) {
            return response()->json(['error' => 'Maksimal 20 file dapat diunduh dalam satu ZIP.'], 400);
        }

        $zipFileName = 'documents_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            return response()->json(['error' => 'Gagal membuat file ZIP.'], 500);
        }

        $documents = Document::whereIn('id', $documentIds)->get();

        foreach ($documents as $document) {
            $filePath = storage_path('app/public/' . $document->file_path);

            if (!file_exists($filePath)) {
                continue;
            }

            $zip->addFile($filePath, basename($filePath));
        }

        $zip->close();

        return response()->json([
            'success' => 'ZIP berhasil dibuat.',
            'zip_url' => asset('storage/' . $zipFileName)
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $documentIds = $request->input('document_ids');

        if (!$documentIds || count($documentIds) === 0) {
            return response()->json(['error' => 'Tidak ada dokumen yang dipilih.'], 400);
        }

        $documents = Document::whereIn('id', $documentIds)->get();

        foreach ($documents as $document) {
            if (Storage::exists('public/' . $document->file_path)) {
                Storage::delete('public/' . $document->file_path);
            }
            $document->delete();
        }

        return response()->json(['success' => 'Dokumen berhasil dihapus.']);
    }

    public function showCategory($category)
    {
        // Validasi kategori agar hanya 'teknik', 'operasi', atau 'k3'
        if (!in_array($category, ['teknik', 'operasi', 'k3'])) {
            abort(404);
        }

        return view("superadmin.documents.$category");

    }

    
}
