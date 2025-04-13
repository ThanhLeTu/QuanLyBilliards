@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Hóa Đơn Bàn: {{ $invoice->table_name }}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Khách hàng:</strong> {{ $invoice->customer_name }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $invoice->customer_phone }}</p>
                    <p><strong>Ghi chú:</strong> {{ $invoice->customer_note ?? 'Không có' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Bắt đầu:</strong> {{ $invoice->start_time }}</p>
                    <p><strong>Kết thúc:</strong> {{ $invoice->end_time }}</p>
                    <p><strong>Thời lượng:</strong> {{ $invoice->play_time_minutes }} phút</p>
                    <p><strong>Đơn giá bàn:</strong> {{ number_format($invoice->table_price) }} đ/giờ</p>
                </div>
            </div>

            <h4 class="mt-4 mb-3">Dịch vụ:</h4>
            @if ($invoice->services->count())
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Tên</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($invoice->usedServices as $service)
                        <tr>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->quantity }}</td>
                            <td>{{ number_format($service->price) }} đ</td>
                            <td>{{ number_format($service->quantity * $service->price) }} đ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted">Không có dịch vụ nào.</p>
            @endif

            <div class="mt-4 border-top pt-3">
                <div class="row">
                    <div class="col-md-6 offset-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tiền chơi:</span>
                            <strong>{{ number_format($invoice->play_cost) }} đ</strong>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top mt-2">
                            <h5>Tổng cộng:</h5>
                            <h5 class="text-danger">{{ number_format($invoice->total_payment) }} đ</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ route('home') }}" class="btn btn-primary">
            <i class="fas fa-home me-1"></i> Quay về trang chính
        </a>
        <button onclick="window.print()" class="btn btn-secondary ms-2">
            <i class="fas fa-print me-1"></i> In hóa đơn
        </button>
    </div>
</div>
@endsection