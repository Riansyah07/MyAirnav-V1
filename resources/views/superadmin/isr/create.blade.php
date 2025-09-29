@extends('layouts.main')

@section('content')
<div class="section-header">
    <h1>Tambah Dokumen</h1>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Form Tambah Dokumen ISR</h4>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ups!</strong> Ada kesalahan pada input Anda.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('superadmin.isr.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Dokumen</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="note" class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-control" id="note" name="note"></textarea>
                </div>
                <div class="form-group">
                    <label for="file">Upload Dokumen (PDF/docx)</label>
                    <input type="file" name="file" class="form-control-file" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('superadmin.isr.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
