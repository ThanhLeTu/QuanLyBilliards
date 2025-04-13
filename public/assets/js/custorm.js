let playCost = 0;
let reservationLoaded = false;
let currentReservationId = null;

function calculatePlayTime(startTime, endTime) {
    const start = new Date(startTime);
    const end = endTime ? new Date(endTime) : new Date();
    const diffMs = end - start;
    const diffHours = diffMs / (1000 * 60 * 60);
    return diffHours.toFixed(2);
}

function formatCurrency(amount) {
    return amount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
}

function updateCartSummary() {
    let cartTotal = 0;
    
    $('#cartItems .cart-item').each(function () {
        // Thêm console.log để debug
        console.log('Cart item:', $(this));
        
        // Kiểm tra trước khi cố gắng truy cập
        const quantityInput = $(this).find('.quantity-input');
        if (quantityInput.length === 0) {
            console.warn('Không tìm thấy .quantity-input trong:', $(this));
            return;
        }
        
        // Lấy giá trị một cách an toàn
        let quantityValue = quantityInput.val() || '0';
        let quantity = parseInt(quantityValue);
        
        // Kiểm tra data-price tồn tại
        if (!$(this).data('price') && $(this).data('price') !== 0) {
            console.warn('Không tìm thấy data-price trong:', $(this));
            return;
        }
        
        let price = parseInt($(this).data('price'));
        
        // Kiểm tra nếu không phải là số
        if (isNaN(quantity) || isNaN(price)) {
            console.warn('Giá trị không hợp lệ:', { 
                element: $(this), 
                quantityValue: quantityValue,
                quantity: quantity, 
                priceValue: $(this).data('price'),
                price: price 
            });
            return; // bỏ qua item lỗi
        }
        
        cartTotal += quantity * price;
    });
    
    $('#cartTotal').text(formatPrice(cartTotal) + ' đ');
    
    // Kiểm tra playCost là số
    const safePlayCost = isNaN(playCost) ? 0 : playCost;
    
    const totalPayment = safePlayCost + cartTotal;
    $('#totalPayment').text(formatPrice(totalPayment) + ' đ');
    $('#finalPayment').text(formatPrice(totalPayment) + ' đ');
}

function updateServerQuantity(reservationId, serviceId, quantity) {
    $.ajax({
        url: '/update-reservation-service',
        method: 'POST',
        data: {
            reservation_id: reservationId,
            service_id: serviceId,
            quantity: quantity,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log('Cập nhật thành công:', response);
            updateCartSummary();
        },
        error: function (xhr) {
            console.error('Lỗi khi cập nhật:', xhr.responseText);
        }
    });
}

function attachCartEvents(reservationId) {
    $('.increase-qty').off('click').on('click', function () {
        const item = $(this).closest('.cart-item');
        const quantityEl = item.find('.quantity');
        let quantity = parseInt(quantityEl.text()) + 1;
        quantityEl.text(quantity);

        updateServerQuantity(reservationId, item.data('service-id'), quantity);
    });

    $('.decrease-qty').off('click').on('click', function () {
        const item = $(this).closest('.cart-item');
        const quantityEl = item.find('.quantity');
        let quantity = parseInt(quantityEl.text()) - 1;

        if (quantity < 1) return;

        quantityEl.text(quantity);
        updateServerQuantity(reservationId, item.data('service-id'), quantity);
    });

    $('.remove-service').off('click').on('click', function () {
        const item = $(this).closest('.cart-item');
        const serviceId = item.data('service-id');

        item.remove();
        updateServerQuantity(reservationId, serviceId, 0);
        updateCartSummary();
    });
}

function addServiceToCart(reservationId, serviceId, serviceName, price) {
    const existingItem = $(`.cart-item[data-service-id="${serviceId}"]`);

    if (existingItem.length > 0) {
        const quantityEl = existingItem.find('.quantity');
        let quantity = parseInt(quantityEl.text()) + 1;
        quantityEl.text(quantity);
    } else {
        $('#cart-items').append(`
            <div class="cart-item" data-service-id="${serviceId}" data-price="${price}">
                <span>${serviceName}</span>
                <span class="quantity">1</span>
                <button class="increase-qty">+</button>
                <button class="decrease-qty">-</button>
                <button class="remove-service">X</button>
            </div>
        `);
        attachCartEvents(reservationId);
    }

    const newQuantity = existingItem.length > 0
        ? parseInt(existingItem.find('.quantity').text())
        : 1;

    updateServerQuantity(reservationId, serviceId, newQuantity);
    updateCartSummary();
}

