@extends('layouts.main')

@section('title', 'Edit Sertifikat')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h4 class="mb-4">Edit Sertifikat</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.sertifikat.update', $sertifikat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div class="mb-3">
            <label for="title" class="form-label">Judul Sertifikat</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $sertifikat->title) }}" required>
            @error('title')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- File Lama --}}
        <div class="mb-3">
            <label class="form-label">File Saat Ini</label>
            <div>
                <a href="{{ asset('storage/' . $sertifikat->file_path) }}" target="_blank">{{ basename($sertifikat->file_path) }}</a>
            </div>
        </div>

        {{-- File Baru (opsional) --}}
        <div class="mb-3">
            <label for="file" class="form-label">Ganti File (Opsional)</label>
            <input type="file" name="file" id="file" class="form-control">
            @error('file')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
