@extends('layouts.app')

@section('title', 'Hóa đơn thanh toán')

@section('content')
<style>
    .receipt-container {
        max-width: 500px;
        margin: 50px auto;
        background: #f9f9f9;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .receipt-header {
        text-align: center;
        margin-bottom: 20px;
    }
    .receipt-header h1 {
        color: #28a745;
        font-size: 28px;
        margin-bottom: 10px;
    }
    .receipt-detail {
        font-size: 16px;
        line-height: 1.8;
    }
    .receipt-detail strong {
        width: 150px;
        display: inline-block;
    }
    .receipt-footer {
        text-align: center;
        margin-top: 30px;
    }
</style>

<div class="receipt-container">
    <div class="receipt-header">
        <h1>✅ Giao dịch thành công!</h1>
        <p>Cảm ơn bạn đã sử dụng dịch vụ tại Billiards</p>
    </div>

    <div class="receipt-detail">
        <p><strong>Tên khách hàng:</strong> {{ session('customer_name', 'Không rõ') }}</p>
        <p><strong>Số điện thoại:</strong> {{ session('customer_phone', 'Không rõ') }}</p>
        <p><strong>Số bàn:</strong> {{ session('reservation_id') ? 'Bàn #' . session('reservation_id') : 'Không rõ' }}</p>
        <p><strong>Mã giao dịch:</strong> {{ $data['orderId'] ?? 'Không có' }}</p>
        <p><strong>Số tiền:</strong> {{ number_format($data['amount'] ?? 0, 0, ',', '.') }} đ</p>
        <p><strong>Trạng thái:</strong> <span class="text-success fw-bold">Thành công</span></p>
    </div>

    <div class="receipt-footer">
        <a href="{{ url('/') }}" class="btn btn-primary mt-3">Quay về trang chủ</a>
    </div>
</div>
@endsection
