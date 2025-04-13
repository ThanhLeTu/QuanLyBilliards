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
                    <div class="image-upload-wrapper">
                        <input type="file" id="serviceImage" name="image" class="form-control">
                        <div class="image-preview"></div>
                    </div>
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

<style>* {
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f5f5f5;
    padding: 20px;
}

.parent {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    grid-template-rows: 0.8fr repeat(6, 1fr);
    grid-column-gap: 2px;
    grid-row-gap: 1px;
    height: 100vh;
    max-width: 1200px;
    margin: 0 auto;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.div1 {
    grid-area: 1 / 1 / 8 / 7;
    background-color: #eef6ff;
    padding: 15px;
    display: flex;
    flex-direction: column;
}

.div2 {
    grid-area: 1 / 7 / 8 / 11;
    background-color: #f0f8ff;
    padding: 15px;
    display: flex;
    flex-direction: column;
    border-left: 1px solid #ddd;
}

.div3 {
    grid-area: 7 / 7 / 8 / 11;
    background-color: #e6f3ff;
    padding: 15px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    border-top: 1px solid #ddd;
}

.div4 {
    grid-area: 2 / 7 / 7 / 11;
    background-color: #f0f8ff;
    padding: 15px;
    overflow-y: auto;
}

.div5 {
    grid-area: 2 / 1 / 8 / 7;
    background-color: #eef6ff;
    padding: 15px;
    overflow-y: auto;
}

/* Tiêu đề */
.header {
    text-align: center;
    padding: 10px;
    margin-bottom: 15px;
    color: #333;
    font-size: 24px;
    font-weight: bold;
    background-color: #d4e9ff;
    border-radius: 8px;
}

/* Danh sách dịch vụ - Cấu trúc grid với tối đa 5 hàng */
.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 15px;
    padding: 15px;
    max-height: calc(100% - 70px); /* Chiều cao tối đa để chứa 5 hàng và nút điều hướng */
    overflow-x: auto;
    scroll-behavior: smooth;
    overflow-y: auto;
    height: calc(100% - 70px);
    scrollbar-width: thin;
}

.service-grid::-webkit-scrollbar {
    width: 6px;
}

.service-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.service-grid::-webkit-scrollbar-thumb {
    background: #00b894;
    border-radius: 3px;
}

/* Card dịch vụ */
.service-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
    padding: 15px;
    display: flex;
    flex-direction: column;
    height: 280px;
}

.service-card.selected {
    border: 2px solid #00b894;
    transform: translateY(-3px);
    box-shadow: 0 4px 15px rgba(0, 184, 148, 0.2);
}

.service-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.service-image {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-radius: 6px;
    margin-bottom: 10px;
}

.service-name {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3436;
    margin-bottom: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.service-price {
    font-size: 1.1rem;
    font-weight: 600;
    color: #00b894;
    margin-bottom: 6px;
}

/* Controls */
.controls {
    display: flex;
    gap: 10px;
    width: 100%;
}

.control-btn {
    flex: 1;
    padding: 10px;
    background-color: #4285f4;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
}

.control-btn.create {
    background-color: #0f9d58;
}

.control-btn.edit {
    background-color: #4285f4;
}

.control-btn.delete {
    background-color: #db4437;
}

.control-btn:hover {
    opacity: 0.9;
}

/* Form */
.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

.file-input {
    margin-top: 5px;
}

/* Scroll buttons */
.scroll-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 15px;
}

.scroll-btn {
    background-color: #4285f4;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 18px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.scroll-btn:hover {
    background-color: #3367d6;
}

.content-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    padding: 20px;
    min-height: calc(100vh - 100px);
    height: calc(100vh - 100px);
}

.service-list-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 140px);
}

.service-info {
    padding: 12px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.service-category {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #636e72;
    font-size: 0.8rem;
    margin-bottom: 6px;
}

.service-description {
    font-size: 0.85rem;
    color: #636e72;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
}

.service-form-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    padding: 20px;
    position: sticky;
    top: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #2d3436;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #dfe6e9;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #00b894;
    box-shadow: 0 0 0 2px rgba(0, 184, 148, 0.1);
    outline: none;
}

.image-preview {
    margin-top: 10px;
    width: 100%;
    min-height: 200px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px dashed #dfe6e9;
}

.image-preview img {
    border-radius: 8px;
}

.btn {
    padding: 12px 20px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #00b894;
    color: white;
}

.btn-primary:hover {
    background: #00a082;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #636e72;
    color: white;
    margin-left: 10px;
}

.btn-secondary:hover {
    background: #555a5e;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.section-header {
    padding: 15px 20px;
    background: linear-gradient(135deg, #00b894 0%, #00d2a8 100%);
    color: white;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 10;
}

.section-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #636e72;
    font-size: 1.1rem;
}

.view-controls {
    display: flex;
    gap: 8px;
}

.view-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.view-btn:hover,
.view-btn.active {
    background: rgba(255, 255, 255, 0.4);
}

@media (max-width: 1200px) {
    .service-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }
}

@media (max-width: 1024px) {
    .content-wrapper {
        grid-template-columns: 1fr;
    }

    .service-form-section {
        position: static;
    }
}

@media (max-width: 768px) {
    .content-wrapper {
        grid-template-columns: 1fr;
    }
    
    .service-list-section {
        max-height: calc(100vh - 400px);
    }
}}
</style>
@endsection

@push('styles')
<link href="{{ asset('assets/css/services.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script>
    // Define routes exactly like in tables
    const servicesDataRoute = "{{ route('services.data') }}";
    const servicesStoreRoute = "{{ route('services.store') }}";
    const servicesShowRoute = "{{ route('services.show', ':id') }}";
    const servicesUpdateRoute = "{{ route('services.update', ':id') }}";
    const servicesDestroyRoute = "{{ route('services.destroy', ':id') }}";
    const servicesDeleteRoute = '/services/:id';
</script>
<script src="{{ asset('assets/js/services.js') }}"></script>
@endpush