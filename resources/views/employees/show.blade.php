@extends('layouts.app')

@section('title', 'Chi tiết nhân viên')
@section('pagetitle', 'Chi tiết nhân viên')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
    <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Nhân viên</a></li>
    <li class="breadcrumb-item active">{{ $employee->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h3>Chi tiết nhân viên</h3>
    </div>

    <div class="employee-detail-container">
        <!-- Thông tin cơ bản và ảnh đại diện -->
        <div class="employee-profile">
            <div class="profile-image">
                @if($employee->avatar)
                    <img src="{{ asset('storage/' . $employee->avatar) }}" alt="{{ $employee->name }}">
                @else
                    <div class="no-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <h2 class="employee-name">{{ $employee->name }}</h2>
            <div class="status-badge">{{ $employee->position }}</div>
            <div class="basic-info">
                <div class="info-item">
                    <i class="fas fa-venus-mars"></i> {{ $employee->gender }}
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="employee-details">
            <div class="detail-section">
                <h4 class="section-title">Thông tin liên hệ</h4>
                
                <div class="detail-item">
                    <div class="item-label"><i class="fas fa-envelope"></i> Email:</div>
                    <div class="item-value">{{ $employee->email }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="item-label"><i class="fas fa-phone"></i> SĐT:</div>
                    <div class="item-value">{{ $employee->phone ?? 'Chưa cập nhật' }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="item-label"><i class="fas fa-birthday-cake"></i> Ngày sinh:</div>
                    <div class="item-value">{{ \Carbon\Carbon::parse($employee->birth_date)->format('d/m/Y') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="item-label"><i class="fas fa-calendar-check"></i> Ngày vào làm:</div>
                    <div class="item-value">{{ \Carbon\Carbon::parse($employee->start_date)->format('d/m/Y') }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="item-label"><i class="fas fa-money-bill-wave"></i> Lương:</div>
                    <div class="item-value">{{ number_format($employee->salary_per_month, 0, ',', '.') }} đ/tháng</div>
                </div>
            </div>
            
            <div class="detail-section">
                <h4 class="section-title">Giấy tờ</h4>
                <div class="id-image-container">
                    @if($employee->citizen_id_image)
                        <img src="{{ asset('storage/' . $employee->citizen_id_image) }}" alt="Ảnh CCCD" class="id-image">
                    @else
                        <div class="no-id-image">
                            <i class="fas fa-id-card"></i>
                            <p>Chưa có ảnh CCCD</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="action-footer">
        <a href="{{ route('employees.index') }}" class="action-btn back-btn">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
        <a href="{{ route('employees.edit', $employee->id) }}" class="action-btn edit-btn">
            <i class="fas fa-edit"></i> Chỉnh sửa thông tin
        </a>
    </div>
</div>


@endsection