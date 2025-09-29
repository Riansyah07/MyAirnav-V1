@extends('layouts.main')

@section('title', 'Detail Sertifikat')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h1>Detail Sertifikat</h1>

    <!-- Tombol Kembali -->
    <a href="{{ route('user.sertifikat.index') }}" class="btn btn-warning mb-3">← Kembali</a>

    <div class="row">
        <!-- Kolom Kiri: Metadata Sertifikat -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $sertifikat->title }}</h4>
                    <p><strong>Jenis File:</strong> {{ strtoupper($sertifikat->file_type) }}</p>
                    <p><strong>Diunggah Oleh:</strong> {{ $sertifikat->user->name ?? 'Tidak diketahui' }}</p>
                    <p><strong>Tanggal Upload:</strong> {{ $sertifikat->created_at->setTimezone('Asia/Jakarta')->format('d M Y - H:i:s') }}</p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Preview Sertifikat -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Preview Sertifikat</h5>

                    @if($sertifikat->file_type === 'pdf')
                        <iframe src="{{ asset('storage/' . $sertifikat->file_path) }}" width="100%" height="600px"></iframe>
                    @elseif($sertifikat->file_type === 'docx')
                        <p>File ini adalah dokumen Word (.docx). Silakan download untuk melihat.</p>
                        <a href="{{ asset('storage/' . $sertifikat->file_path) }}" class="btn btn-primary" download>Download Sertifikat</a>
                    @else
                        <p>Preview tidak tersedia untuk format ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
