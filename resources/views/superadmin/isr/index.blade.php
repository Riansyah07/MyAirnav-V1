@extends('layouts.main')

@section('title', 'Izin Stasiun Radio')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>ISR (Izin Stasiun Radio)</h4>
        <a href="{{ route('superadmin.isr.create') }}" class="btn btn-primary">+ Tambah Dokumen</a>
    </div>

    {{-- Filter dan Pencarian --}}
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-primary w-100 h-100">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>

    {{-- Tabel Dokumen --}}
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
                            <a href="{{ route('superadmin.isr.show', $item->id) }}" class="btn btn-sm btn-info">Lihat</a>
                            <a href="{{ route('superadmin.isr.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>

                            {{-- Form Hapus (jangan nested dalam form lain) --}}
                            <form action="{{ route('superadmin.isr.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada dokumen ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination (jika ada) --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $isrs->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
