<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DocumentActionNotification;

class LocaController extends Controller
{
    public function index(Request $request)
    {
        $query = Loca::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('sort')) {
            $query->orderBy('name', $request->sort);
        } else {
            $query->latest();
        }

        $locas = $query->paginate(10);

        return view('admin.loca.index', compact('locas'));
    }

    public function create()
    {
        return view('admin.loca.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|in:Pengantar,Internal,Eksternal',
        'note' => 'nullable|string',
        'file' => 'required|mimes:pdf,docx|max:5120',
    ]);

    $uploadedFile = $request->file('file');
    $filePath = $request->file('file')->store('loca', 'public');
    $fileType = $uploadedFile->getClientOriginalExtension();

    $loca = Loca::create([
        'name' => $request->name,
        'category' => $request->category,
        'note' => $request->note,
        'file_path' => $filePath,
        'file_type' => $fileType,
        'user_id' => Auth::id(),
    ]);

    Auth::user()->notify(new DocumentActionNotification('Dokumen LOCA "' . $loca->name . '" berhasil diupload.'));

    return redirect()->route('admin.loca.index')->with('success', 'Dokumen LOCA berhasil diupload.');
}


    public function show($id)
    {
        $loca = Loca::with('user')->findOrFail($id);
        $fileUrl = Storage::url($loca->file_path);
        return view('admin.loca.show', compact('loca', 'fileUrl'));
    }


    public function edit(Loca $loca)
    {
        $loca->load('user');
        return view('admin.loca.edit', compact('loca'));
    }

    public function update(Request $request, Loca $loca)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category' => 'required|in:Pengantar,Internal,Eksternal',
        'note' => 'nullable|string',
        'file' => 'nullable|mimes:pdf,docx|max:5120',
    ]);

    if ($request->hasFile('file')) {
        if ($loca->file_path && Storage::exists($loca->file_path)) {
            Storage::delete($loca->file_path);
        }

        $uploadedFile = $request->file('file');
        $filePath = $uploadedFile->store('loca_documents');
        $fileType = $uploadedFile->getClientOriginalExtension();

        $loca->file_path = $filePath;
        $loca->file_type = $fileType;
    }

    $loca->update([
        'name' => $request->name,
        'category' => $request->category,
        'note' => $request->note,
        'file_path' => $loca->file_path,
        'file_type' => $loca->file_type,
    ]);

    Auth::user()->notify(new DocumentActionNotification('Dokumen LOCA "' . $loca->name . '" berhasil diperbarui.'));

    return redirect()->route('admin.loca.index')->with('success', 'Dokumen LOCA berhasil diperbarui.');
}


    public function destroy(Loca $loca)
    {
        if ($loca->file_path && Storage::exists($loca->file_path)) {
            Storage::delete($loca->file_path);
        }

        $name = $loca->name;
        $loca->delete();

        Auth::user()->notify(new DocumentActionNotification('Dokumen LOCA "' . $name . '" berhasil dihapus.'));

        return redirect()->route('admin.loca.index')->with('success', 'Dokumen LOCA berhasil dihapus.');
    }
}
