$(document).ready(function () {

    // $("#payment-details-panel-form").hide('slow');
    $("#payment_update_item_btn").hide('slow');

    window.customer_outstanding_table = $('#customer_outstanding_table').DataTable({
        order: [[0, "desc"]],
        processing: true,
        serverSide: false,
        order: [[1, "desc"]],
        iDisplayLength: 15,
        data: [],
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

            {data: 'invoice_code', name: 'invoice_code'},
            {data: 'invoice_date', name: 'invoice_date'},
            {data: 'total', name: 'total', className: 'dt-body-right'},
            {data: 'paid_amount', name: 'paid_amount', className: 'dt-body-right'},
            {data: 'balance', name: 'balance', className: 'dt-body-right'},
            {data: 'payment_status', name: 'payment_status'},
            {data: 'status', name: 'status'},
            {data: 'created_by', name: 'created_by'},
            {data: 'action', name: 'action'},
        ]
    });

    $(document).on('click', '#edit_invoice', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        window.open(url, '_blank');
    });

    $(document).on('click', '#new_payment', function (e) {
        e.preventDefault();
        var data = customer_outstanding_table.row($(this).parents('tr')).data();

        $("#invoice_id").val(data['id'])

        $("#payment_hedding").text('Payment Details : ' + data['invoice_code']).css({color: 'red'});

        InvoicePaymentsTable.ajax.url(BASE + 'invoice/get_sales_payments/' + $('#invoice_id').val()).load();
    });

    window.InvoicePaymentsTable = $('#paymentsTable').DataTable({
        searching: false,
        paging: true,
        responsive: true,
        "ordering": true,
        order: [[0, "desc"]],
        "destroy": true,
        ajax: BASE + 'invoice/get_sales_payments/' + $('#invoice_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'payment_code', name: 'payment_code'},
            {data: 'invoice_code', name: 'invoice_code', 'bVisible': false},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'cheque_date', name: 'cheque_date'},
            {data: 'cheque_bank', name: 'cheque_bank'},
            {data: 'payment_ref_no', name: 'payment_ref_no'},
            {data: 'payment_remarks', name: 'payment_remarks'},
            {data: 'payment_amount', name: 'payment_amount'},
            {data: 'cheque_status', name: 'cheque_status'},
            {data: 'actions', name: 'actions', 'bVisible': false}
        ]
    });


    $('#paymentsTable tbody').on('click', 'button.payment-edit-button', function (e) {

        var data = InvoicePaymentsTable.row($(this).parents('tr')).data();

        $('#record_payment_update_id').val(data['id']);
        $('#payment_date').val(data['payment_date']);
        $("#payment_method").val(data['payment_method']);
        $('#payment_ref_no').val(data['payment_ref_no']);
        $('#payment_remarks').val(data['payment_remarks']);
        $('#payment_amount').val(data['payment_amount']);
        $('#payment_add_item_btn').hide('slow');
        $('#payment_update_item_btn').show('slow');
        return false;

    });

    $('#paymentsTable tbody').on('click', 'button.payment-delete-button', function (e) {

        var data = InvoicePaymentsTable.row($(this).parents('tr')).data();

        var payment_details = $('#payment-details-panel-form :input').serialize();

        var params = {
            payment_details: payment_details,
            invoice_id: $('#invoice_id').val(),
            record_payment_id: data['id']
        };
        $.ajax({
            url: BASE + 'invoice/delete_payment',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    InvoicePaymentsTable.ajax.reload();

                    calPrice.run();
                    customer_outstanding_table.ajax.url(BASE + 'invoices_list/' + $('#customer_id_selected').val() + '/customer/payment').load();
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

    $('#paymentsTable tbody').on('click', 'button.payment-print-button', function (e) {
        var $btn = $(this);
        $btn.button('loading');

        var data = InvoicePaymentsTable.row($(this).parents('tr')).data();

        var params = {
            invoice_id: $('#invoice_id').val(),
            record_payment_id: data['id']
        };

        $.ajax({
            url: BASE + 'invoice/print_payment',
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

        var invoices_selected = customer_outstanding_table.column(0).checkboxes.selected();

        var invoices_array = [];

        $.each(invoices_selected, function (index, rowId) {
            invoices_array.push(rowId);
        });
        var payment_details = $('#payment_details_card_form :input').serialize();

        var params = {
            customer_id: $('#customer_id_selected').val(),
            payment_details: payment_details,
            record_payment_id: $('#record_payment_update_id').val(),
            invoices_selected: invoices_array
        };

        $.ajax({
            url: BASE + 'payment/save_payment',
            type: 'POST',
            dataType: 'JSON',
            async: false,
            data: $.param(params),
            success: function (response) {
                notification(response);
                InvoicePaymentsTable.ajax.url(BASE + 'invoice/get_sales_payments/' + $('#customer_id_selected').val()+'/'+ 'customer').load();

                $('#record_payment_update_id').val('');
                $('#payment_update_item_btn').hide('slow');
                $('#payment_add_item_btn').show('slow');

                customer_outstanding_table.ajax.url(BASE + 'invoices_list/' + $('#customer_id_selected').val() + '/customer/payment').load();
                refreshCustomerDetails.run();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });

        $('#payment_ref_no').val('');
        $('#payment_remarks').val('');
        $('#payment_amount').val('');


    });

});
