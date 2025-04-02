function loadCustomerInfo(tableId) {
    $.ajax({
        url: `/reservations/customer-by-table/${tableId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const customer = response.customer;
                $('#billingModal input[name="customer_name"]').val(customer.name);
                $('#billingModal input[name="customer_phone"]').val(customer.phone);
                $('#billingModal textarea[name="customer_note"]').val(customer.email || '');
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Không thể tải thông tin khách hàng.');
        }
    });
}

function openBillingModal(tableId) {
    $('#billingModal').modal('show');
    loadCustomerInfo(tableId);
}
