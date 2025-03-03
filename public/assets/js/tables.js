// Define functions in global scope
window.showEditModal = function(id) {
    $.ajax({
        url: tablesShowRoute.replace(':id', id),
        type: "GET",
        dataType: "json",
        success: function(data) {
            $('#editTableId').val(data.id);
            $('#edit_table_number').val(data.table_number);
            $('#edit_area').val(data.area);
            $('#edit_table_type').val(data.table_type);
            $('#edit_price').val(data.price);
            $('#edit_status').val(data.status);
            $('#edit_description').val(data.description);
            $('#editTableModal').modal('show');
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi lấy thông tin bàn:", error);
            alert('Có lỗi xảy ra khi lấy thông tin bàn!');
        }
    });
};

window.deleteTable = function(id) {
    if (confirm('Bạn có chắc chắn muốn xóa bàn này?')) {
        $.ajax({
            url: tablesDestroyRoute.replace(':id', id),
            type: "DELETE",
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                loadTables();
                alert('Xóa bàn thành công!');
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi xóa bàn:", error);
                alert('Có lỗi xảy ra khi xóa bàn!');
            }
        });
    }
};

$(document).ready(function() {
    function loadTables() {
        $.ajax({
            url: tablesIndexRoute,
            type: "GET",
            dataType: "json",
            success: function(data) {
                let html = '';
                data.forEach(table => {
                    html += `
                        <div class="table-card ${table.status}" data-id="${table.id}">
                            <div class="table-status-icon">
                                ${getStatusIcon(table.status)}
                            </div>
                            <div class="table-number">
                                <i class="fas fa-table"></i> Bàn ${table.table_number}
                            </div>
                            <div class="table-info">
                                <span><i class="fas fa-map-marker-alt"></i> ${table.area}</span>
                                <span><i class="fas fa-tag"></i> ${table.table_type}</span>
                            </div>
                            <div class="table-info">
                                <span><i class="fas fa-money-bill-wave"></i> ${formatPrice(table.price)}đ/giờ</span>
                                <span class="status-badge ${table.status}">
                                    ${getStatusText(table.status)}
                                </span>
                            </div>
                            <div class="table-actions">
                                <button class="action-btn edit-btn" onclick="showEditModal(${table.id})">
                                    <i class="fas fa-edit"></i> Sửa
                                </button>
                                <button class="action-btn delete-btn" onclick="deleteTable(${table.id})">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </button>
                            </div>
                        </div>
                    `;
                });
                $('#tablesGrid').html(html);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh sách bàn:", error);
                alert('Có lỗi xảy ra khi tải danh sách bàn!');
            }
        });
    }

    function getStatusIcon(status) {
        switch(status) {
            case 'available':
                return '<i class="fas fa-check-circle text-success"></i>';
            case 'occupied':
                return '<i class="fas fa-user text-danger"></i>';
            case 'unavailable':
                return '<i class="fas fa-ban text-secondary"></i>';
            default:
                return '<i class="fas fa-question-circle"></i>';
        }
    }

    function getStatusText(status) {
        switch(status) {
            case 'available':
                return 'Trống';
            case 'occupied':
                return 'Đang sử dụng';
            case 'unavailable':
                return 'Không khả dụng';
            default:
                return 'Không xác định';
        }
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    // Filter handling
    $('.filter-btn').click(function() {
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const status = $(this).data('status');
        if(status === 'all') {
            $('.table-card').show();
        } else {
            $('.table-card').hide();
            $(`.table-card.${status}`).show();
        }
    });

    // Initial load
    loadTables();

    // Thêm bàn mới
    $('#addTableBtn').click(function() {
        $.ajax({
            url: tablesStoreRoute,
            type: "POST",
            dataType: "json",
            data: $('#addTableForm').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                loadTables();
                $('#addTableForm')[0].reset();
                $('#addTableModal').modal('hide');
                alert('Thêm bàn thành công!');
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi thêm bàn:", error);
                alert('Có lỗi xảy ra khi thêm bàn!');
            }
        });
    });

    // Hiển thị modal sửa bàn
    $(document).on('click', '.editTableBtn', function() {
        var tableId = $(this).data('id');
        $.ajax({
            url: tablesShowRoute.replace(':id', tableId),
            type: "GET",
            dataType: "json",
            success: function(data) {
                $('#editTableId').val(data.id);
                $('#edit_table_number').val(data.table_number);
                $('#edit_area').val(data.area);
                $('#edit_table_type').val(data.table_type);
                $('#edit_price').val(data.price);
                $('#edit_status').val(data.status);
                $('#edit_description').val(data.description);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi lấy thông tin bàn:", error);
                alert('Có lỗi xảy ra khi lấy thông tin bàn!');
            }
        });
    });

    // Cập nhật bàn
    $('#updateTableBtn').click(function() {
        var tableId = $('#editTableId').val();
        $.ajax({
            url: tablesUpdateRoute.replace(':id', tableId),
            type: "PUT",
            dataType: "json",
            data: $('#editTableForm').serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                loadTables();
                $('#editTableModal').modal('hide');
                alert('Cập nhật bàn thành công!');
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi cập nhật bàn:", error);
                alert('Có lỗi xảy ra khi cập nhật bàn!');
            }
        });
    });

    // Xóa bàn
    $(document).on('click', '.deleteTableBtn', function() {
        var tableId = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa bàn này?')) {
            $.ajax({
                url: tablesDestroyRoute.replace(':id', tableId),
                type: "DELETE",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    loadTables();
                    alert('Xóa bàn thành công!');
                },
                error: function(xhr, status, error) {
                    console.error("Lỗi khi xóa bàn:", error);
                    alert('Có lỗi xảy ra khi xóa bàn!');
                }
            });
        }
    });
});