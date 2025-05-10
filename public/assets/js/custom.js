function openBillingModal(tableId) {
    $('#billingModal').modal('show');
    loadReservationInfo(tableId);
    loadServicesForReservation(); // 👈 gọi load từ services.js
}


function loadReservationInfo(tableId) {
    $.ajax({
        url: '/reservations/by-table/' + tableId,
        method: 'GET',
        success: function (data) {
            if (data.success && data.reservation) {
                const reservation = data.reservation;
                const table = reservation.table;
                const customer = reservation.customer;
        
                // Parse thời gian bắt đầu và kết thúc
                const startTime = reservation.start_time
                    ? new Date(reservation.start_time)
                    : null;
                const endTime = reservation.end_time
                    ? new Date(reservation.end_time)
                    : null;
        
                const startTimeStr = startTime ? startTime.toLocaleString() : 'Không có';
                const endTimeStr = endTime ? endTime.toLocaleString() : 'Đang chơi';
        
                // Tính thời gian chơi và tiền
                const now = new Date();
                const durationMs = (endTime || now) - startTime;
                const durationMinutes = Math.floor(durationMs / (1000 * 60));
                const durationHours = durationMs / (1000 * 60 * 60);
        
                const hourlyRate = parseInt(table.price); // 🔥 Fix chỗ này
                const playCost = Math.round(durationHours * hourlyRate);
        
                // ✅ Gắn vào HTML
                $('#billingTableNumber').text(table.table_number);
                $('#billingStartTime').text(startTimeStr);
                $('#billingEndTime').text(endTime ? endTimeStr : 'Đang chơi');
                $('#hourlyRate').text(hourlyRate.toLocaleString());
        
                $('#billingDuration').text(durationMinutes + ' phút');
                $('#billingTotal').text(playCost.toLocaleString() + ' đ');
        
                $('input[name="customer_name"]').val(customer.name);
                $('input[name="customer_phone"]').val(customer.phone);
                $('textarea[name="customer_note"]').val(customer.note ?? '');
        
                $('#playTimeCost').text(playCost.toLocaleString() + ' đ');
                $('#totalPayment').text(playCost.toLocaleString() + ' đ');
                $('#finalPayment').text(playCost.toLocaleString() + ' đ');
            } else {
                alert(data.message || 'Không tìm thấy thông tin đặt bàn.');
            }
            updateCartSummary();
            updateFinalTotal();

        }
        
        
    });
    
}

function formatDate(date) {
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) +
           ' ' + date.toLocaleDateString();
}

// Format tiền tệ
function formatPrice(price) {
    return price.toLocaleString('vi-VN');
}

// Cập nhật tổng tiền 1 dòng
function updateCartItemTotal(item, quantity, price) {
    item.find('.cart-item-total').text(formatPrice(quantity * price) + ' đ');
}

// Cập nhật tổng giỏ hàng
function updateCartSummary() {
    let total = 0;
    $('#cartItems .cart-item').each(function () {
        let quantity = parseInt($(this).find('.quantity-input').val());
        let price = parseInt($(this).data('price'));
        total += quantity * price;
    });
    $('#cartTotal').text(formatPrice(total) + ' đ');
}

// Gửi Ajax cập nhật số lượng dịch vụ
function updateServerQuantity(serviceId, quantity) {
    $.ajax({
        url: '/update-reservation-service', // Đổi thành route đúng của bạn
        method: 'POST',
        data: {
            service_id: serviceId,
            quantity: quantity,
            reservation_id: currentReservationId, // Khai báo biến này bên ngoài
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log('Cập nhật thành công:', response);
        },
        error: function (xhr) {
            console.error('Lỗi khi cập nhật:', xhr.responseText);
        }
    });
}

