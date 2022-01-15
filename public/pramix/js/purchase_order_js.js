$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $("#purchase_order_form").validationEngine();

    $("#paid").prop("readonly", true);

    $('#update_item_row_btn').hide('slow');


    $("#purchase_order_discount_type").change(function (e) {
        calPrice.run();
    });

    $("#checked_vat").change(function (e) {
        calPrice.run();
    });

    $("#checked_nbt").change(function (e) {
        calPrice.run();
    });

    $("#tax").keyup(function (e) {
        calPrice.run();
    });

    $("#purchase_order_discount").keyup(function (e) {
        calPrice.run();
    });

    $("#paid").keyup(function (e) {
        calPrice.run();
    });

    if ($('#ref_id').val() == '') {
        $('#purchase_order-update-btn').hide('slow');
        $('#purchase_order_approved_btn').hide('slow');
        $('#used_grn_btn').hide('slow');


    } else {
        $('#sales_purchase_order_save_btn').hide('slow');
    }

    if ($('#purchase_order_id').val() != '') {

        $('#overlay').hide('slow');

        $('#quotation_code_selected').prop("disabled", true);
        $('#job_card_code_selected').prop("disabled", true);
        calPrice.run();

    } else {

        $('#generate_purchase_order_pdf').hide('slow');
        $('#generate_mail').hide('slow');
        $('#duplicate_purchase_order').hide('slow');

        calPrice.run();
    }


    $("#used_grn_btn").click(function (e) {

        window.grn_history_model = $.confirm({
            title: 'GRN History',
            draggable: true,
            boxWidth: '80%',
            closeIcon: true,
            useBootstrap: false,
            buttons: {

                close: function () {
                }
            },
            content: 'url:' + BASE + 'get_view_used_grn_model/' + $('#purchase_order_id').val(),
            onContentReady: function () {

            },
            columnClass: 'medium',
        });
        return false;
    });


    $('#quantity ,  #description, #unit_price, #product_discount').keypress(function (e) {
        var key = e.which;
        if (key == 13)  // the enter key code
        {
            $('#add_item_row_btn').click();
            return false;
        }
    });


    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $('.selectpicker').selectpicker('mobile');
    }

    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {


        var product_details = $('#product-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            purchase_order_id: $('#purchase_order_id').val(),
            record_product_id: $('#record_product_update_id').val()
        };

        $.ajax({
            url: BASE + 'purchase_order/add_purchase_order_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    $('#products_info_alert').hide('slow');
                    ProductsTable.ajax.url(BASE + 'purchase_order/get_purchase_order_products/' + $('#purchase_order_id').val()).load();

                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');
                    calPrice.run();


                    $("#item_category_code").val('').trigger('change');
                    $("#item_product_code").val('').trigger('change.select2');
                    $('#description').val('');
                    $('#quantity').val('');

                    $('#unit_price').val('');
                    $('#product_discount').val('');

                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });


    });


    $("#generate_purchase_order_pdf").click(function (e) {

        if ($('#purchase_order_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            purchase_order_id: $('#purchase_order_id').val(),
        };

        $.ajax({
            url: BASE + 'purchase_order/generate_pdf',
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
    });


    $("#duplicate_purchase_order").click(function (e) {

        if ($('#purchase_order_id').val() == '')
            return false;

        var $btn = $(this);
        $btn.button('loading');

        var params = {
            purchase_order_id: $('#purchase_order_id').val(),
        };

        $.ajax({
            url: BASE + 'purchase_order/duplicate_purchase_order',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {

                    window.location.href = BASE + 'purchase_order/' + response.purchase_order_no + '/edit';

                } else {

                }
            },
            error: function (errors) {

            }
        });
    });

    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'purchase_order/get_purchase_order_products/' + $('#purchase_order_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'category', name: 'category'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'quantity', name: 'quantity'},
            {data: 'unit_price', name: 'unit_price'},
            {data: 'discount', name: 'discount'},
            {data: 'discount_type_show', name: 'discount_type_show'},
            {data: 'discount_type', name: 'discount_type', 'bVisible': false},
            {data: 'sub_total', name: 'sub_total'},
            {data: 'actions', name: 'actions'},
        ]
    });

    $('#ProductsTable tbody').on('click', '.purchase_order_product_edit_btn', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id);
        $('#item_category_code').trigger('change.select2');
        $("#item_product_code").val(data['product'].id);
        $('#item_product_code').trigger('change.select2');
        $('#description').val(data['description']);
        $('#quantity').val(data['quantity']);
        $('#unit_price').val(data['unit_price']);
        $('#product_discount').val(data['discount']);
        $('#product_discount_type').val(data['discount_type']);
        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');


        return false;

    });

    $('#ProductsTable tbody').on('click', '.purchase_order_product_delete_btn', function () {

        var data = ProductsTable.row($(this).parents('tr')).data();
        var purchase_order_price_details = $('#price_panel :input').serialize();
        var params = {
            purchase_order_price_details: purchase_order_price_details,
            record_id: data['id'],
            purchase_order_id: $('#purchase_order_id').val()
        };
        $.ajax({
            url: BASE + 'purchase_order/delete_purchase_order_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    ProductsTable.ajax.reload();
                    calPrice.run();
                    return false;
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

    window.createPurchaseOrder = {
        run: function () {
            var supplier_id = $("#supplier_code_selected").val();
            if (supplier_id == '')
                supplier_id = $("#supplier_name_selected").val();


            var params = {
                supplier_id: supplier_id,
            };

            var url;
            var method;

            if ($('#purchase_order_id').val() != '') {
                url = BASE + 'purchase_order/' + $('#purchase_order_id').val();
                method = 'PUT';

            } else {
                url = BASE + 'purchase_order';
                method = 'POST';
            }

            $.ajax({
                url: url,
                type: method,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {

                        $('#overlay').hide('slow');

                        $("#purchase_order_id").val(response.purchase_order_details.id);
                        $("#purchase_order_no").val(response.purchase_order_details.purchase_order_code);
                        $("#ref_id").val(response.purchase_order_details.id);

                        if (response.purchase_order_details.status == 'D')
                            $('#display_status').html('<span class="label label-danger">Draft</span>');
                        else if (response.purchase_order_details.status == 'A')
                            $('#display_status').html('<span class="label label-success">Completed</span>');

                        CommentsListTable.ajax.url(BASE + 'get_comments_list/' + $('#ref_type').val() + '/' + $('#ref_id').val()).load();
                    } else {
                        notification(response)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });

        }
    }

    $("#supplier_id_selected ,  #supplier_name_selected , #company_id_selected").change(function (e) {
        if ($('#purchase_order_id').val() != '') {
            return false;
        }

        createPurchaseOrder.run();

    });

    if ($('#purchase_order_id').val() == '' || $('#quotation_id').val() == '') {
        var selected_quotation = new Option('Please select quotation', '', true, true);
        $('#quotation_code_selected').append(selected_quotation).trigger('change.select2');
    }

    if ($('#purchase_order_id').val() == '' || $('#job_card_id').val() == '') {
        var selected_job_card = new Option('Please select job card', '', true, true);
        $('#job_card_code_selected').append(selected_job_card).trigger('change.select2');
    }

    $("#quotation_code_selected").change(function (e) {
        if ($('#quotation_code_selected').val() == '') {
            return false;
        }

        var params = {
            quotation_id: $('#quotation_code_selected').val(),
            purchase_order_id: $('#purchase_order_id').val()
        };
        e.preventDefault();
        $.ajax({
            url: BASE + 'quotation/convert_to_purchase_order',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                notification(response);
                if (response.status == 'success') {

                    window.location.href = BASE + 'purchase_order/' + response.purchase_order_no + '/edit';
                } else {
                    window.location.href = BASE + 'purchase_order/' + response.purchase_order_no + '/edit';
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        e.preventDefault();
        return false;

    });


    //    payment part
    $('#payment_update_item_btn').hide('slow');


    window.PurchaseOrderPaymentsTable = $('#paymentsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'purchase_order/get_sales_payments/' + $('#purchase_order_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'purchase_order_code', name: 'purchase_order_code', 'bVisible': false},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'payment_ref_no', name: 'payment_ref_no'},
            {data: 'payment_remarks', name: 'payment_remarks'},
            {data: 'payment_amount', name: 'payment_amount'},
            {data: 'actions',name: 'actions',}
        ]
    });


    $('#paymentsTable tbody').on('click', 'button.payment-edit-button', function (e) {

        var data = PurchaseOrderPaymentsTable.row($(this).parents('tr')).data();

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

        var data = PurchaseOrderPaymentsTable.row($(this).parents('tr')).data();

        var payment_details = $('#payment-details-panel-form :input').serialize();

        var params = {
            payment_details: payment_details,
            purchase_order_id: $('#purchase_order_id').val(),
            record_payment_id: data['id']
        };
        $.ajax({
            url: BASE + 'purchase_order/delete_payment',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    PurchaseOrderPaymentsTable.ajax.reload();
                    calPrice.run();
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

        var data = PurchaseOrderPaymentsTable.row($(this).parents('tr')).data();

        var params = {
            purchase_order_id: $('#purchase_order_id').val(),
            record_payment_id: data['id']
        };

        $.ajax({
            url: BASE + 'purchase_order/print_payment',
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

        var payment_details = $('#payment-details-panel-form :input').serialize();

        var params = {
            payment_details: payment_details,
            purchase_order_id: $('#purchase_order_id').val(),
            record_payment_id: $('#record_payment_update_id').val()
        };
        if ($('#purchase_order_id').val() != '') {
            $.ajax({
                url: BASE + 'purchase_order/save_payment',
                type: 'POST',
                dataType: 'JSON',
                async: false,
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {
                        PurchaseOrderPaymentsTable.ajax.url(BASE + 'purchase_order/get_sales_payments/' + $('#purchase_order_id').val()).load();

                        $('#record_payment_update_id').val('');
                        $('#payment_update_item_btn').hide('slow');
                        $('#payment_add_item_btn').show('slow');
                        $('#payment_ref_no').val('');
                        $('#payment_remarks').val('');
                        $('#payment_amount').val('');
                        calPrice.run();

                    } else {

                        return false;
                    }



                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });
        }



    });

    $("#item_product_code").tooltip({
        trigger: "hover",
        html: true,
        placement: "top"
    });

    $("#view_quotation").click(function (e) {

        window.location.href = BASE + 'quotation/' + $('#quotation_id').val() + '/edit';
    });


    updatePurchaseOrder = {
        run: function (status) {
            var recurring_details = $('#recurring_panel :input').serialize();

            var params = {
                status: status,
                remarks: $('#remarks').Editor("getText"),
                supplier_id: $('#supplier_id').val(),
                project_id: $('#project_code_selected').val(),
                purchase_order_due_date: $('#purchase_order_due_date').val(),
                staff_id: $('#staff_member_id_selected').val(),
                purchase_order_company: $('#INVOICE_COMPANY').val(),
                recurring_details : recurring_details
            };


            $.ajax({
                url: BASE + 'purchase_order/' + $('#purchase_order_id').val(),
                type: 'PUT',
                async: false,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {

                        $('#purchase_order_no').val(response.purchase_order_details.purchase_order_code);
                        $('#generate_purchase_order_pdf').show('slow');
                        $('#generate_mail').show('slow');
                        $('#duplicate_purchase_order').show('slow');
                            $('#purchase_order-update-btn').show('slow');
                        $('#purchase_order_approved_btn').show('slow');
                            $('#sales_purchase_order_save_btn').hide('slow');
                            if (response.purchase_order_details.status == 'A')
                            $('#display_status').html('<span class="label label-success">Completed</span>');

                    } else {


                        return false;
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });

            return false;

        }
    };

    $('#checked_recurring').change(function () {
        if ($('#checked_recurring').is(':checked')) {
            $('#status_recurring').show(1000);
            $('#other_recurring').show(1000);
        } else {
            $('#status_recurring').hide(1000);
            $('#other_recurring').hide(1000);
        }
    });

    $("#sales_purchase_order_save_btn, #purchase_order-update-btn, #purchase_order_approved_btn").click(function (e) {

        var btn = $(this).attr("id");

        var valid = $("#purchase_order_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }
        if ($("#purchase_order_id").val() == '') {
            return false
        }

        if (btn != 'purchase_order_approved_btn') {
            updatePurchaseOrder.run('D');
            return false;
        }

        var purchase_order_save_confirm = $.confirm({
            title: 'Save PurchaseOrder',
            type: 'blue',
            buttons: {
                draft: {
                    text: 'Cancel',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-default',
                    action: function () {

                    }
                },
                complete: {
                    text: 'Approved',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-primary',
                    action: function () {
                        updatePurchaseOrder.run('A');

                    }
                },

            }
        });
        return false;


    });


});
