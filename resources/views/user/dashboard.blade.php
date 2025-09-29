@extends('layouts.main')

@section('title', 'User Dashboard')

@section('content')
    {{-- Carousel Section --}}
    <div id="dashboardCarousel" class="carousel slide carousel-fade position-relative mb-4" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false">
        

        {{-- Gambar Slide --}}
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/img/background-img.jpg') }}" class="d-block w-100 rounded" style="height: 500px; object-fit: cover;" alt="Slide 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/background-img2.jpg') }}" class="d-block w-100 rounded" style="height: 500px; object-fit: cover;" alt="Slide 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/img/background-img3.jpg') }}" class="d-block w-100 rounded" style="height: 500px; object-fit: cover;" alt="Slide 3">
            </div>
        </div>

        {{-- Overlay Konten --}}
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center text-black text-center" style="background-color: rgba(230, 229, 229, 0.4);">
            <div class="card bg-white bg-opacity-40 shadow-sm w-90 h-90 border-0 rounded-0">
                <div class="card-body h-100 d-flex flex-column justify-content-center align-items-center">
                    <p class="lead fw-bold mt-3"><b>My AirNav</b> adalah aplikasi berbasis web yang dirancang untuk mengelola 
                    dokumen-dokumen penting terkait <b>Manual Operasi Pelayanan Manajemen Lalu Lintas dan Telekomunikasi 
                    Penerbangan di lingkungan Perum LPPNPI Cabang Pontianak.</b> Aplikasi ini bertujuan untuk mempermudah proses penyimpanan, 
                    pengelompokan, pencarian, serta distribusi dokumen operasional agar lebih efisien, terstruktur, dan sesuai dengan standar 
                    pelayanan navigasi penerbangan. Dengan antarmuka yang ramah pengguna dan sistem manajemen dokumen yang terpusat. Kelola seluruh sistem dengan mudah, aman, dan cepat.</p>
                    
                    {{-- Tambahan 2 Card --}}
                    <div class="row mt-4 w-100 px-5">
                        <div class="col-md-6">
                            <div class="card text-white bg-success shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Jumlah Dokumen Manual Operasi</h6>
                                    <h3 class="fw-bold">{{ $totalDocuments }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-white bg-info shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Jumlah Sertifikat</h6>
                                    <h3 class="fw-bold">{{ $totalCertificates }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="card text-white bg-warning shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Jumlah Dokumen Loca</h6>
                                    <h3 class="fw-bold">{{ $totalLoca }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <div class="card text-white bg-success shadow-sm">
                                <div class="card-body text-center">
                                    <h6 class="card-title">Jumlah Dokumen ISR</h6>
                                    <h3 class="fw-bold">{{ $totalIsr }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

