@extends('layouts.app')

@section('title', 'Quản lý Bàn Billiards')

@section('pagetitle', 'Quản lý Bàn')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý Bàn</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-title">
            <h1><i class="fas fa-billiard"></i> Quản lý Bàn Billiards</h1>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary add-table-btn" data-bs-toggle="modal" data-bs-target="#addTableModal">
                <i class="fas fa-plus-circle"></i> Thêm bàn mới
            </button>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReservationModal">
                <i class="fas fa-calendar-plus"></i> Đặt Bàn
            </button>

        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="status-filter">
            <button class="filter-btn active" data-status="all">
                <i class="fas fa-border-all"></i> Tất cả
            </button>
            <button class="filter-btn" data-status="reserved">
                <i class="fas fa-"></i> Đã đặt
            </button>
            <button class="filter-btn" data-status="available">
                <i class="fas fa-check-circle"></i> Trống
            </button>
            <button class="filter-btn" data-status="occupied">
                <i class="fas fa-user"></i> Đang sử dụng
            </button>
            
            <button class="filter-btn" data-status="unavailable">
                <i class="fas fa-ban"></i> Không khả dụng
            </button>
        </div>
    </div>

    <!-- Tables Grid -->
    <div class="tables-container">
        <div class="tables-grid" id="tablesGrid">
            <!-- Tables will be loaded here by JavaScript -->
        </div>
    </div>
</div>

<!-- Add Table Modal -->
<div class="modal fade custom-modal" id="addTableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTableModalLabel">Thêm bàn mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addTableForm">
                    @csrf
                    <div class="mb-3">
                        <label for="table_number" class="form-label">Số Bàn</label>
                        <input type="number" class="form-control" id="table_number" name="table_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="area" class="form-label">Khu vực</label>
                        <input type="text" class="form-control" id="area" name="area" required>
                    </div>
                    <div class="mb-3">
                        <label for="table_type" class="form-label">Loại Bàn</label>
                        <input type="text" class="form-control" id="table_type" name="table_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="available">Trống</option>
                            <option value="reserved">Đã đặt</option>
                            <option value="occupied">Đang sử dụng</option>
                            <option value="unavailable">Không khả dụng</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="addTableBtn">Thêm</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Table Modal -->
<div class="modal fade custom-modal" id="editTableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTableModalLabel">Sửa Bàn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTableForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTableId" name="id">
                    <div class="mb-3">
                        <label for="edit_table_number" class="form-label">Số Bàn</label>
                        <input type="number" class="form-control" id="edit_table_number" name="table_number" required>
                    </div>
                     <div class="mb-3">
                        <label for="edit_area" class="form-label">Khu vực</label>
                        <input type="text" class="form-control" id="edit_area" name="area" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_table_type" class="form-label">Loại Bàn</label>
                        <input type="text" class="form-control" id="edit_table_type" name="table_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Giá</label>
                        <input type="number" class="form-control" id="edit_price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="available">Trống</option>
                            <option value="reserved">Đã đặt</option>
                            <option value="occupied">Đang sử dụng</option>
                            <option value="unavailable">Không khả dụng</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="updateTableBtn">Cập nhật</button>
            </div>
        </div>
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
                                @foreach($tables as $table)
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
                                <option value="pending">Chờ xác nhận</option>
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


<!-- CSS for modern styling -->
<style>
.container-fluid {
    padding: 20px;
    background: #f5f7fa;
}

.dashboard-header {
    padding: 25px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.filter-section {
    margin: 20px 0;
    padding: 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.status-filter {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-btn {
    padding: 10px 20px;
    margin: 0 5px;
    border: none;
    border-radius: 25px;
    background: #f8f9fa;
    color: #495057;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.filter-btn.active {
    background: #2a5298;
    color: white;
}

.tables-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    padding: 20px;
}

.table-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: none;
}

.table-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.table-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
}

.table-card.available::before {
    background: #28a745;
}

.table-card.occupied::before {
    background: #dc3545;
}

.table-card.unavailable::before {
    background: #6c757d;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9em;
    font-weight: 500;
}

.status-badge.available {
    background: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.status-badge.occupied {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.status-badge.unavailable {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.table-number {
    font-size: 24px;
    font-weight: bold;
    color: #2a5298;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.table-info {
    margin: 10px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px 0;
    border-bottom: 1px solid #f0f0f0;
}

.table-info span {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #495057;
}

.table-actions {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    justify-content: flex-end;
}

.action-btn {
    padding: 8px 20px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn:hover {
    transform: translateY(-2px);
}

.edit-btn {
    background: #2a5298;
    color: white;
}

.edit-btn:hover {
    background: #1e3c72;
}

.delete-btn {
    background: #dc3545;
    color: white;
}

.delete-btn:hover {
    background: #c82333;
}

.add-table-btn {
    padding: 12px 25px;
    border-radius: 25px;
    background: #28a745;
    border: none;
    color: white;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.add-table-btn:hover {
    background: #218838;
    transform: translateY(-2px);
}

.add-table-btn i {
    font-size: 1.2em;
}

/* Modal Styling */
.custom-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.custom-modal .modal-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border-radius: 20px 20px 0 0;
    padding: 20px 30px;
}

.custom-modal .modal-body {
    padding: 30px;
}

.custom-modal .modal-footer {
    border-top: none;
    padding: 20px 30px;
}

.custom-modal .form-label {
    font-weight: 500;
    color: #495057;
}

.custom-modal .form-control,
.custom-modal .form-select {
    border-radius: 10px;
    padding: 10px 15px;
    border: 1px solid #dee2e6;
}

.custom-modal .form-control:focus,
.custom-modal .form-select:focus {
    border-color: #2a5298;
    box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
}
</style>
@endsection

@push('scripts')
    <script>
        var tablesIndexRoute = "{{ route('tables.data') }}";
        var tablesStoreRoute = "{{ route('tables.store') }}";
        var tablesShowRoute = "{{ route('tables.show', ':id') }}";
        var tablesUpdateRoute = "{{ route('tables.update', ':id') }}";
        var tablesDestroyRoute = "{{ route('tables.destroy', ':id') }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/tables.js') }}"></script>
@endpush
