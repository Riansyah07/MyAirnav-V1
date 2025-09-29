<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Certificate;
use App\Models\Loca;
use App\Models\Isr;

class DashboardController extends Controller
{
    public function index()
{
    $totalDocuments = Document::count();
    $totalCertificates = Certificate::count();
    $totalLoca = Loca::count();
    $totalIsr = Isr::count();
    

    return view('user.dashboard', compact('totalDocuments', 'totalCertificates', 'totalLoca', 'totalIsr'));
}
}

