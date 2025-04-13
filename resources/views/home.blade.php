@extends('layouts.app')

@section('title', 'Trang chủ')

@section('pagetitle', 'Trang chủ')

@section('breadcrumb')
    <li class="breadcrumb-item active">Trang chủ</li>
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
    
    <!-- Tables Grid -->
    <div class="tables-container">
        <h3>
          Trạng thái bàn
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReservationModal">
                <i class="fas fa-calendar-plus"></i> Đặt Bàn
            </button>
        </h3>
        
        <div class="tables-grid" id="tablesGrid">
            <!-- Thông tin danh sách bàn được hiển thị ở đây -->
        </div>
    </div>
    
    <!-- Add Reservation Modal -->
    <div class="modal fade custom-modal" id="addReservationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addReservationModalLabel">Đặt Bàn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addReservationForm" action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="table_id" class="form-label">Chọn Bàn</label>
                            <select class="form-select" id="table_id" name="table_id" required>
                                @foreach($availableTables as $table)
                                <option value="{{ $table->id }}">Bàn số {{ $table->table_number }} - {{ $table->area }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Tên Khách Hàng</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name">
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Số Điện Thoại</label>
                            <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                        </div>
                        <div class="mb-3">
                            <label for="customer_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email">
                        </div>

                        <div class="mb-3">
                            <label for="start_time" class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="start_time" name="start_time" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_time" class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" id="end_time" name="end_time">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="playing">Đang chơi</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Đặt Bàn</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="billingModal" tabindex="-1" aria-labelledby="billingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen m-0">
        <div class="modal-content">
            <!-- Header with Table Info -->
        <div class="table-info text-white">
        <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8" style="color: black;">
                <h1 class="table-title">Bàn số: <span id="billingTableNumber"></span></h1>
                <div class="d-flex align-items-center">
                    <span class="me-3"><i class="far fa-clock"></i> Bắt đầu: <span id="billingStartTime"></span></span>
                    <span class="me-3"><i class="far fa-clock"></i> Kết thúc: <span id="billingEndTime">Đang chơi</span></span>
                    <span><i class="fas fa-tag"></i> Giá/giờ: <span id="hourlyRate"></span> đ</span>
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="timer-container">
                    <div>Thời gian chơi: <strong id="billingDuration"></strong></div>
                    <div class="timer-display text-red fw-bold" id="billingTotal">0 đ</div>
                </div>
            </div>
        </div>
        </div>
        </div>
            <!-- Main Content -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row g-4">
                        <!-- Left Column - Customer Info -->
                        <div class="col-md-4">
                            <!-- Customer Info Card -->
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h5>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Sửa
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tên khách hàng</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                            <input type="text" class="form-control" name="customer_name" placeholder="Nhập tên khách hàng">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input type="tel" class="form-control" name="customer_phone" placeholder="0xxx xxx xxx">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Ghi chú</label>
                                        <textarea class="form-control" name="customer_note" rows="2" placeholder="Thêm ghi chú cho khách hàng..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Cart Preview -->
                            <div class="card mt-4">
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng</h5>
                                    <span class="badge bg-light text-dark" id="cartItemCount">... sản phẩm</span>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush" id="cartItems">
                                        <li class="list-group-item cart-item d-flex justify-content-between align-items-center">
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="text-muted">Tổng thanh toán:</div>
                                            <div class="fs-5 fw-bold text-danger" id="totalPayment">... đ</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Products -->
                        <div class="col-md-8" style="background-color: #0a58ca3d;color: black;">
                            <!-- Category Navigation -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <ul class="nav nav-tabs card-header-tabs category-nav">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#"><i class="fas fa-beer me-1"></i>Đồ uống</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#"><i class="fas fa-utensils me-1"></i>Đồ ăn</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#"><i class="fas fa-smoking me-1"></i>Thuốc lá</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#"><i class="fas fa-th me-1"></i>Khác</a>
                                        </li>
                                    </ul>
                                </div>
                                
                                <!-- Product Search & View Controls -->
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="input-group" style="max-width: 300px;">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                                        </div>
                                        <div class="btn-group">
                                        </div>
                                    </div>

                                    <!-- Product Grid -->
                                    <div class="service-grid" id="serviceGrid">
                                        <!-- Product Items -->
                                        <div class="card product-card h-100">
                                        </div>                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer with Actions -->
            <div class="sticky-bottom">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button class="btn btn-outline-warning me-2">
                                <i class="fas fa-pause me-1"></i> Tạm dừng
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="fas fa-print me-1"></i> In hóa đơn
                            </button>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button class="btn btn-success btn-lg">
                                <i class="fas fa-money-bill-wave me-2"></i> Thanh toán
                                <span class="ms-2 badge bg-light text-dark" id="finalPayment">... đ</span>
                            </button>
                            <form id="momoForm" method="POST" action="{{ route('payment.momo') }}">
                                @csrf
                                <input type="hidden" name="amount" id="momoAmount">
                                <input type="hidden" name="customer_name" id="momoCustomerName">
                                <input type="hidden" name="customer_phone" id="momoCustomerPhone">
                                <input type="hidden" name="reservation_id" id="momoReservationId">

                                <button type="submit" class="btn btn-danger btn-lg mt-2">
                                    <i class="fab fa-cc-apple-pay me-2"></i> Thanh toán qua Momo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ asset('assets/css/services.css') }}">
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endpush

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

        // 👉 Lấy dữ liệu từ form khách hàng
        const customerName = $('input[name="customer_name"]').val();
        const customerPhone = $('input[name="customer_phone"]').val();
        const reservationId = $('#table_id').val(); // hoặc lưu trong JS từ lúc load

        $('#momoCustomerName').val(customerName);
        $('#momoCustomerPhone').val(customerPhone);
        $('#momoReservationId').val(reservationId);
    });
</script>


@endpush