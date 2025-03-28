@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Đăng ký</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label>Họ và tên</label>
            <input type="text" name="name" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mật khẩu</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng ký</button>
        <a href="{{ route('login') }}">Đã có tài khoản?</a>
    </form>
</div>
@endsection
