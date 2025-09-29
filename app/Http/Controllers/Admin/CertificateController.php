<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Notifications\DocumentActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $date = $request->input('date');
        $sort = $request->input('sort');

        $query = Certificate::query();

        if (!empty($search)) {
            $query->where('title', 'like', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $search) . '%');
        }

        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }

        if (!empty($sort)) {
            $query->orderBy('title', $sort);
        } else {
            $query->latest();
        }

        $sertifikat = $query->paginate(10)->withQueryString();

        return view('admin.sertifikat.index', compact('sertifikat', 'search', 'date', 'sort'));
    }

    public function create()
    {
        return view('admin.sertifikat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,docx|max:5120',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('sertifikat', 'public');
        $fileType = $file->getClientOriginalExtension();

        $certificate = Certificate::create([
            'title' => $request->title,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'user_id' => Auth::id(),
        ]);

        Auth::user()->notify(new DocumentActionNotification('Sertifikat "' . $certificate->title . '" berhasil diupload.'));

        return redirect()->route('admin.sertifikat.index')->with('success', 'Sertifikat berhasil diupload.');
    }

    public function edit($id)
    {
        $sertifikat = Certificate::findOrFail($id);
        return view('admin.sertifikat.edit', compact('sertifikat'));
    }

    public function update(Request $request, $id)
    {
        $sertifikat = Certificate::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,docx|max:5120',
        ]);

        $updateData = ['title' => $request->title];

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($sertifikat->file_path)) {
                Storage::disk('public')->delete($sertifikat->file_path);
            }

            $file = $request->file('file');
            $newFilePath = $file->store('sertifikat', 'public');
            $fileType = $file->getClientOriginalExtension();

            $updateData['file_path'] = $newFilePath;
            $updateData['file_type'] = $fileType;
        }

        $sertifikat->update($updateData);

        Auth::user()->notify(new DocumentActionNotification('Sertifikat "' . $sertifikat->title . '" berhasil diperbarui.'));

        return redirect()->route('admin.sertifikat.index')->with('success', 'Sertifikat berhasil diperbarui.');
    }

    public function show($id)
    {
        $sertifikat = Certificate::findOrFail($id);
        $fileUrl = Storage::url($sertifikat->file_path);
        return view('admin.sertifikat.show', compact('sertifikat', 'fileUrl'));
    }

    public function destroy($id)
    {
        try {
            $certificate = Certificate::findOrFail($id);

            if (Storage::disk('public')->exists($certificate->file_path)) {
                Storage::disk('public')->delete($certificate->file_path);
            }

            $certificate->delete();

            Auth::user()->notify(new DocumentActionNotification('Sertifikat "' . $certificate->title . '" berhasil dihapus.'));

            if (request()->ajax()) {
                return response()->json(['success' => 'Sertifikat berhasil dihapus.']);
            }

            return redirect()->route('admin.sertifikat.index')->with('success', 'Sertifikat berhasil dihapus.');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal menghapus sertifikat. Coba lagi nanti.');
        }
    }

    public function bulkDownload(Request $request)
    {
        $request->validate([
            'certificate_ids' => 'required|array',
            'certificate_ids.*' => 'integer|exists:certificates,id',
        ]);

        $sertifikat = Certificate::whereIn('id', $request->certificate_ids)->get();

        if ($sertifikat->isEmpty()) {
            return response()->json(['error' => 'Tidak ada sertifikat yang dipilih.'], 400);
        }

        $zip = new ZipArchive;
        $zipFileName = 'sertifikat-terpilih-' . now()->format('YmdHis') . '-' . uniqid() . '.zip';
        $zipPath = Storage::disk('public')->path($zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            foreach ($sertifikat as $item) {
                $filePath = Storage::disk('public')->path($item->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, basename($filePath));
                }
            }
            $zip->close();
        }

        return response()->json([
            'success' => true,
            'zip_url' => asset('storage/' . $zipFileName),
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'certificate_ids' => 'required|array',
            'certificate_ids.*' => 'integer|exists:certificates,id',
        ]);

        $sertifikat = Certificate::whereIn('id', $request->certificate_ids)->get();

        if ($sertifikat->isEmpty()) {
            return response()->json(['error' => 'Tidak ada sertifikat valid untuk dihapus.'], 400);
        }

        foreach ($sertifikat as $item) {
            if (Storage::disk('public')->exists($item->file_path)) {
                Storage::disk('public')->delete($item->file_path);
            }
            $item->delete();
        }

        return response()->json(['success' => 'Sertifikat terpilih berhasil dihapus.']);
    }
}
