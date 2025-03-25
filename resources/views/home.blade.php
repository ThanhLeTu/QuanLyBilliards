@extends('layouts.app')

@section('title', 'Trang chủ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý Bàn</li>
@endsection
@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row">
        <div class="col-lg-8">
            <!-- Thông tin bàn -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                    <strong>Bàn 05 - Pool</strong>
                    <span>Thời gian bắt đầu: <strong>14:30</strong></span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p>Thời gian đã chơi: <span class="text-primary fw-bold">2h15p</span></p>
                            <p>Tổng tiền hiện tại: <span class="text-success fw-bold">350,000 đ</span></p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p>Giá/giờ: <span class="text-primary fw-bold">150,000 đ</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs menu -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#listtables">Danh sách bàn</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#services">Dịch vụ</a>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Tab Danh sách bàn -->
                <div class="tab-pane fade show active" id="listtables">
                    <div class="row row-cols-2 row-cols-md-3 g-3">
                        @foreach($tables as $table)
                        <div class="col">
                            <div class="card h-100 table-card {{ $table['status_class'] }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $table['name'] }}</h5>
                                    <p class="card-text">
                                        <span class="badge {{ $table['status_badge'] }}">
                                            {{ $table['status_text'] }}
                                        </span>
                                    </p>
                                    <p class="card-text">
                                        <small>Loại: {{ $table['type'] }}</small><br>
                                        <small>Khu vực: {{ $table['area'] }}</small><br>
                                        <small>Giá: {{ number_format($table['price']) }} đ</small>
                                    </p>
                                    @if($table['is_available'])
                                    <button class="btn btn-primary btn-sm start-table" 
                                            data-table-id="{{ $table['id'] }}"
                                            data-table-name="{{ $table['name'] }}">
                                        Bắt đầu
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Dịch vụ -->
                <div class="tab-pane fade" id="services">
                    <div class="row row-cols-2 row-cols-md-4 g-3">
                        @foreach($services as $service)
                        <div class="col">
                            <div class="card h-100 service-card">
                                @if($service->image)
                                <img src="{{ asset('storage/services/' . $service->image) }}" 
                                     class="card-img-top" 
                                     alt="{{ $service->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $service->name }}</h5>
                                    <p class="card-text">
                                        <strong>{{ number_format($service->price) }} đ</strong>
                                    </p>
                                    <button class="btn btn-primary btn-sm add-service" 
                                            data-id="{{ $service->id }}"
                                            data-name="{{ $service->name }}"
                                            data-price="{{ $service->price }}">
                                        Thêm vào giỏ
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Thông tin bàn -->
            <div class="card mb-3 shadow-sm">
                <div class="card-header bg-secondary text-white">Thông tin khách hàng</div>
                <div class="card-body">
ư
                </div>
            </div>

            <!-- Giỏ hàng -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Giỏ hàng</div>
                <div class="card-body">
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <button class="btn btn-secondary">In hóa đơn</button>
                    <button class="btn btn-success">Thanh toán</button>
                    <button class="btn btn-danger">Kết thúc</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    let currentBookingId = {{ $booking->id ?? 'null' }};
    const routes = {
        bookingStore: "{{ route('bookings.store') }}",
        addService: "{{ route('bookings.add-service') }}",
        checkout: "{{ route('bookings.checkout', ':id') }}",
        cart: "{{ route('bookings.cart', ':id') }}"
    };
</script>   
<script src="{{ asset('assets/js/booking.js') }}"></script>
@endpush