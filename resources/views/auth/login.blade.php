@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</h4>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Lỗi:</strong> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" required class="form-control" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu:</label>
                    <input type="password" id="password" name="password" required class="form-control">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                    </button>
                </div>

                <div class="mt-3 text-center">
                    <a href="{{ route('register') }}">Chưa có tài khoản? <strong>Đăng ký</strong></a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
