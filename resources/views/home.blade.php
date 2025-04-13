@extends('layouts.app')

@section('title', 'Trang chủ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý Bàn</li>
@endsection

@section('content')
<style>
    .timer-display {
        font-size: 1.2rem;
        font-weight: bold;
    }
    .product-card {
        cursor: pointer;
        transition: transform 0.2s;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .category-nav {
        overflow-x: auto;
        white-space: nowrap;
        padding: 10px 0;
    }
    .table-title {
        font-size: 1.5rem;
        margin-bottom: 5px;
    }
    .timer-container {
        background-color: #0a58ca;
        border-radius: 6px;
        padding: 8px 15px;
        color: white;
    }
    .service-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    .cart-item {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .cart-total {
        font-size: 1.2rem;
        padding: 15px;
        background-color: #f8f9fa;
    }
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
    }
    .sticky-bottom {
        position: sticky;
        bottom: 0;
        background: white;
        border-top: 1px solid #dee2e6;
        padding: 15px;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    }
    .quantity-control {
        display: flex;
        align-items: center;
    }
    .quantity-control button {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quantity-control input {
        width: 50px;
        text-align: center;
    }
</style>

<div class="container-fluid px-4 py-3">
    <h1>Chào mừng đến với trang chủ!</h1>
    <p>Đây là trang chủ của ứng dụng quản lý Billiards.</p>

    <div class="dashboard-stats">
        <div class="stat-card" id="table-stats">
            <h3>Bàn đang hoạt động</h3>
            <div class="value"><span id="active-tables">{{ $activeTables }}</span>/<span id="total-tables">{{ $totalTables }}</span></div>
            <div class="info">Tỷ lệ sử dụng: <span id="usage-rate">{{ number_format($usageRate, 2) }}</span>%</div>
        </div>
        <div class="stat-card income">
            <h3>Doanh thu hôm nay</h3>
            <div class="value">4.850.000 đ</div>
            <div class="info up"><i class="fas fa-arrow-up"></i> 12% so với hôm qua</div>
        </div>
        <div class="stat-card">
            <h3>Số giờ chơi</h3>
            <div class="value">42.5</div>
            <div class="info"><i class="fas fa-clock"></i> Giờ chơi hôm nay</div>
        </div>
        <div class="stat-card">
            <h3>Khách hàng</h3>
            <div class="value">32</div>
            <div class="info up"><i class="fas fa-arrow-up"></i> 8% so với hôm qua</div>
        </div>
    </div>

    <!-- Giao diện bảng và dịch vụ -->
    @include('partials.tables_and_services')
</div>

@include('partials.billing_modal')

<link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
@endsection

@push('scripts')
<script>
    var isHomePage = true;
    var tablesIndexRoute = "{{ route('tables.data') }}";
    var tablesStoreRoute = "{{ route('tables.store') }}";
    var tablesShowRoute = "{{ route('tables.show', ':id') }}";
    var tablesUpdateRoute = "{{ route('tables.update', ':id') }}";
    var tablesDestroyRoute = "{{ route('tables.destroy', ':id') }}";
    const servicesDataRoute = "{{ route('services.data') }}";
    const servicesStoreRoute = "{{ route('services.store') }}";
    const servicesShowRoute = "{{ route('services.show', ':id') }}";
    const servicesUpdateRoute = "{{ route('services.update', ':id') }}";
    const servicesDestroyRoute = "{{ route('services.destroy', ':id') }}";
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('assets/js/tables.js') }}"></script>
<script src="{{ asset('assets/js/services.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script>
    $('#momoForm').on('submit', function () {
        const total = parseInt($('#finalPayment').text().replace(/[^\d]/g, '')) || 0;
        $('#momoAmount').val(total);

        const customerName = $('input[name="customer_name"]').val();
        const customerPhone = $('input[name="customer_phone"]').val();
        const reservationId = $('#table_id').val();

        $('#momoCustomerName').val(customerName);
        $('#momoCustomerPhone').val(customerPhone);
        $('#momoReservationId').val(reservationId);
    });
</script>
@endpush
