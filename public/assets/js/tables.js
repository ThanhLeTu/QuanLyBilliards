$(document).ready(function() {
    // Load danh sách bàn
    function loadTables() {
        $.ajax({
            url: tablesIndexRoute,
            type: "GET",
            dataType: "json",
            success: function(data) {
                var html = '';
                $.each(data, function(key, table) {
                    html += '<tr>';
                    html += '<td>' + table.table_number + '</td>';
                    html += '<td>' + table.area + '</td>';
                    html += '<td>' + table.table_type + '</td>';
                    html += '<td>' + table.price + '</td>';
                    html += '<td>' + table.status + '</td>';
                    html += '<td>';
                    html += '<button class="btn btn-primary btn-sm editTableBtn" data-id="' + table.id + '" data-bs-toggle="modal" data-bs-target="#editTableModal">Sửa</button>';
                    html += ' <button class="btn btn-danger btn-sm deleteTableBtn" data-id="' + table.id + '">Xóa</button>';
                    html += '</td>';
                    html += '</tr>';
                });
                $('#tablesTable tbody').html(html);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh sách bàn:", error);
                alert('Có lỗi xảy ra khi tải danh sách bàn!');
            }
        });
    }

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