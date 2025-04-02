
@extends('layouts.app')

@section('title', 'Trang chủ')

@section('pagetitle', 'Trang chủ')

@section('breadcrumb')
    <li class="breadcrumb-item active">Trang chủ</li>
@endsection

@section('content')
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
            <!-- Tables will be loaded here by JavaScript -->
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
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white d-flex justify-content-between">
                <div>
                    <h5 class="modal-title">Bàn 05 - Pool</h5>
                    <small>Bắt đầu: 14:30 | Giá/giờ: 150,000 đ</small>
                </div>
                <div>
                    <span class="mr-3">Tổng giờ chơi: <strong>2h15p</strong></span>
                    <span>02:15:37</span>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Customer Info -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <span>Thông tin khách hàng</span>
                                <button class="btn btn-sm btn-outline-secondary">Sửa</button>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Tên khách hàng</label>
                                    <input type="text" class="form-control" name="customer_name" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="customer_phone" value="">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Ghi chú</label>
                                    <textarea class="form-control" name="customer_note" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Billing History -->
                        <div class="card mt-3">
                            <div class="card-header">Lịch sử gần đây</div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span>03/03 - Giờ chơi</span>
                                    <span class="text-success">520,000 đ</span>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <span>20/02 - Giờ chơi</span>
                                    <span class="text-success">480,000 đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Product Selection -->
                    <div class="col-md-8">
                    <div class="service-list-section">
            <div class="section-header">
                <h2><i class="fas fa-list"></i> Danh Sách Dịch Vụ</h2>
                <div class="view-controls">
                    <button class="view-btn active" data-view="grid">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
            
            <div class="service-grid" id="serviceGrid">
                <!-- Services will be loaded here -->
            </div>
        </div>

                    </div>
                </div>
                <!-- Cart -->
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">Giỏ hàng</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span>Bia Tiger</span>
                            <span>2 x 25,000 đ</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <strong>Tổng:</strong>
                        <span class="text-danger">50,000 đ</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-primary">Thanh toán</button>
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

@endpush