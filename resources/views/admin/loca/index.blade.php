@extends('layouts.main')

@section('title', 'Daftar Loca')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>LOCA (Letter of Operational Coordination Agreement)</h2>
        <a href="{{ route('admin.loca.create') }}" class="btn btn-primary"> + Tambah Dokumen Loca</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Pencarian & Filter -->
    <form action="{{ route('admin.loca.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Cari nama..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Tanggal Dibuat</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100 h-100"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </form>

    <!-- Tabel Loca -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Deskripsi</th>
                <th>Diunggah Oleh</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($locas as $loca)
                <tr>
                    <td><input type="checkbox" class="loca-checkbox" value="{{ $loca->id }}"></td>
                    <td>{{ $loca->name }}</td>
                    <td>{{ $loca->category }}</td>
                    <td>{{ Str::limit($loca->note, 50) }}</td>
                    <td>{{ $loca->user->name }}</td>
                    <td>{{ $loca->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.loca.show', $loca->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('admin.loca.edit', $loca->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.loca.destroy', $loca->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data loca.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Aksi Massal -->
    <div class="mt-3" id="bulk-actions" style="display: none;">
        <button id="bulk-delete" class="btn btn-danger">Hapus Terpilih</button>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $locas->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

