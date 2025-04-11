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
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="status-filter">
            <button class="filter-btn active" data-status="all">
                <i class="fas fa-border-all"></i> Tất cả
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


    
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endpush

@push('scripts')
    <script>
    var isHomePage = false;
        var tablesIndexRoute = "{{ route('tables.data') }}";
        var tablesStoreRoute = "{{ route('tables.store') }}";
        var tablesShowRoute = "{{ route('tables.show', ':id') }}";
        var tablesUpdateRoute = "{{ route('tables.update', ':id') }}";
        var tablesDestroyRoute = "{{ route('tables.destroy', ':id') }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/tables.js') }}"></script>
@endpush
