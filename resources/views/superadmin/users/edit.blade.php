@extends('layouts.main')

@section('title', 'Edit User')

@section('content')
<div class="container bg-white p-4 rounded shadow-sm">
    <h1>Edit User</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('superadmin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" value="{{ $user->password }}" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                    👁️
                </button>
            </div>
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
            </select>
        </div>

        <a href="{{route('superadmin.users.index')}}" class="btn btn-warning">Kembali</a>
        <button type="submit" class="btn btn-success">Update User</button>
    </form>
</div>
@push('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById("password");
        input.type = input.type === "password" ? "text" : "password";
    }
</script>
@endpush
@endsection
