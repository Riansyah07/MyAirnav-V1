<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Isr;
use App\Notifications\DocumentActionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class IsrController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $date = $request->input('date');
        $sort = $request->input('sort');

        $query = Isr::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $search) . '%');
        }

        if (!empty($date)) {
            $query->whereDate('created_at', $date);
        }

        $sort ? $query->orderBy('name', $sort) : $query->latest();

        $isrs = $query->paginate(10)->withQueryString();

        return view('admin.isr.index', compact('isrs', 'search', 'date', 'sort'));
    }

    public function create()
    {
        return view('admin.isr.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,docx|max:5120',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('isr', 'public');
        $fileType = $file->getClientOriginalExtension();

        $isr = Isr::create([
            'name' => $request->name,
            'note' => $request->note,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'user_id' => Auth::id(),
        ]);

        Auth::user()->notify(new DocumentActionNotification('Dokumen ISR "' . $isr->name . '" berhasil diupload.'));

        return redirect()->route('admin.isr.index')->with('success', 'Dokumen berhasil diupload.');
    }

    public function show($id)
    {
        $isr = Isr::findOrFail($id);
        $fileUrl = Storage::url($isr->file_path);
        return view('admin.isr.show', compact('isr', 'fileUrl'));
    }

    public function edit($id)
    {
        $isr = Isr::findOrFail($id);
        return view('admin.isr.edit', compact('isr'));
    }

    public function update(Request $request, $id)
    {
        $isr = Isr::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'note' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,docx|max:5120',
        ]);

        $data = [
            'name' => $request->name,
            'note' => $request->note,
        ];

        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($isr->file_path)) {
                Storage::disk('public')->delete($isr->file_path);
            }

            $file = $request->file('file');
            $newFilePath = $file->store('isr', 'public');
            $data['file_path'] = $newFilePath;
            $data['file_type'] = $file->getClientOriginalExtension();
        }

        $isr->update($data);

        Auth::user()->notify(new DocumentActionNotification('Dokumen ISR "' . $isr->name . '" berhasil diperbarui.'));

        return redirect()->route('admin.isr.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $isr = Isr::findOrFail($id);

            if (Storage::disk('public')->exists($isr->file_path)) {
                Storage::disk('public')->delete($isr->file_path);
            }

            $isr->delete();

            Auth::user()->notify(new DocumentActionNotification('Dokumen ISR "' . $isr->name . '" berhasil dihapus.'));

            if (request()->ajax()) {
                return response()->json(['success' => 'Dokumen berhasil dihapus.']);
            }

            return redirect()->route('admin.isr.index')->with('success', 'Dokumen berhasil dihapus.');
        } catch (\Exception $e) {
            report($e);
            return redirect()->back()->with('error', 'Gagal menghapus dokumen.');
        }
    }

}