$(document).ready(function () {
    const reservationId = $('#cart-items').data('reservation-id');

    attachCartEvents(reservationId);
    updateCartSummary();

    $(document).on('click', '.add-service', function () {
        const serviceId = $(this).data('id');
        const serviceName = $(this).data('name');
        const price = parseInt($(this).data('price'));

        addServiceToCart(reservationId, serviceId, serviceName, price);
    });
});


function loadReservationDetail(tableId) {
    $.get(`/reservations/playing-info/${tableId}`, function (data) {
        const reservation = data.reservation;
        const services = data.services;
        const customer = data.customer;
        const table = data.table;

        const playTime = calculatePlayTime(reservation.start_time, reservation.end_time);
        const hourlyRate = parseInt(table.price_per_hour);
        playCost = parseFloat(playTime) * hourlyRate;

        $('#modalCustomerName').text(customer.name);
        $('#modalTableName').text(table.name);
        $('#modalStartTime').text(reservation.start_time);
        $('#modalEndTime').text(reservation.end_time ?? 'Đang chơi');
        $('#modalPlayTime').text(playTime + ' giờ');
        $('#modalPlayCost').text(formatCurrency(playCost));

        const $cartList = $('#cart-items');
        $cartList.empty();

        services.forEach(service => {
            $cartList.append(`
                <div class="cart-item" data-service-id="${service.service_id}" data-price="${service.service.price}">
                    <span>${service.service.name}</span>
                    <span class="quantity">${service.quantity}</span>
                    <button class="increase-qty">+</button>
                    <button class="decrease-qty">-</button>
                    <button class="remove-service">X</button>
                </div>
            `);
        });

        attachCartEvents(reservation.id);
        updateCartSummary();

        $('#reservationModal').modal('show');
    });
}

$(document).ready(function () {
    $('.table-card').on('click', function () {
        const tableId = $(this).data('table-id');
        loadReservationDetail(tableId);
    });
});


function openBillingModal(tableId) {
    reservationLoaded = false;
    $('#billingModal').modal('show');
    loadReservationInfo(tableId);
    loadServicesForReservation();
}

function loadReservationInfo(tableId) {
    $.ajax({
        url: '/reservations/by-table/' + tableId,
        method: 'GET',
        success: function (data) {
            if (data.success && data.reservation) {
                const reservation = data.reservation;
                currentReservationId = reservation.id;
                const table = reservation.table;
                const customer = reservation.customer;

                const startTime = reservation.start_time ? new Date(reservation.start_time) : null;
                const endTime = reservation.end_time ? new Date(reservation.end_time) : null;

                const startTimeStr = startTime ? startTime.toLocaleString() : 'Không có';
                const endTimeStr = endTime ? endTime.toLocaleString() : 'Đang chơi';

                const now = new Date();
                const durationMs = (endTime || now) - startTime;
                const durationMinutes = Math.floor(durationMs / (1000 * 60));
                const durationHours = durationMs / (1000 * 60 * 60);

                const hourlyRate = parseInt(table.price);
                playCost = Math.round(durationHours * hourlyRate);

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

                reservationLoaded = true; // ✅ đánh dấu đã load xong
            } else {
                alert(data.message || 'Không tìm thấy thông tin đặt bàn.');
            }
        }
    });
}

function formatPrice(price) {
    return price.toLocaleString('vi-VN');
}

function updateCartItemTotal(item, quantity, price) {
    item.find('.cart-item-total').text(formatPrice(quantity * price) + ' đ');
}

function updateCartSummary() {
    let cartTotal = 0;

    $('#cartItems .cart-item').each(function () {
        let quantity = parseInt($(this).find('.quantity-input').val());
        let price = parseInt($(this).data('price'));

        // Kiểm tra nếu không phải là số
        if (isNaN(quantity) || isNaN(price)) {
            console.warn('Giá trị không hợp lệ:', { quantity, price });
            return; // bỏ qua item lỗi
        }

        cartTotal += quantity * price;
    });

    $('#cartTotal').text(formatPrice(cartTotal) + ' đ');

    // Kiểm tra playCost là số
    const safePlayCost = isNaN(playCost) ? 0 : playCost;

    const totalPayment = safePlayCost + cartTotal;
    $('#totalPayment').text(formatPrice(totalPayment) + ' đ');
    $('#finalPayment').text(formatPrice(totalPayment) + ' đ');
}


