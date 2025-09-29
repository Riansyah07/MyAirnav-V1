<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loca;
use Illuminate\Support\Facades\Storage;

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

        return view('user.loca.index', compact('locas'));
    }


    public function show($id)
    {
        $loca = Loca::with('user')->findOrFail($id);
        $fileUrl = Storage::url($loca->file_path);
        return view('user.loca.show', compact('loca', 'fileUrl'));
    }

    
}
