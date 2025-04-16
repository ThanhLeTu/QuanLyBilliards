@extends('layouts.app')

    @section('title', 'Quản lý Nhân viên')
    @section('pagetitle', 'Nhân viên')
    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
        <li class="breadcrumb-item active">Nhân viên</li>
    @endsection

    @section('content')
    <div class="container-fluid">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h3>Danh sách nhân viên</h3>
            <a href="{{ route('employees.create') }}" class="add-table-btn">
                <i class="fas fa-user-plus"></i> Thêm nhân viên
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="status-filter">
                <button class="filter-btn active">
                    <i class="fas fa-users"></i> Tất cả
                </button>
                <button class="filter-btn">
                    <i class="fas fa-user-tie"></i> Quản lý
                </button>
                <button class="filter-btn">
                    <i class="fas fa-cash-register"></i> Thu Ngân
                </button>
                <button class="filter-btn">
                    <i class="fas fa-concierge-bell"></i> Phục vụ
                </button>
                <button class="filter-btn">
                    <i class="fas fa-user-shield"></i> Bảo vệ
                </button>
            </div>
        </div>


        <!-- Employee Grid -->
        <div class="tables-grid">
            @foreach ($employees as $emp)
            <div class="table-card" data-position="{{ $emp->position }}">
                    <div class="table-number">
                        @if($emp->avatar)
                            <img src="{{ asset('storage/' . $emp->avatar) }}" width="50" height="50" class="rounded-circle">
                        @else
                            <i class="fas fa-user-circle"></i>
                        @endif
                        {{ $emp->name }}
                    </div>
                    
                    <div class="status-badge available">{{ $emp->position }}</div>
                    
                    <div class="table-info">
                        <span><i class="fas fa-envelope"></i> Email:</span>
                        <span>{{ $emp->email }}</span>
                    </div>
                    
                    <div class="table-info">
                        <span><i class="fas fa-venus-mars"></i> Giới tính:</span>
                        <span>{{ $emp->gender }}</span>
                    </div>
                    
                    <div class="table-info">
                        <span><i class="fas fa-birthday-cake"></i> Ngày sinh:</span>
                        <span>{{ \Carbon\Carbon::parse($emp->birth_date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="table-info">
                        <span><i class="fas fa-calendar-check"></i> Ngày vào làm:</span>
                        <span>{{ \Carbon\Carbon::parse($emp->start_date)->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="table-info">
                        <span><i class="fas fa-money-bill-wave"></i> Lương:</span>
                        <span>{{ number_format($emp->salary_per_month, 0, ',', '.') }} đ</span>
                    </div>
                    
                    <div class="table-actions">
                        <a href="{{ route('employees.show', $emp->id) }}" class="action-btn edit-btn">
                            <i class="fas fa-eye"></i> Xem
                        </a>
                        <a href="{{ route('employees.edit', $emp->id) }}" class="action-btn edit-btn">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <form action="{{ route('employees.destroy', $emp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa nhân viên này?')">
                            @csrf
                            @method('DELETE')
                            <button class="action-btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $employees->links() }}
        </div>
    </div>




    @yield('scripts')
    <script>
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const keyword = this.textContent.trim().toLowerCase();

                document.querySelectorAll('.table-card').forEach(card => {
                    const position = (card.getAttribute('data-position') || '').toLowerCase();

                    if (keyword === 'tất cả' || position.includes(keyword)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
    @endsection