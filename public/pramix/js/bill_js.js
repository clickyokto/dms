$(document).ready(function () {

    // $("#bill-details-panel-form").hide('slow');
    $("#payment_update_item_btn").hide('slow');

    window.supplier_outstanding_table = $('#supplier_outstanding_table').DataTable({
        order: [[0, "desc"]],
        processing: true,
        serverSide: false,
        order: [[1, "desc"]],
        iDisplayLength: 15,
        ajax: BASE + 'purchase_orders_list/' + 0 + '/supplier',
        bInfo: false,

        columns: [
            {
                data: 'id', name: 'id',
                'targets': 0,
                'checkboxes': {
                    'selectRow': true
                },
                'createdCell': function (td, cellData, rowData, row, col) {

                    this.api().cell(td).checkboxes.select();

                }
            },

            {data: 'purchase_order_code', name: 'purchase_order_code'},
            {data: 'purchase_order_date', name: 'purchase_order_date'},
            {data: 'total', name: 'total', className: 'dt-body-right'},
            {data: 'paid_amount', name: 'paid_amount', className: 'dt-body-right'},
            {data: 'balance', name: 'balance', className: 'dt-body-right'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'status', name: 'status'},
            {data: 'created_by', name: 'created_by'},
            {data: 'action', name: 'action'},
        ]
    });

    $(document).on('click', '#edit_purchase_order', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.open(url, '_blank');
    });

    $(document).on('click', '#new_bill', function (e) {
        e.preventDefault();
        var data = supplier_outstanding_table.row($(this).parents('tr')).data();

        $("#purchase_order_id").val(data['id'])

        $("#bill_hedding").text('Payment Details : ' + data['purchase_order_code']).css({color: 'red'});

        PurchaseOrderPaymentsTable.ajax.url(BASE + 'purchase_order/get_sales_payments/' + $('#purchase_order_id').val()).load();
    });

    window.PurchaseOrderPaymentsTable = $('#paymentsTable').DataTable({
        searching: false,
        paging: true,
        responsive: true,
        "ordering": true,
        order: [[0, "desc"]],
        "destroy": true,
        ajax: BASE + 'purchase_order/get_sales_payments/' + $('#purchase_order_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'purchase_order_code', name: 'purchase_order_code'},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'payment_ref_no', name: 'payment_ref_no'},
            {data: 'payment_remarks', name: 'payment_remarks'},
            {data: 'payment_amount', name: 'payment_amount'},
            {data: 'actions', name: 'actions',}
        ]
    });


    $('#paymentsTable tbody').on('click', 'button.bill-edit-button', function (e) {

        var data = PurchaseOrderPaymentsTable.row($(this).parents('tr')).data();

        $('#record_bill_update_id').val(data['id']);
        $('#bill_date').val(data['bill_date']);
        $("#bill_method").val(data['bill_method']);
        $('#bill_ref_no').val(data['bill_ref_no']);
        $('#bill_remarks').val(data['bill_remarks']);
        $('#payment_amount').val(data['payment_amount']);
        $('#payment_add_item_btn').hide('slow');
        $('#payment_update_item_btn').show('slow');
        return false;

    });

    $('#paymentsTable tbody').on('click', 'button.bill-delete-button', function (e) {

        var data = PurchaseOrderPaymentsTable.row($(this).parents('tr')).data();

        var bill_details = $('#bill-details-panel-form :input').serialize();

        var params = {
            bill_details: bill_details,
            purchase_order_id: $('#purchase_order_id').val(),
            record_bill_id: data['id']
        };
        $.ajax({
            url: BASE + 'purchase_order/delete_bill',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    PurchaseOrderPaymentsTable.ajax.reload();

                    calPrice.run();
                    supplier_outstanding_table.ajax.url(BASE + 'purchase_orders_list/' + $('#supplier_code_selected').val() + '/supplier/bill').load();
                } else {
                    notification(response);
                    return false;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        return false;
    });

    $('#paymentsTable tbody').on('click', 'button.bill-print-button', function (e) {
        var $btn = $(this);
        $btn.button('loading');

        var data = PurchaseOrderPaymentsTable.row($(this).parents('tr')).data();

        var params = {
            purchase_order_id: $('#purchase_order_id').val(),
            record_bill_id: data['id']
        };

        $.ajax({
            url: BASE + 'purchase_order/print_bill',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    window.open(response.url);
                    $btn.button('reset');

                } else {
                    notification(response);
                    $btn.button('reset');
                    return false;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });

        return false;
    });


    $("#payment_add_item_btn , #payment_update_item_btn").click(function (e) {
        if ($('#payment_amount').val() == '') {
            return false;
        }

        var purchase_orders_selected = supplier_outstanding_table.column(0).checkboxes.selected();

        var purchase_orders_array = [];

        $.each(purchase_orders_selected, function (index, rowId) {
            purchase_orders_array.push(rowId);
        });
        var bill_details = $('#payment-details-panel-form :input').serialize();

        var params = {
            supplier_id: $('#supplier_code_selected').val(),
            bill_details: bill_details,
            record_bill_id: $('#record_bill_update_id').val(),
            purchase_orders_selected: purchase_orders_array
        };

        $.ajax({
            url: BASE + 'bill/save_bill',
            type: 'POST',
            dataType: 'JSON',
            async: false,
            data: $.param(params),
            success: function (response) {

                notification(response);

                if (response.status == 'success') {
                    PurchaseOrderPaymentsTable.ajax.url(BASE + 'purchase_order/get_sales_payments/' + $('#supplier_code_selected').val()+'/'+ 'supplier').load();

                    $('#record_bill_update_id').val('');
                    $('#payment_update_item_btn').hide('slow');
                    $('#payment_add_item_btn').show('slow');
                    calPrice.run();
                    supplier_outstanding_table.ajax.url(BASE + 'purchase_orders_list/' + $('#supplier_code_selected').val() + '/supplier/payment').load();
                    $('#bill_ref_no').val('');
                    $('#bill_remarks').val('');
                    $('#payment_amount').val('');

                } else {
                    return false;
                }


            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
    });

});
