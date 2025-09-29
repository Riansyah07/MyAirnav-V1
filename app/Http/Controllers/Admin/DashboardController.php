<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Certificate;
use App\Models\Loca;
use App\Models\Isr; // PASTIKAN ANDA MEMILIKI MODEL INI DAN SUDAH DI-IMPORT
use Illuminate\Support\Facades\Auth; // DISARANKAN: Untuk memfilter data

class DashboardController extends Controller
{
    public function index()
    {
        // === Perhitungan untuk Stat Cards ===

        // Data ini mungkin tetap global untuk admin
        $totalUsers = User::count(); 
        
        // DISARANKAN: Filter data berdasarkan user yang login
        $userId = Auth::id();
        $totalDocuments = Document::count();
        $totalCertificates = Certificate::count();
        $totalLoca = Loca::count();
        $totalIsr = Isr::where('user_id', $userId)->count(); 
        $recentDocuments = Document::where('uploaded_by', $userId)
                                    ->latest('updated_at')
                                    ->take(5)
                                    ->get();

        // Variabel yang dikirim ke view
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalDocuments', 
            'totalCertificates', 
            'totalLoca', 
            'totalIsr',
            'recentDocuments'
        ));
    }
}