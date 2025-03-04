$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Xử lý xem trước hình ảnh
    $('#serviceImage').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.image-preview').html(`<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px;">`);
            }
            reader.readAsDataURL(file);
        }
    });

    // Xử lý thêm dịch vụ mới
    $('#addServiceBtn').click(function() {
        const formData = new FormData($('#serviceForm')[0]);
        
        $.ajax({
            url: servicesStoreRoute,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Hiển thị thông báo thành công
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã thêm dịch vụ mới'
                }).then(() => {
                    // Reset form
                    $('#serviceForm')[0].reset();
                    $('.image-preview').empty();
                    // Tải lại danh sách dịch vụ
                    loadServices();
                });
            },
            error: function(xhr) {
                // Hiển thị thông báo lỗi
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể thêm dịch vụ. Vui lòng kiểm tra lại thông tin.'
                });
                console.error(xhr.responseText);
            }
        });
    });

    let selectedServiceId = null;

    // Xử lý khi click vào service card
    $(document).on('click', '.service-card', function() {
        const serviceId = $(this).data('id');
        selectedServiceId = serviceId;
        
        // Xóa class selected từ tất cả các card
        $('.service-card').removeClass('selected');
        // Thêm class selected vào card được chọn
        $(this).addClass('selected');
        
        // Lấy thông tin dịch vụ để điền vào form
        $.ajax({
            url: servicesShowRoute.replace(':id', serviceId),
            type: 'GET',
            success: function(service) {
                // Điền thông tin vào form
                $('#serviceName').val(service.name);
                $('#servicePrice').val(service.price);
                $('#serviceCategory').val(service.category);
                $('#serviceDescription').val(service.description);
                
                // Hiển thị hình ảnh nếu có
                if (service.image) {
                    $('.image-preview').html(`
                        <img src="/storage/services/${service.image}" 
                             alt="Preview" 
                             style="max-width: 100%; max-height: 200px;">
                    `);
                }

                // Enable các nút sửa và xóa
                $('#editServiceBtn').prop('disabled', false);
                $('#deleteServiceBtn').prop('disabled', false);
                // Disable nút thêm mới
                $('#addServiceBtn').prop('disabled', true);
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể tải thông tin dịch vụ'
                });
            }
        });
    });

    // Xử lý khi click nút Cập nhật
    $('#editServiceBtn').click(function() {
        if (!selectedServiceId) return;

        const formData = new FormData($('#serviceForm')[0]);
        
        $.ajax({
            url: servicesUpdateRoute.replace(':id', selectedServiceId),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PUT'
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: 'Đã cập nhật dịch vụ'
                }).then(() => {
                    // Reset form và các trạng thái
                    resetForm();
                    // Tải lại danh sách dịch vụ
                    loadServices();
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể cập nhật dịch vụ'
                });
                console.error(xhr.responseText);
            }
        });
    });

    // Hàm reset form và trạng thái
    function resetForm() {
        $('#serviceForm')[0].reset();
        $('.image-preview').empty();
        selectedServiceId = null;
        $('.service-card').removeClass('selected');
        $('#editServiceBtn').prop('disabled', true);
        $('#deleteServiceBtn').prop('disabled', true);
        $('#addServiceBtn').prop('disabled', false);
    }

    // Thêm nút Reset để hủy chọn
    $('#serviceForm').append(`
        <button type="button" class="btn btn-secondary" id="resetBtn">
            <i class="fas fa-undo"></i> Làm mới
        </button>
    `);

    $('#resetBtn').click(function() {
        resetForm();
    });

    // Hàm tải danh sách dịch vụ
    function loadServices() {
        $.ajax({
            url: servicesDataRoute,  // Use the route variable defined in the view
            type: "GET",
            dataType: "json",
            success: function(data) {
                if (data.length === 0) {
                    $('#serviceGrid').html('<div class="no-data">Không có dịch vụ nào</div>');
                    return;
                }

                let html = '';
                data.forEach(service => {
                    html += `
                        <div class="service-card" data-id="${service.id}">
                            <img src="${service.image ? '/storage/services/' + service.image : '/assets/img/default-service.png'}" 
                                 class="service-image" 
                                 alt="${service.name}">
                            <div class="service-info">
                                <h3 class="service-name">${service.name}</h3>
                                <div class="service-category">
                                    <i class="fas ${getCategoryIcon(service.category)}"></i>
                                    ${getCategoryName(service.category)}
                                </div>
                                <div class="service-price">${formatPrice(service.price)}đ</div>
                                <p class="service-description">${service.description || 'Không có mô tả'}</p>
                            </div>
                        </div>
                    `;
                });
                $('#serviceGrid').html(html);
            },
            error: function(xhr, status, error) {
                console.error("Lỗi khi tải danh sách dịch vụ:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: 'Không thể tải danh sách dịch vụ'
                });
            }
        });
    }

    // Hàm hỗ trợ format giá
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    // Hàm lấy icon cho danh mục
    function getCategoryIcon(category) {
        switch(category) {
            case 'drink':
                return 'fa-glass-martini-alt';
            case 'food':
                return 'fa-utensils';
            default:
                return 'fa-box';
        }
    }

    // Hàm lấy tên danh mục tiếng Việt
    function getCategoryName(category) {
        switch(category) {
            case 'drink':
                return 'Đồ uống';
            case 'food':
                return 'Đồ ăn';
            default:
                return 'Khác';
        }
    }

    // Tải danh sách dịch vụ khi trang được load
    loadServices();
});