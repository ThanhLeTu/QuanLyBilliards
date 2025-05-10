@extends('layouts.app')

@section('title', 'Thông tin tài khoản')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thông tin tài khoản</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Thông tin chung --}}
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h5><i class="bi bi-person-circle"></i> Thông tin chung</h5>
        </div>
        <div class="card-body">
            <p><strong>Họ tên:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Ngày đăng ký:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- Cập nhật thông tin --}}
    <div class="card mb-4 shadow">
        <div class="card-header bg-success text-white">
            <h5><i class="bi bi-pencil-square"></i> Cập nhật thông tin</h5>
        </div>
        <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="name" class="form-label">Họ tên</label>
        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Cập nhật</button>
</form>

        </div>
    </div>

    {{-- Đổi mật khẩu --}}
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h5><i class="bi bi-shield-lock"></i> Đổi mật khẩu</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Mật khẩu hiện tại</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">Đổi mật khẩu</button>
            </form>
        </div>
    </div>
</div>
@endsection
