@extends('layouts.main')

@section('content')
<div class="section-header">
    <h1>Tambah Sertifikat</h1>
</div>

<div class="section-body">
    <div class="card">
        <div class="card-header">
            <h4>Form Tambah Sertifikat</h4>
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

            <form action="{{ route('admin.sertifikat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Judul Sertifikat</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                </div>


                <div class="form-group">
                    <label for="file">Upload Sertifikat (PDF/JPG/PNG)</label>
                    <input type="file" name="file" class="form-control-file" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.sertifikat.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
