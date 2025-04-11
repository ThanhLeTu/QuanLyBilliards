// Define reusable utility functions
const utils = {
    formatPrice: price => new Intl.NumberFormat('vi-VN').format(price),
    
    getStatusIcon: status => {
      const icons = {
        'available': '<i class="fas fa-check-circle text-success"></i>',
        'reserved': '<i class="fas fa-user text-danger"></i>',
        'occupied': '<i class="fas fa-user text-danger"></i>',
        'unavailable': '<i class="fas fa-ban text-secondary"></i>'
      };
      return icons[status] || '<i class="fas fa-question-circle"></i>';
    },
    
    getStatusText: status => {
      const texts = {
        'available': 'Trống',
        'reserved': 'Đã đặt',
        'occupied': 'Đang sử dụng',
        'unavailable': 'Không khả dụng'
      };
      return texts[status] || 'Không xác định';
    }
  };
  
  // Core table management functions
  window.tableManager = {
    loadTables: () => {
      $.ajax({
        url: tablesIndexRoute,
        type: "GET",
        dataType: "json",
        success: data => {
          const tableCards = data.map(table => `
            <div class="table-card ${table.status}" data-id="${table.id}">
              <div class="table-status-icon">
                ${utils.getStatusIcon(table.status)}
              </div>
              <div class="table-number">
                <i class="fas fa-table"></i> Bàn ${table.table_number}
              </div>
              <div class="table-info">
                <span><i class="fas fa-map-marker-alt"></i> ${table.area}</span>
                <span><i class="fas fa-tag"></i> ${table.table_type}</span>
              </div>
              <div class="table-info">
                <span><i class="fas fa-money-bill-wave"></i> ${utils.formatPrice(table.price)}đ/giờ</span>
                <span class="status-badge ${table.status}">
                  ${utils.getStatusText(table.status)}
                </span>
              </div>
              ${!isHomePage ? `
              <div class="table-actions">
                <button class="action-btn edit-btn" onclick="tableManager.showEditModal(${table.id})">
                  <i class="fas fa-edit"></i> Sửa
                </button>
                <button class="action-btn delete-btn" onclick="tableManager.deleteTable(${table.id})">
                  <i class="fas fa-trash-alt"></i> Xóa
                </button>
              </div>` : ''}
              ${(table.status === 'occupied' && isHomePage) ? `
              <div class="reservation-actions">
                <button class="btn btn-success confirm-reservation-btn" data-id="${table.id}">Xác nhận</button>
                <button class="btn btn-danger cancel-reservation-btn" data-id="${table.id}">Hủy</button>
              </div>` : ''}
            </div>
          `).join('');
          
          $('#tablesGrid').html(tableCards);
        },
        error: (xhr, status, error) => {
          console.error("Lỗi khi tải danh sách bàn:", error);
          alert('Có lỗi xảy ra khi tải danh sách bàn!');
        }
      });
    },
    
    showEditModal: id => {
      $.ajax({
        url: tablesShowRoute.replace(':id', id),
        type: "GET",
        dataType: "json",
        success: data => {
          $('#editTableId').val(data.id);
          $('#edit_table_number').val(data.table_number);
          $('#edit_area').val(data.area);
          $('#edit_table_type').val(data.table_type);
          $('#edit_price').val(data.price);
          $('#edit_status').val(data.status);
          $('#edit_description').val(data.description);
          $('#editTableModal').modal('show');
        },
        error: (xhr, status, error) => {
          console.error("Lỗi khi lấy thông tin bàn:", error);
          alert('Có lỗi xảy ra khi lấy thông tin bàn!');
        }
      });
    },
    
    deleteTable: id => {
      if (confirm('Bạn có chắc chắn muốn xóa bàn này?')) {
        $.ajax({
          url: tablesDestroyRoute.replace(':id', id),
          type: "DELETE",
          dataType: "json",
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: data => {
            tableManager.loadTables();
            alert('Xóa bàn thành công!');
          },
          error: (xhr, status, error) => {
            console.error("Lỗi khi xóa bàn:", error);
            alert('Có lỗi xảy ra khi xóa bàn!');
          }
        });
      }
    },
    
    updateTableStats: () => {
      $.ajax({
        url: '/table-stats',
        type: 'GET',
        success: data => {
          $('#active-tables').text(data.activeTables);
          $('#total-tables').text(data.totalTables);
          $('#usage-rate').text(data.usageRate);
          
          // Update available tables dropdown
          const dropdown = $('#table_id');
          dropdown.empty();
          
          data.availableTables.forEach(table => {
            dropdown.append(
              $('<option></option>')
                .attr('value', table.id)
                .text(`Bàn số ${table.table_number} - ${table.area}`)
            );
          });
        },
        error: xhr => {
          console.error("Lỗi khi cập nhật thống kê:", xhr.responseText);
        }
      });
    }
  };
  
  // For backward compatibility
  window.showEditModal = tableManager.showEditModal;
  window.deleteTable = tableManager.deleteTable;
  window.loadTables = tableManager.loadTables;
  
  // Document ready handler
  $(document).ready(() => {
    // Initial load
    tableManager.loadTables();
    
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
    
    // Table card click handler
    $(document).on('click', '.table-card', function() {
      const tableId = $(this).data('id'); 
      openBillingModal(tableId);
    });
    
    // Add new table
    $('#addTableBtn').click(() => {
      $.ajax({
        url: tablesStoreRoute,
        type: "POST",
        dataType: "json",
        data: $('#addTableForm').serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: data => {
          tableManager.loadTables();
          $('#addTableForm')[0].reset();
          $('#addTableModal').modal('hide');
          alert('Thêm bàn thành công!');
        },
        error: (xhr, status, error) => {
          console.error("Lỗi khi thêm bàn:", error);
          alert('Có lỗi xảy ra khi thêm bàn!');
        }
      });
    });
    
    // Update table
    $('#updateTableBtn').click(() => {
      const tableId = $('#editTableId').val();
      $.ajax({
        url: tablesUpdateRoute.replace(':id', tableId),
        type: "PUT",
        dataType: "json",
        data: $('#editTableForm').serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: data => {
          tableManager.loadTables();
          $('#editTableModal').modal('hide');
          alert('Cập nhật bàn thành công!');
        },
        error: (xhr, status, error) => {
          console.error("Lỗi khi cập nhật bàn:", error);
          alert('Có lỗi xảy ra khi cập nhật bàn!');
        }
      });
    });
    
    // Reservation form submit
    $('#addReservationForm').submit(function(e) {
      e.preventDefault();
      
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: response => {
          alert(response.message || 'Đặt bàn thành công!');
          $('#addReservationModal').modal('hide');
          tableManager.loadTables();
          tableManager.updateTableStats();
          $(this)[0].reset();
        },
        error: xhr => {
          console.error("Lỗi khi đặt bàn:", xhr.responseText);
          alert('Lỗi khi đặt bàn: ' + (xhr.responseJSON?.message || 'Đã xảy ra lỗi'));
        }
      });
    });
    
    // Confirm reservation
    $(document).on('click', '.confirm-reservation-btn', function() {
      const tableId = $(this).data('id');
      
      if (!tableId) {
        alert("Không tìm thấy bàn để xác nhận!");
        return;
      }
      
      if (confirm('Bạn có chắc chắn muốn xác nhận bàn này không?')) {
        $.ajax({
          url: `/reservations/confirm/${tableId}`,
          type: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: response => {
            alert(response.message);
            tableManager.loadTables();
            tableManager.updateTableStats();
          },
          error: xhr => {
            console.error("Lỗi khi xác nhận bàn:", xhr.responseText);
            alert('Lỗi khi xác nhận bàn!');
          }
        });
      }
    });
    
    // Cancel reservation
    $(document).on('click', '.cancel-reservation-btn', function() {
      const tableId = $(this).data('id');
      
      if (!tableId) {
        alert("Không tìm thấy bàn để hủy đặt!");
        return;
      }
      
      if (confirm('Bạn có chắc chắn muốn hủy đặt bàn này không?')) {
        $.ajax({
          url: `/reservations/cancel/${tableId}`,
          type: 'PATCH',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: response => {
            alert(response.message);
            tableManager.loadTables();
            tableManager.updateTableStats();
          },
          error: xhr => {
            console.error("Lỗi khi hủy đặt bàn:", xhr.responseText);
            alert('Lỗi khi hủy đặt bàn!');
          }
        });
      }
    });
  });

  

  