// Thêm dịch vụ vào giỏ khi click vào card
    $(document).on('click', '.product-card', function () {
        const card = $(this);
        const id = card.data('id');
        const name = card.data('name');
        const price = parseInt(card.data('price'));

        let existingItem = $(`#cartItems .cart-item[data-id="${id}"]`);
        if (existingItem.length > 0) {
            // Nếu đã có trong giỏ => tăng số lượng
            let quantityInput = existingItem.find('.quantity-input');
            let quantity = parseInt(quantityInput.val()) + 1;
            quantityInput.val(quantity);
            updateCartItemTotal(existingItem, quantity, price);
            updateServerQuantity(id, quantity);
        } else {
            // Nếu chưa có => thêm mới với số lượng 1, không gọi updateServerQuantity ở đây nữa
            const newItem = `
                <li class="list-group-item cart-item d-flex justify-content-between align-items-center" data-id="${id}" data-price="${price}">
                    <div>
                        <div class="fw-bold">${name}</div>
                        <div class="text-muted small">${formatPrice(price)} đ</div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="quantity-control me-3">
                            <button class="btn btn-sm btn-outline-secondary btn-decrease">-</button>
                            <input type="text" class="form-control mx-1 quantity-input" value="1" readonly style="width: 40px;">
                            <button class="btn btn-sm btn-outline-secondary btn-increase">+</button>
                        </div>
                        <span class="text-success fw-bold cart-item-total">${formatPrice(price)} đ</span>
                        <button class="btn btn-sm btn-danger ms-2 btn-remove"><i class="fas fa-times"></i></button>
                    </div>
                </li>
            `;
            $('#cartItems').append(newItem);

            // 💥 Chỉ gọi sau khi render xong
            updateServerQuantity(id, 1);
        }

        updateCartSummary();
        updateFinalTotal();
    });


// Tăng số lượng
$(document).on('click', '.btn-increase', function () {
    const item = $(this).closest('.cart-item');
    const input = item.find('.quantity-input');
    let quantity = parseInt(input.val()) + 1;
    const price = parseInt(item.data('price'));
    input.val(quantity);
    updateCartItemTotal(item, quantity, price);
    updateServerQuantity(item.data('id'), quantity);
    updateCartSummary();
    updateFinalTotal();//
});

// Giảm số lượng
$(document).on('click', '.btn-decrease', function () {
    const item = $(this).closest('.cart-item');
    const input = item.find('.quantity-input');
    let quantity = parseInt(input.val());
    if (quantity > 1) {
        quantity -= 1;
        const price = parseInt(item.data('price'));
        input.val(quantity);
        updateCartItemTotal(item, quantity, price);
        updateServerQuantity(item.data('id'), quantity);
        updateCartSummary();
        updateFinalTotal();//
    }
});

// Xóa dịch vụ khỏi giỏ
$(document).on('click', '.btn-remove', function () {
    const item = $(this).closest('.cart-item');
    const id = item.data('id');
    item.remove();
    updateServerQuantity(id, 0); // Coi 0 là xóa khỏi reservation
    updateCartSummary();
    updateFinalTotal(); //
});

// Tạm patch: Nếu chưa tách file thành công
function loadServicesForReservation() {
    $.ajax({
        url: servicesDataRoute,
        type: "GET",
        dataType: "json",
        success: function(data) {
            let html = '';
            data.forEach(service => {
                html += `
                    <div class="card product-card h-100"
                    
                         data-id="${service.id}"
                         
                         data-name="${service.name}"
                         data-price="${parseInt(service.price)}">
                        <div class="card-body">
                                                  <img src="${service.image ? '/storage/services/' + service.image : '/assets/img/default-service.png'}" 
                                 class="service-image" 
                                 alt="${service.name}">
                            <h5 class="card-title">${service.name}</h5>
                            <p class="card-text">${formatPrice(service.price)} đ</p>
                        </div>
                    </div>
                `;
            });
            $('#serviceGrid').html(html);
        },
        error: function(xhr) {
            console.error('Lỗi khi load service modal:', xhr.responseText);
        }
    });

    function updateFinalTotal() {
        const playCost = parseInt($('#playTimeCost').text().replace(/[^\d]/g, '')) || 0;
        const cartTotal = parseInt($('#cartTotal').text().replace(/[^\d]/g, '')) || 0;
        const final = playCost + cartTotal;
    
        $('#totalPayment').text(formatPrice(final) + ' đ');
        $('#finalPayment').text(formatPrice(final) + ' đ');
    }
}
