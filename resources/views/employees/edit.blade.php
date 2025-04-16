@extends('layouts.app')

@section('title', 'Cập nhật nhân viên')
@section('pagetitle', 'Cập nhật nhân viên')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Nhân viên</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h3>Cập nhật thông tin nhân viên</h3>
        <a href="{{ route('employees.index') }}" class="back-btn">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    <div class="employee-form-container">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-layout">
                <!-- Thông tin cơ bản -->
                <div class="form-section">
                    <h4 class="section-title">Thông tin cơ bản</h4>
                    
                    <div class="row">
                        <!-- Họ tên -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> Họ tên</label>
                                <input type="text" name="name" class="form-control" required value="{{ old('name', $employee->name) }}">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" name="email" class="form-control" required value="{{ old('email', $employee->email) }}">
                            </div>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-phone"></i> Số điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $employee->phone) }}">
                            </div>
                        </div>

                        <!-- Vị trí -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-briefcase"></i> Vị trí</label>
                                <input type="text" name="position" class="form-control" required value="{{ old('position', $employee->position) }}">
                            </div>
                        </div>

                        <!-- Giới tính -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-venus-mars"></i> Giới tính</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">-- Chọn --</option>
                                    <option value="Nam" {{ old('gender', $employee->gender) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="Nữ" {{ old('gender', $employee->gender) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                    <option value="Khác" {{ old('gender', $employee->gender) == 'Khác' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <!-- Ngày sinh -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-birthday-cake"></i> Ngày sinh</label>
                                <input type="date" name="birth_date" class="form-control" required value="{{ old('birth_date', $employee->birth_date) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin công việc -->
                <div class="form-section">
                    <h4 class="section-title">Thông tin công việc</h4>
                    
                    <div class="row">
                        <!-- Ngày vào làm -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-calendar-check"></i> Ngày bắt đầu làm</label>
                                <input type="date" name="start_date" class="form-control" required value="{{ old('start_date', $employee->start_date) }}">
                            </div>
                        </div>

                        <!-- Lương -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-money-bill-wave"></i> Lương/tháng (VNĐ)</label>
                                <input type="number" name="salary_per_month" class="form-control" required min="0" value="{{ old('salary_per_month', $employee->salary_per_month) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh và giấy tờ -->
                <div class="form-section">
                    <h4 class="section-title">Hình ảnh & Giấy tờ</h4>
                    
                    <div class="row">
                        <!-- Avatar -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-image"></i> Ảnh đại diện</label>
                                <div class="custom-file-container">
                                    <input type="file" name="avatar" class="form-control custom-file-input" accept="image/*" onchange="previewImage(this, 'avatar-preview')">
                                    <div class="preview-container" id="avatar-preview">
                                        @if ($employee->avatar)
                                            <img src="{{ asset('storage/' . $employee->avatar) }}" alt="Avatar">
                                        @else
                                            <div class="preview-placeholder">
                                                <i class="fas fa-user"></i>
                                                <p>Xem trước ảnh</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CCCD -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-id-card"></i> Ảnh CCCD</label>
                                <div class="custom-file-container">
                                    <input type="file" name="citizen_id_image" class="form-control custom-file-input" accept="image/*" onchange="previewImage(this, 'cccd-preview')">
                                    <div class="preview-container" id="cccd-preview">
                                        @if ($employee->citizen_id_image)
                                            <img src="{{ asset('storage/' . $employee->citizen_id_image) }}" alt="CCCD">
                                        @else
                                            <div class="preview-placeholder">
                                                <i class="fas fa-id-card"></i>
                                                <p>Xem trước ảnh</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="action-btn save-btn">
                        <i class="fas fa-save"></i> Cập nhật nhân viên
                    </button>
                    <a href="{{ route('employees.index') }}" class="action-btn cancel-btn">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
    function previewImage(input, targetId) {
        const preview = document.getElementById(targetId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            // Nếu không có file mới và đã có ảnh cũ, giữ nguyên ảnh cũ
            if (preview.querySelector('img') && !preview.querySelector('.preview-placeholder')) {
                return;
            }
            preview.innerHTML = `
                <div class="preview-placeholder">
                    <i class="fas fa-${targetId === 'avatar-preview' ? 'user' : 'id-card'}"></i>
                    <p>Xem trước ảnh</p>
                </div>
            `;
        }
    }
</script>
@endpush
@endsection