@extends('layouts.app')

@section('title', 'Quản lý Bàn')

@section('pagetitle', 'Quản lý Bàn')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý Bàn</li>
@endsection

@section('content')
    <div class="container">
        <div class="header">
            <h1>Quản lý Bàn</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTableModal">
                <i class="fas fa-plus"></i>
                Thêm bàn mới
            </button>
        </div>

        <div class="tables-grid">
            <table class="table table-striped" id="tablesTable">
                <thead>
                    <tr>
                        <th>Số Bàn</th>
                        <th>Khu vực</th>
                        <th>Loại Bàn</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Dữ liệu bàn sẽ được tải ở đây bằng JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Modal Thêm bàn mới (Bootstrap) -->
        <div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
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

        <!-- Modal sửa bàn -->
        <div class="modal fade" id="editTableModal" tabindex="-1" aria-labelledby="editTableModalLabel" aria-hidden="true">
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
    </div>
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
