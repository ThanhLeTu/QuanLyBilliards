$(document).ready(function() {
    // Xử lý đặt bàn
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        const formData = {
            table_id: $('#tableId').val(),
            table_name: $('#tableName').val(),
            customer_name: $('#customerName').val(),
            phone: $('#phone').val(),
            start_time: new Date().toISOString(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: routes.bookingStore,
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    currentBookingId = response.booking.id;
                    Swal.fire({
                        icon: 'success',
                        title: 'Đặt bàn thành công!',
                        text: 'Bàn đã được đặt thành công'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể đặt bàn'
                });
            }
        });
    });

    // Xử lý thêm dịch vụ
    $('.drink-item, .food-item, .service-item').click(function() {
        if (!currentBookingId) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: 'Vui lòng đặt bàn trước khi thêm dịch vụ'
            });
            return;
        }

        const serviceId = $(this).data('id');
        const quantity = 1;

        $.ajax({
            url: routes.addService,
            type: 'POST',
            data: {
                booking_id: currentBookingId,
                service_id: serviceId,
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    updateCart();
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể thêm dịch vụ'
                });
            }
        });
    });

    // Xử lý thanh toán
    $('#checkoutBtn').click(function() {
        Swal.fire({
            title: 'Xác nhận thanh toán?',
            text: "Bạn có chắc chắn muốn thanh toán?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Thanh toán',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/bookings/${currentBookingId}/checkout`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Thành công!',
                                'Đã thanh toán thành công',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
});

function updateCart() {
    if (!currentBookingId) return;

    $.ajax({
        url: routes.cart.replace(':id', currentBookingId),
        type: 'GET',
        success: function(response) {
            if (!response.items) {
                console.error('Invalid response format');
                return;
            }

            let html = '';
            response.items.forEach(item => {
                html += `
                    <tr>
                        <td>${item.name || ''}</td>
                        <td width="120">
                            <div class="input-group">
                                <button class="btn btn-outline-secondary quantity-btn" 
                                        type="button" 
                                        data-action="decrease" 
                                        data-id="${item.id}">-</button>
                                <input type="text" class="form-control text-center" 
                                       value="${item.quantity}" readonly>
                                <button class="btn btn-outline-secondary quantity-btn" 
                                        type="button" 
                                        data-action="increase" 
                                        data-id="${item.id}">+</button>
                            </div>
                        </td>
                        <td class="text-end">${(item.total || 0).toLocaleString()} đ</td>
                    </tr>
                `;
            });
            $('.cart-items').html(html);
            $('.total-amount').text((response.total || 0).toLocaleString() + ' đ');
        },
        error: function(xhr) {
            console.error('Error updating cart:', xhr);
        }
    });
}