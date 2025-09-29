@extends('layouts.main')

@section('title', 'Upload Dokumen')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h1>Upload Dokumen LOCA</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('superadmin.loca.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Judul Dokumen -->
        <div class="mb-3">
            <label for="name" class="form-label">Judul Dokumen</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Kategori Dokumen -->
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required onchange="toggleSOPFields()">
                <option value="pilih">Pilih Kategori</option>
                <option value="Internal">Internal</option>
                <option value="Eksternal">Eksternal</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Upload File -->
        <div class="mb-3">
            <label for="file" class="form-label">Upload Dokumen (PDF/DOCX)</label>
            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" accept=".pdf,.docx" required>
            <small class="text-muted">Maksimal ukuran file: 5MB</small>
            @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Catatan (Opsional) -->
        <div class="mb-3">
            <label for="note" class="form-label">Catatan (Opsional)</label>
            <textarea class="form-control" id="note" name="note"></textarea>
        </div>

        <a href="{{ route('superadmin.loca.index') }}" class="btn btn-warning">Kembali</a>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<script>
    function toggleSOPFields() {
        var category = document.getElementById("category").value;
        var sopFields = document.getElementById("sop_fields");

        if (category === "BAB 7") {
            sopFields.style.display = "block";
        } else {
            sopFields.style.display = "none";
            document.getElementById("sop_type").value = "";
            document.getElementById("region").value = "";
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("file").addEventListener("change", function () {
            var file = this.files[0]; // Ambil file yang dipilih
            var maxSize = 5 * 1024 * 1024; // 5MB dalam byte

            if (file && file.size > maxSize) {
                alert("Ukuran file terlalu besar! Maksimal 5MB.");
                this.value = ""; // Reset input file
            }
        });

        // Validasi sebelum submit form
        document.querySelector("form").addEventListener("submit", function (event) {
            var fileInput = document.getElementById("file");
            if (!fileInput.files.length) {
                alert("Silakan pilih file untuk diunggah.");
                event.preventDefault(); // Batalkan pengiriman form
            }
        });
    });
</script>

@endsection
