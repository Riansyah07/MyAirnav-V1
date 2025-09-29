<?php

namespace App\Http\Controllers\User;

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

        return view('user.isr.index', compact('isrs', 'search', 'date', 'sort'));
    }
    public function show($id)
    {
        $isr = Isr::findOrFail($id);
        $fileUrl = Storage::url($isr->file_path);
        return view('user.isr.show', compact('isr', 'fileUrl'));
    }


}
