@extends('layouts.main')

@section('title', 'Daftar Dokumen')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Dokumen</h1>
        <a href="{{ route('superadmin.documents.create') }}" class="btn btn-primary"> + Tambah Dokumen</a>
    </div>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Form Pencarian & Filter -->
    <form action="{{ route('superadmin.documents.index') }}" method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Cari judul..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label for="date" class="form-label">Tanggal Upload</label>
                <input type="date" id="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100 h-100">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </div>
    </form>

    <!-- Tabel Daftar Dokumen -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Jenis File</th>
                <th>Diunggah Oleh</th>
                <th>Tanggal Upload</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documents as $document)
                <tr>
                    <td><input type="checkbox" class="document-checkbox" value="{{ $document->id }}"></td>
                    <td>{{ Str::limit($document->title, 30, '...') }}</td>
                    <td>{{ $document->category }}</td>
                    <td>{{ strtoupper($document->file_type) }}</td>
                    <td>{{ $document->user->name }}</td>
                    <td>{{ $document->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('superadmin.documents.show', $document->id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('superadmin.documents.edit', $document->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('superadmin.documents.destroy', $document->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

     <!-- Tombol Aksi Massal -->
     <div class="mt-3" id="bulk-actions" style="display: none;">
        <button id="bulk-download" class="btn btn-success">Unduh Terpilih</button>
        <button id="bulk-delete" class="btn btn-danger">Hapus Terpilih</button>
        
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $documents->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('search-form').addEventListener('submit', function() {
        var searchButton = document.getElementById('search-button');
        var searchText = document.getElementById('search-text');
        
        // Nonaktifkan tombol dan ubah teksnya
        searchButton.disabled = true;
        searchText.textContent = 'Mencari...';
    });
    $(document).ready(function() {
        function toggleBulkActions() {
            if ($('.document-checkbox:checked').length > 0) {
                $('#bulk-actions').fadeIn();
            } else {
                $('#bulk-actions').fadeOut();
            }
        }

        $('#select-all').on('change', function() {
            $('.document-checkbox').prop('checked', $(this).prop('checked')).trigger('change');
        });

        $('.document-checkbox').on('change', function() {
            $('#select-all').prop('checked', $('.document-checkbox:checked').length === $('.document-checkbox').length);
            toggleBulkActions();
        });

        toggleBulkActions();
    });

        $('#bulk-download').on('click', function() {
        let selectedDocs = $('.document-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedDocs.length === 0) {
            alert('Pilih minimal satu dokumen.');
            return;
        }

        $.ajax({
            url: "{{ route('superadmin.documents.bulkDownload') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                document_ids: selectedDocs
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.zip_url;
                } else {
                    alert(response.error);
                }
            },
            error: function(response) {
                alert('Terjadi kesalahan saat mengunduh.');
            }
        });
    });

        $('#bulk-delete').on('click', function() {
        let selectedDocs = $('.document-checkbox:checked').map(function() { return $(this).val(); }).get();
        if (selectedDocs.length === 0) return alert('Pilih minimal satu dokumen.');
        if (!confirm('Apakah Anda yakin ingin menghapus dokumen terpilih?')) return;

        $.ajax({
            url: "{{ route('superadmin.documents.bulkDelete') }}",
            method: "POST",
            data: { _token: "{{ csrf_token() }}", document_ids: selectedDocs },
            success: function(response) { alert(response.success); location.reload(); },
            error: function(response) { alert(response.responseJSON.error); }
        });
    });
</script>
@endsection