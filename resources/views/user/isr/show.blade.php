@extends('layouts.main')

@section('title', 'Detail Dokumen ISR')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h1>Detail Dokumen ISR</h1>

    <!-- Tombol Kembali -->
    <a href="{{ route('user.isr.index') }}" class="btn btn-warning mb-3">← Kembali</a>

    <div class="row">
        <!-- Kolom Kiri: Metadata Dokumen -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ $isr->name}}</h4>
                    <p><strong>Jenis File:</strong> {{ strtoupper($isr->file_type) }}</p>
                    <p><strong>Diunggah Oleh:</strong> {{ $isr->user->name ?? 'Tidak diketahui' }}</p>
                    @if ($isr->created_at)
                        <p><strong>Tanggal & Waktu Upload:</strong> {{ $isr->created_at->setTimezone('Asia/Jakarta')->format('d M Y - H:i:s') }}</p>
                    @else
                        <p><strong>Tanggal & Waktu Upload:</strong> Tidak tersedia</p>
                    @endif
                    @if(!empty($isr->note))
                        <p><strong>Catatan:</strong> {{ $isr->note }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Preview Dokumen -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Preview Dokumen</h5>

                    @if($isr->file_type === 'pdf')
                        <iframe src="{{ asset('storage/' . $isr->file_path) }}" width="100%" height="600px"></iframe>
                    @elseif($isr->file_type === 'docx')
                        <p>Dokumen ini adalah file Word (.docx). Silakan download untuk melihat.</p>
                        <a href="{{ asset('storage/' . $isr->file_path) }}" class="btn btn-primary" download>Download Dokumen</a>
                    @else
                        <p>Preview tidak tersedia untuk format ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
