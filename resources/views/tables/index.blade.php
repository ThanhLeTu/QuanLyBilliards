@extends('layouts.app')

@section('title', 'Quản lý Bàn')

@section('pagetitle', 'Quản lý Bàn')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
    <li class="breadcrumb-item active">Quản lý Bàn</li>
@endsection

@section('content')
<div class="row">
    <!-- Phần thống kê tổng quan -->
    <div class="col-12 mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Tổng số bàn</h5>
                                <h2 class="display-4 mb-0" id="totalTables">0</h2>
                            </div>
                            <i class="bi bi-table fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Bàn trống</h5>
                                <h2 class="display-4 mb-0" id="availableTables">0</h2>
                            </div>
                            <i class="bi bi-check-circle fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-0">Bàn đang sử dụng</h5>
                                <h2 class="display-4 mb-0" id="occupiedTables">0</h2>
                            </div>
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần quản lý bàn chính -->
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-bottom border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thêm bàn mới</h5>
                    <i class="bi bi-plus-circle text-primary"></i>
                </div>
            </div>
            <div class="card-body">
                <form id="addTableForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-4">
                        <label for="table_number" class="form-label fw-bold">Số Bàn</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                            <input type="number" class="form-control shadow-none" id="table_number" name="table_number" required>
                            <div class="invalid-feedback">Vui lòng nhập số bàn.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="capacity" class="form-label fw-bold">Sức chứa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-people"></i></span>
                            <input type="number" class="form-control shadow-none" id="capacity" name="capacity" min="1" max="20" value="4" required>
                            <div class="invalid-feedback">Vui lòng nhập sức chứa từ 1-20 người.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="area" class="form-label fw-bold">Khu vực</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                            <select class="form-select shadow-none" id="area" name="area" required>
                                <option value="">Chọn khu vực</option>
                                <option value="main">Sảnh chính</option>
                                <option value="outdoor">Ngoài trời</option>
                                <option value="vip">Phòng VIP</option>
                                <option value="balcony">Ban công</option>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn khu vực.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold">Trạng thái</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-toggle-on"></i></span>
                            <select class="form-select shadow-none" id="status" name="status" required>
                                <option value="available">Trống</option>
                                <option value="occupied">Đang sử dụng</option>
                                <option value="unavailable">Không khả dụng</option>
                                <option value="reserved">Đã đặt trước</option>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn trạng thái.</div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold">Ghi chú</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-journal-text"></i></span>
                            <textarea class="form-control shadow-none" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Đặt lại
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-plus-circle me-1"></i>Thêm bàn
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách bàn</h5>
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control shadow-none" id="searchTable" placeholder="Tìm kiếm bàn...">
                            <button class="btn btn-outline-primary" type="button" id="refreshTablesBtn">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tablesTable">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Số Bàn</th>
                                <th scope="col">Sức chứa</th>
                                <th scope="col">Khu vực</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dữ liệu bàn sẽ được tải ở đây bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">Hiển thị <span id="displayedTables">0</span>/<span id="totalTablesInList">0</span> bàn</div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0" id="tablesPagination">
                            <!-- Phân trang sẽ được tạo bằng JavaScript -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Hiển thị bàn dạng ô lưới -->
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white border-bottom border-4 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sơ đồ bàn</h5>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-primary active" data-area="all">Tất cả</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-area="main">Sảnh chính</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-area="outdoor">Ngoài trời</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-area="vip">Phòng VIP</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3" id="tablesGrid">
                    <!-- Dữ liệu bàn dạng lưới sẽ được tải ở đây bằng JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa bàn -->
<div class="modal fade" id="editTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Sửa thông tin bàn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTableForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTableId" name="id">
                    <div class="mb-3">
                        <label for="edit_table_number" class="form-label fw-bold">Số Bàn</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-hash"></i></span>
                            <input type="number" class="form-control shadow-none" id="edit_table_number" name="table_number" required>
                            <div class="invalid-feedback">Vui lòng nhập số bàn.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_capacity" class="form-label fw-bold">Sức chứa</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-people"></i></span>
                            <input type="number" class="form-control shadow-none" id="edit_capacity" name="capacity" min="1" max="20" required>
                            <div class="invalid-feedback">Vui lòng nhập sức chứa từ 1-20 người.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_area" class="form-label fw-bold">Khu vực</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                            <select class="form-select shadow-none" id="edit_area" name="area" required>
                                <option value="main">Sảnh chính</option>
                                <option value="outdoor">Ngoài trời</option>
                                <option value="vip">Phòng VIP</option>
                                <option value="balcony">Ban công</option>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn khu vực.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label fw-bold">Trạng thái</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-toggle-on"></i></span>
                            <select class="form-select shadow-none" id="edit_status" name="status" required>
                                <option value="available">Trống</option>
                                <option value="occupied">Đang sử dụng</option>
                                <option value="unavailable">Không khả dụng</option>
                                <option value="reserved">Đã đặt trước</option>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn trạng thái.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label fw-bold">Ghi chú</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-journal-text"></i></span>
                            <textarea class="form-control shadow-none" id="edit_notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-primary" id="updateTableBtn">
                    <i class="bi bi-save me-1"></i>Cập nhật
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết bàn -->
<div class="modal fade" id="viewTableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="bi bi-info-circle me-2"></i>Thông tin chi tiết bàn</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold text-muted mb-3">Thông tin bàn</h6>
                            <p class="mb-2"><span class="fw-bold">Số bàn:</span> <span id="view_table_number"></span></p>
                            <p class="mb-2"><span class="fw-bold">Sức chứa:</span> <span id="view_capacity"></span> người</p>
                            <p class="mb-2"><span class="fw-bold">Khu vực:</span> <span id="view_area"></span></p>
                            <p class="mb-0"><span class="fw-bold">Ghi chú:</span> <span id="view_notes"></span></p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold text-muted mb-3">Trạng thái</h6>
                            <div class="text-center mb-3">
                                <div id="statusIndicator" class="d-inline-block rounded-circle p-3"></div>
                            </div>
                            <p class="mb-2"><span class="fw-bold">Hiện tại:</span> <span id="view_status" class="badge"></span></p>
                            <p class="mb-2"><span class="fw-bold">Cập nhật lúc:</span> <span id="view_updated"></span></p>
                            <p class="mb-0"><span class="fw-bold">Bởi:</span> <span id="view_updated_by"></span></p>
                        </div>
                    </div>
                </div>
                <div class="border rounded p-3 mt-2">
                    <h6 class="fw-bold text-muted mb-3">Lịch sử đặt bàn gần đây</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Khách hàng</th>
                                    <th>Số người</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody id="table_history">
                                <!-- Lịch sử đặt bàn sẽ được tải ở đây -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="editFromViewBtn">
                    <i class="bi bi-pencil me-1"></i>Sửa
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Đóng
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-card {
        border-radius: 10px;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }
    
    .table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .table-number {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .table-capacity {
        position: absolute;
        bottom: 0;
        right: 0;
        background-color: rgba(0,0,0,0.1);
        border-radius: 0 0 9px 0;
        padding: 2px 8px;
        font-size: 0.8rem;
    }
    
    .table-available {
        background-color: #d1e7dd;
        border: 2px solid #198754;
    }
    
    .table-occupied {
        background-color: #f8d7da;
        border: 2px solid #dc3545;
    }
    
    .table-unavailable {
        background-color: #e2e3e5;
        border: 2px solid #6c757d;
    }
    
    .table-reserved {
        background-color: #fff3cd;
        border: 2px solid #ffc107;
    }
    
    .badge-available {
        background-color: #198754;
    }
    
    .badge-occupied {
        background-color: #dc3545;
    }
    
    .badge-unavailable {
        background-color: #6c757d;
    }
    
    .badge-reserved {
        background-color: #ffc107;
        color: #000;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        (function() {
            'use strict';
            
            // Fetch all forms we want to apply validation styles to
            var forms = document.querySelectorAll('.needs-validation');
            
            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Biến để lưu trữ dữ liệu bàn
        let allTables = [];
        let currentPage = 1;
        let itemsPerPage = 10;
        
        // Tải dữ liệu bàn
        function loadTables() {
            $.ajax({
                url: "{{ route('tables.data') }}",
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    $('#tablesTable tbody').html('<tr><td colspan="5" class="text-center py-3"><div class="spinner-border text-primary" role="status"></div></td></tr>');
                    $('#tablesGrid').html('<div class="col-12 text-center py-3"><div class="spinner-border text-primary" role="status"></div></div>');
                },
                success: function(data) {
                    allTables = data;
                    updateTableStats();
                    displayTables();
                    displayTablesGrid();
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi tải dữ liệu bàn:", error);
                    $('#tablesTable tbody').html('<tr><td colspan="5" class="text-center text-danger">Lỗi khi tải dữ liệu. Vui lòng thử lại.</td></tr>');
                    $('#tablesGrid').html('<div class="col-12 text-center text-danger">Lỗi khi tải dữ liệu. Vui lòng thử lại.</div>');
                }
            });
        }
        
        // Cập nhật thống kê
        function updateTableStats() {
            const total = allTables.length;
            const available = allTables.filter(table => table.status === 'available').length;
            const occupied = allTables.filter(table => table.status === 'occupied').length;
            
            $('#totalTables').text(total);
            $('#availableTables').text(available);
            $('#occupiedTables').text(occupied);
            $('#totalTablesInList').text(total);
        }
        
        // Hiển thị danh sách bàn dạng bảng
        function displayTables(page = 1) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const displayedTables = allTables.slice(start, end);
            currentPage = page;
            
            let html = '';
            if (displayedTables.length === 0) {
                html = '<tr><td colspan="5" class="text-center py-3">Không có dữ liệu bàn</td></tr>';
            } else {
                $.each(displayedTables, function(key, table) {
                    const statusClass = getStatusClass(table.status);
                    const statusText = getStatusText(table.status);
                    
                    html += '<tr>';
                    html += '<td><span class="fw-bold">' + table.table_number + '</span></td>';
                    html += '<td>' + (table.capacity || 4) + ' người</td>';
                    html += '<td>' + getAreaText(table.area || 'main') + '</td>';
                    html += '<td><span class="badge rounded-pill ' + statusClass + '">' + statusText + '</span></td>';
                    html += '<td>';
                    html += '<div class="btn-group btn-group-sm" role="group">';
                    html += '<button class="btn btn-outline-info viewTableBtn" data-id="' + table.id + '" title="Xem chi tiết"><i class="bi bi-eye"></i></button>';
                    html += '<button class="btn btn-outline-primary editTableBtn" data-id="' + table.id + '" data-bs-toggle="modal" data-bs-target="#editTableModal" title="Sửa"><i class="bi bi-pencil"></i></button>';
                    html += '<button class="btn btn-outline-danger deleteTableBtn" data-id="' + table.id + '" title="Xóa"><i class="bi bi-trash"></i></button>';
                    html += '</div>';
                    html += '</td>';
                    html += '</tr>';
                });
            }
            
            $('#tablesTable tbody').html(html);
            $('#displayedTables').text(displayedTables.length);
            
            // Cập nhật phân trang
            updatePagination();
        }
        
        // Hiển thị bàn dạng lưới
        function displayTablesGrid(area = 'all') {
            let filteredTables = allTables;
            if (area !== 'all') {
                filteredTables = allTables.filter(table => table.area === area);
            }
            
            let html = '';
            if (filteredTables.length === 0) {
                html = '<div class="col-12 text-center py-3">Không có bàn nào trong khu vực này</div>';
            } else {
                $.each(filteredTables, function(key, table) {
                    const tableClass = 'table-' + table.status;
                    const capacity = table.capacity || 4;
                    
                    html += '<div class="col-6 col-md-4 col-lg-3">';
                    html += '<div class="table-card ' + tableClass + ' p-3 text-center" data-id="' + table.id + '">';
                    html += '<div class="table-number">' + table.table_number + '</div>';
                    html += '<div class="py-4">';
                    html += '<i class="bi bi-people fs-2"></i>';
                    html += '<p class="mb-0 mt-2">' + getStatusText(table.status) + '</p>';
                    html += '</div>';
                    html += '<div class="table-capacity"><i class="bi bi-person-fill"></i> ' + capacity + '</div>';
                    html += '</div>';
                    html += '</div>';
                });
            }
            
            $('#tablesGrid').html(html);
        }
        
        // Cập nhật phân trang
        function updatePagination() {
            const totalPages = Math.ceil(allTables.length / itemsPerPage);
            let html = '';
            
            html += '<li class="page-item ' + (currentPage === 1 ? 'disabled' : '') + '">';
            html += '<a class="page-link" href="#" data-page="' + (currentPage - 1) + '" aria-label="Previous">';
            html += '<span aria-hidden="true">&laquo;</span>';
            html += '</a>';
            html += '</li>';
            
            for (let i = 1; i <= totalPages; i++) {
                html += '<li class="page-item ' + (currentPage === i ? 'active' : '') + '">';
                html += '<a class="page-link" href="#" data-page="' + i + '">' + i + '</a>';
                html += '</li>';
            }
            
            html += '<li class="page-item ' + (currentPage === totalPages ? 'disabled' : '') + '">';
            html += '<a class="page-link" href="#" data-page="' + (currentPage + 1) + '" aria-label="Next">';
            html += '<span aria-hidden="true">&raquo;</span>';
            html += '</a>';
            html += '</li>';
            
            $('#tablesPagination').html(html);
        }
        
        // Lấy màu trạng thái
        function getStatusClass(status) {
            switch(status) {
                case 'available': return 'badge-available';
                case 'occupied': return 'badge-occupied';
                case 'unavailable': return 'badge-unavailable';
                case 'reserved': return 'badge-reserved';
                default: return 'badge-secondary';
            }
        }
        
        // Lấy text trạng thái
        function getStatusText(status) {
            switch(status) {
                case 'available': return 'Trống';
                case 'occupied': return 'Đang sử dụng';
                case 'unavailable': return 'Không khả dụng';
                case 'reserved': return 'Đã đặt trước';
                default: return 'Không xác định';
            }
        }
    });
</script>
@endpush