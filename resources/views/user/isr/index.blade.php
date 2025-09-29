@extends('layouts.main')

@section('title', 'Izin Stasiun Radio')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>ISR (Izin Stasiun Radio)</h4>
    </div>

    {{-- Filter dan Pencarian --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-2">
            <select id="sort" name="sort" class="form-control">
                <option value="">-- Urutkan --</option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>A-Z</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Z-A</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100 h-100">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>

    {{-- Tabel Sertifikat --}}
    <form id="bulk-certificate-form">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama Dokumen</th>
                        <th>Tipe Dokumen</th>
                        <th>Tanggal Upload</th>
                        <th>Diunggah Oleh</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($isrs as $item)
                        <tr id="row-{{ $item->id }}">
                            <td>{{ $item->name }}</td>
                            <td>{{ strtoupper($item->file_type) }}</td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>{{ $item->user ? $item->user->name : 'Unknown' }}</td>
                            <td>
                                <a href="{{ route('user.isr.show', $item->id) }}" class="btn btn-sm btn-info">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada dokumen ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>
</div>
@endsection
