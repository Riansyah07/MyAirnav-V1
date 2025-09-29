@extends('layouts.main')

@section('title', 'Edit Dokumen ISR')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h4 class="mb-4">Edit Dokumen</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.isr.update', $isr->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Judul --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nama Dokumen</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $isr->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

         <!-- Catatan -->
         <div class="mb-3">
            <label for="note" class="form-label">Catatan</label>
            <textarea class="form-control" id="note" name="note">{{ old('note', $isr->note) }}</textarea>
        </div>

        {{-- File Lama --}}
        <div class="mb-3">
            <label class="form-label">File Saat Ini</label>
            <div>
                <a href="{{ asset('storage/' . $isr->file_path) }}" target="_blank">{{ basename($isr->file_path) }}</a>
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
            <a href="{{ route('admin.isr.index') }}" class="btn btn-warning">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
