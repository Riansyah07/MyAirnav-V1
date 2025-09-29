@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
    {{-- Baris untuk Stat Cards --}}
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success"><i class="far fa-file-alt"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Total Dokumen</h4></div>
                    <div class="card-body">{{ $totalDocuments }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info"><i class="far fa-id-card"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Total Sertifikat</h4></div>
                    <div class="card-body">{{ $totalCertificates }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning"><i class="far fa-file-alt"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Dokumen Loca</h4></div>
                    <div class="card-body">{{ $totalLoca }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-info"><i class="far fa-file-alt"></i></div>
                <div class="card-wrap">
                    <div class="card-header"><h4>Dokumen ISR</h4></div>
                    <div class="card-body">{{ $totalIsr }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Baris untuk Panel Aktivitas dan Dokumen Terbaru --}}
    <div class="row">
        {{-- Panel Dokumen Terbaru (Kolom Kiri) --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-history me-2"></i> Daftar Dokumen yang Baru Diperbarui</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Judul Dokumen</th>
                                    <th>Oleh</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentDocuments as $doc)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.documents.show', $doc->id) }}">{{ Str::limit($doc->title, 35) }}</a>
                                        </td>
                                        <td>{{ $doc->user->name ?? 'N/A' }}</td>
                                        <td>
                                            <div class="badge badge-light">{{ $doc->updated_at->diffForHumans() }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada aktivitas dokumen.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel Aktivitas Terbaru (Kolom Kanan) --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-bolt me-2"></i> Aktivitas Terbaru</h4>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled list-unstyled-border">
                        @forelse ($recentDocuments as $doc)
                            <li class="media">
                                <div class="media-body">
                                    <div class="float-right text-primary">{{ $doc->updated_at->diffForHumans() }}</div>
                                    <div class="media-title">{{ $doc->user->name ?? 'User' }}</div>
                                    <span class="text-muted">
                                        @if($doc->created_at == $doc->updated_at)
                                            mengupload dokumen baru:
                                        @else
                                            memperbarui dokumen:
                                        @endif
                                        "{{ Str::limit($doc->title, 25) }}"
                                    </span>
                                </div>
                            </li>
                        @empty
                            <li class="media">
                                <div class="media-body text-center">
                                    Tidak ada aktivitas terbaru.
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection