@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div class="header-title">
            <h1><i class="fas fa-cocktail"></i> Quản Lý Dịch Vụ</h1>
        </div>
    </div>
    
    <div class="content-wrapper">
        <!-- Left Section - Service List -->
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

        <!-- Right Section - Service Form -->
        <div class="service-form-section">
            <div class="section-header">
                <h2><i class="fas fa-edit"></i> Thông Tin Dịch Vụ</h2>
            </div>
            
            <form id="serviceForm" class="custom-form">
                @csrf
                <div class="form-group">
                    <label for="serviceName">
                        <i class="fas fa-tag"></i> Tên dịch vụ
                    </label>
                    <input type="text" class="form-control" id="serviceName" name="name" required>
                </div>

                <div class="form-group">
                    <label for="servicePrice">
                        <i class="fas fa-dollar-sign"></i> Giá dịch vụ
                    </label>
                    <input type="number" class="form-control" id="servicePrice" name="price" required>
                </div>

                <div class="form-group">
                    <label for="serviceCategory">
                        <i class="fas fa-folder"></i> Danh mục
                    </label>
                    <select class="form-control" id="serviceCategory" name="category">
                        <option value="drink">Đồ uống</option>
                        <option value="food">Đồ ăn</option>
                        <option value="other">Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="serviceDescription">
                        <i class="fas fa-align-left"></i> Mô tả
                    </label>
                    <textarea class="form-control" id="serviceDescription" name="description" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="serviceImage">
                        <i class="fas fa-image"></i> Hình ảnh
                    </label>
               
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-primary" id="addServiceBtn">
                        <i class="fas fa-plus"></i> Thêm mới
                    </button>
                    <button type="button" class="btn btn-success" id="editServiceBtn" disabled>
                        <i class="fas fa-save"></i> Cập nhật
                    </button>
                    <button type="button" class="btn btn-danger" id="deleteServiceBtn" disabled>
                        <i class="fas fa-trash"></i> Xóa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.content-wrapper {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 24px;
    padding: 20px;
    height: calc(100vh - 180px);
}

.service-list-section,
.service-form-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.section-header {
    padding: 20px;
    background: linear-gradient(to right, #2193b0, #6dd5ed);
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h2 {
    font-size: 1.25rem;
    margin: 0;
}

.view-controls {
    display: flex;
    gap: 8px;
}

.view-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.view-btn.active,
.view-btn:hover {
    background: rgba(255, 255, 255, 0.4);
}

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 20px;
    padding: 20px;
    overflow-y: auto;
    height: calc(100% - 70px);
}

.service-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
}

.service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.service-image {
    width: 100%;
    height: 160px;
    object-fit: cover;
}

.service-info {
    padding: 15px;
}

.service-name {
    font-weight: 600;
    color: #2d3436;
    margin-bottom: 8px;
}

.service-price {
    color: #00b894;
    font-weight: 600;
    font-size: 1.1rem;
}

.custom-form {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #2d3436;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #dfe6e9;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #2193b0;
    box-shadow: 0 0 0 2px rgba(33, 147, 176, 0.2);
}

.image-upload-wrapper {
    position: relative;
}

.image-preview {
    margin-top: 10px;
    width: 100%;
    height: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-primary {
    background: #2193b0;
}

.btn-success {
    background: #00b894;
}

.btn-danger {
    background: #d63031;
}
</style>
@endsection

@push('scripts')
    <script>
        var servicesIndexRoute = "{{ route('services.data') }}";
        var servicesStoreRoute = "{{ route('services.store') }}";
        var servicesShowRoute = "{{ route('services.show', ':id') }}";
        var servicesUpdateRoute = "{{ route('services.update', ':id') }}";
        var servicesDestroyRoute = "{{ route('services.destroy', ':id') }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/services.js') }}"></script>
@endpush