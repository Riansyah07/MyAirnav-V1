@extends('layouts.main')

@section('title', 'Edit Dokumen LOCA')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h2 class="mb-4">Edit Dokumen LOCA</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.loca.update', $loca->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Judul Dokumen -->
        <div class="mb-3">
            <label for="name" class="form-label">Judul Dokumen</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $loca->name) }}" required>
        </div>

        <!-- Kategori Dokumen -->
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-control" id="category" name="category" required onchange="toggleSOPFields()">
                <option value="Pengantar" {{ $loca->category == 'Pengantar' ? 'selected' : '' }}>Pengantar</option>
                <option value="Internal" {{ $loca->category == 'Internal' ? 'selected' : '' }}>Internal</option>
                <option value="Eksternal" {{ $loca->category == 'Eksternal' ? 'selected' : '' }}>Eksternal</option>
            </select>
        </div>

        <!-- Catatan -->
        <div class="mb-3">
            <label for="note" class="form-label">Catatan</label>
            <textarea class="form-control" id="note" name="note">{{ old('note', $loca->note) }}</textarea>
        </div>

        <!-- File Saat Ini -->
        <div class="mb-3">
            <label class="form-label">File Saat Ini</label>
            <p><a href="{{ Storage::url($loca->file_path) }}" target="_blank">{{ basename($loca->file_path) }}</a></p>
        </div>

        <!-- Upload File Baru -->
        <div class="mb-3">
            <label for="file" class="form-label">Unggah File Baru (Opsional)</label>
            <input type="file" class="form-control" id="file" name="file">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.loca.index') }}" class="btn btn-warning">Batal</a>
    </form>
</div>
@endsection
