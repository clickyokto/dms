$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $("#quotation_form").validationEngine();

    $('#update_item_row_btn').hide('slow');

    $("#quotation_discount_type ,#checked_vat, #checked_nbt ").change(function (e) {
        calPrice.run();
    });
    $("#tax").keyup(function (e) {
        calPrice.run();
    });
    $("#quotation_discount").keyup(function (e) {
        calPrice.run();
    });

    if ($('#quotation_id').val() != '') {

        $('#overlay').hide('slow');
        $('#sales_quotation_save_btn').hide('slow');

    } else {
        $('#generate_quotation_pdf').hide('slow');
        $('#quotation-update-btn').hide('slow');
        $('#generate_mail').hide('slow');
        $('#quotation_convert_to_invoice').hide('slow');
    }



    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $('.selectpicker').selectpicker('mobile');
    }

    $('#quantity ,  #description, #unit_price, #product_discount').keypress(function (e) {
        var key = e.which;
        if (key == 13)  // the enter key code
        {
            $('#add_item_row_btn').click();
            return false;
        }
    });

    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {


        if ($('#item_category_code').val() == 0) {
            return false;
        }
        var product_details = $('#product-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            quotation_id: $('#quotation_id').val(),
            record_product_id: $('#record_product_update_id').val()

        };

        $.ajax({
            url: BASE + 'quotation/add_quotation_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                ProductsTable.ajax.url(BASE + 'quotation/get_quotation_products/' + $('#quotation_id').val()).load();

                $('#record_product_update_id').val('');
                $('#update_item_row_btn').hide('slow');
                $('#add_item_row_btn').show('slow');
                calPrice.run();
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });


        $("#item_category_code").val('').trigger('change');
        $("#item_product_code").val('').trigger('change.select2');

        $('#description').val('');
        $('#quantity').val('');
        $('#unit_price').val('');
        $('#product_discount').val('');


    });

    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'quotation/get_quotation_products/' + $('#quotation_id').val(),
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
            {data: 'discount_type', name: 'discount_type' ,  'bVisible': false},
            {data: 'discount_type_show', name: 'discount_type_show'},
            {data: 'sub_total', name: 'sub_total'},
            {
                data: 'actions',
                name: 'actions',
                defaultContent: "<button class='btn btn-warning btn-sm icon-edit' id='product_edit_btn'></button> <button class='btn btn-danger btn-sm icon-circle-cross'></button> "
            }
        ]
    });

    $('#ProductsTable tbody').on('click', 'button.icon-edit', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();
        console.log(data)
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

    $('#ProductsTable tbody').on('click', 'button.icon-circle-cross', function () {
        var data = ProductsTable.row($(this).parents('tr')).data();


        var quotation_price_details = $('#price_panel :input').serialize();

        var params = {
            quotation_price_details: quotation_price_details,
            record_id: data['id'],
            quotation_id: $('#quotation_id').val()
        };

        $.ajax({
            url: BASE + 'quotation/delete_quotation_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    ProductsTable.ajax.reload();
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

    $("#customer_id_selected ,  #customer_name_selected, #company_id_selected").change(function (e) {
        createQuotation.run();
    });

    window.createQuotation = {
        run : function () {


            var customer_id = $("#customer_id_selected").val();
            if (customer_id == '')
                customer_id = $("#customer_name_selected").val();
            if (customer_id == '')
                customer_id = $("#company_id_selected").val();


            var params = {
                customer_id: customer_id,
                staff_id: $('#staff_member_id_selected').val()

            };

            var url;
            var method;

            if ($('#quotation_id').val() != '') {
                url = BASE + 'quotation/' + $('#quotation_id').val();
                method = 'PUT';

            } else {
                url = BASE + 'quotation';
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

                        $("#quotation_id").val(response.quotation_details.id);
                        $("#quotation_no").val(response.quotation_details.quotation_code);
                        $("#ref_id").val(response.quotation_details.id);
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

    //    payment part
    $('#payment_update_item_btn').hide('slow');


    window.QuotationPaymentsTable = $('#paymentsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'quotation/get_sales_payments/' + $('#quotation_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'payment_ref_no', name: 'payment_ref_no'},
            {data: 'payment_remarks', name: 'payment_remarks'},
            {data: 'payment_amount', name: 'payment_amount'},
            {
                data: 'actions',
                name: 'actions',
                defaultContent: "<button class='btn btn-warning btn-sm icon-edit payment-edit-button' ></button> <button class='btn btn-danger btn-sm icon-circle-cross payment-delete-button'></button> "
            }
        ]
    });


    $('#paymentsTable tbody').on('click', 'button.payment-edit-button', function (e) {

        var data = QuotationPaymentsTable.row($(this).parents('tr')).data();
        console.log(data['id']);
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

        var data = QuotationPaymentsTable.row($(this).parents('tr')).data();
        $('#record_payment_update_id').val(data['id']);


        var payment_details = $('#payment-details-panel-form :input').serialize();

        var params = {
            payment_details: payment_details,
            quotation_id: $('#quotation_id').val(),
            record_payment_id: $('#record_payment_update_id').val()
        };
        $.ajax({
            url: BASE + 'quotation/delete_payment',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    QuotationPaymentsTable.ajax.reload();
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




    $("#sales_quotation_save_btn, #quotation-update-btn").click(function (e) {

        var key = $(this).val();
        var valid = $("#quotation_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        var params = {
            remarks: $('#remarks').Editor("getText"),
            customer_id : $('#customer_id_selected').val(),
            project_id :  $('#project_code_selected').val(),
            staff_id: $('#staff_member_id_selected').val()
        };

        e.preventDefault();
        $.ajax({
            url: BASE + 'quotation/' + $('#quotation_id').val(),
            type: "PUT",
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                        $('#quotation-update-btn').show('slow');
                    $('#generate_mail').show('slow');
                        $('#generate_quotation_pdf').show('slow');
                        $('#sales_quotation_save_btn').hide('slow');
                } else {
                    return false;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        e.preventDefault();
        return false;
    });

    $("#quotation_convert_to_invoice").click(function (e) {

        var params = {
            quotation_id: $('#quotation_id').val()
        };
        e.preventDefault();
        $.ajax({
            url: BASE + 'quotation/convert_to_invoice',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                notification(response);
                if (response.status == 'success') {
                    window.location.href = BASE + 'invoice/' + response.invoice_no + '/edit';

                } else {
                    window.location.href = BASE + 'invoice/' + response.invoice_no + '/edit';
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        e.preventDefault();
        return false;
    });

    $("#generate_quotation_pdf").click(function (e) {

        if ($('#quotation_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            quotation_id: $('#quotation_id').val(),
        };

        $.ajax({
            url: BASE + 'quotation/generate_pdf',
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


});