function updateServerQuantity(serviceId, quantity) {
    $.ajax({
        url: '/update-reservation-service',
        method: 'POST',
        data: {
            service_id: serviceId,
            quantity: quantity,
            reservation_id: currentReservationId,
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

$(document).on('click', '.product-card', function () {
    if (!reservationLoaded) {
        alert('Vui lòng đợi thông tin bàn được tải xong!');
        return;
    }

    const card = $(this);
    const id = card.data('id');
    const name = card.data('name');
    const price = parseInt(card.data('price'));
    let existingItem = $(`#cartItems .cart-item[data-id="${id}"]`);
    if (existingItem.length > 0) {
        let quantityInput = existingItem.find('.quantity-input');
        let quantity = parseInt(quantityInput.val()) + 1;
        quantityInput.val(quantity);
        updateCartItemTotal(existingItem, quantity, price);
        updateServerQuantity(id, quantity);
    } else {
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
        updateServerQuantity(id, 1);
    }

    updateCartSummary();
});

$(document).on('click', '.btn-increase', function () {
    const item = $(this).closest('.cart-item');
    const input = item.find('.quantity-input');
    let quantity = parseInt(input.val()) + 1;
    const price = parseInt(item.data('price'));
    input.val(quantity);
    updateCartItemTotal(item, quantity, price);
    updateServerQuantity(item.data('id'), quantity);
    updateCartSummary();
});

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
    }
});

$(document).on('click', '.btn-remove', function () {
    const item = $(this).closest('.cart-item');
    const id = item.data('id');
    item.remove();
    updateServerQuantity(id, 0);
    updateCartSummary();
});

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
}
$('#confirmPaymentBtn').click(function () {
    if (!currentReservationId) return;

    const tableName = $('#billingTableNumber').text().trim(); // Bàn số
    const startTime = $('#billingStartTime').text().trim();   // Bắt đầu
    const endTime = $('#billingEndTime').text().trim();       // Kết thúc
    const duration = $('#billingDuration').text().trim();     // Thời gian chơi
    const hourlyRate = parseInt($('#hourlyRate').text().replace(/[^\d]/g, '')); // Giá/giờ
    const tableCost = parseInt($('#billingTotal').text().replace(/[^\d]/g, '')); // Tổng tiền bàn

    const customerName = $('input[name="customer_name"]').val().trim();   // Tên khách hàng
    const customerPhone = $('input[name="customer_phone"]').val().trim(); // SĐT khách hàng
    const customerNote = $('textarea[name="customer_note"]').val().trim(); // Ghi chú

    const serviceCost = parseInt($('#cartTotal').data('total')); // Tổng tiền dịch vụ
    const totalCost = parseInt($('#finalPayment').text().replace(/[^\d]/g, '')); // Tổng thanh toán

    // Dịch vụ trong giỏ hàng
    const services = [];
    $('#cartItems .cart-item').each(function () {
        const serviceId = $(this).data('id');
        const serviceName = $(this).find('.fw-bold').text().trim();
        const quantity = parseInt($(this).find('.quantity-input').val());
        const price = parseInt($(this).data('price'));

        if (quantity > 0) {
            services.push({
                service_id: serviceId,
                name: serviceName,
                quantity,
                price
            });
        }
    });

    // Gửi Ajax tạo hóa đơn
    $.ajax({
        url: '/invoices',
        method: 'POST',
        data: {
            reservation_id: currentReservationId,
            customer_name: customerName,
            customer_phone: customerPhone,
            customer_note: customerNote,
            table_name: tableName,
            start_time: startTime,
            end_time: endTime,
            duration: duration,
            table_price: hourlyRate,
            table_cost: tableCost,
            service_cost: serviceCost,
            total_cost: totalCost,
            services: services
            
        },
        success: function (response) {
            console.log('Hi', services);
            if (response.invoice_id) {
                // Chuyển hướng sang trang hóa đơn
                window.location.href = `/invoices/${response.invoice_id}`;
            }
        },
        error: function (err) {
            alert('Lỗi khi tạo hóa đơn!');
            console.error(err);
        }
    });
});
