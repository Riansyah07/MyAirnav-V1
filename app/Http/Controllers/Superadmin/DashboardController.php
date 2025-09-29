<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Certificate;
use App\Models\Loca;

class DashboardController extends Controller
{
    public function index()
{
    $totalUsers = User::count();
    $totalDocuments = Document::count();
    $totalCertificates = Certificate::count();
    $totalLoca = Loca::count();
    $recentDocuments = Document::with('user')->latest('updated_at')->take(5)->get();

    return view('superadmin.dashboard', compact('totalUsers', 'totalDocuments', 'totalCertificates', 'totalLoca', 'recentDocuments'));
}
}

