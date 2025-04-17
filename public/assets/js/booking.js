window.showBookingModal = function(tableId) {
    $('#bookingTableId').val(tableId);
    $('#bookingModal').modal('show');
};

$('#bookingForm').submit(function(e) {
    e.preventDefault();
    
    const tableId = $('#bookingTableId').val();
    const customerName = $('#customerName').val();
    const customerPhone = $('#customerPhone').val();
    const startTime = $('#startTime').val();
    const endTime = $('#endTime').val();

    $.ajax({
        url: bookingsStoreRoute, 
        type: "POST",
        dataType: "json",
        data: {
            table_id: tableId,
            customer_name: customerName,
            customer_phone: customerPhone,
            start_time: startTime,
            end_time: endTime
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            loadTables();
            $('#bookingModal').modal('hide');
            alert('Đặt bàn thành công!');
        },
        error: function(xhr, status, error) {
            console.error("Lỗi khi đặt bàn:", error);
            alert('Có lỗi xảy ra khi đặt bàn!');
        }
    });
});
