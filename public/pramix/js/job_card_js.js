 $(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $('#update_item_row_btn').hide('slow');

    $('#create_new_customer_model').hide('slow');

    if ($('#job_card_id').val() != '') {

        $('#overlay').hide('slow');

        $("#customer_filter :input").attr("disabled", true);
        calPrice.run();

    } else {
        $('#generate_job_card_pdf').hide('slow');

    }
    if ($('#job_card_status').val() == '' || $('#job_card_status').val() == 'D') {
        $('#job_card_update_btn').hide('slow');
        $('#convert_to_invoice').hide('slow');

    } else {
        $('#job_card_save_btn').hide('slow');
    }

    $("#job_card_discount_type").change(function (e) {
        calPrice.run();
    });

    $("#job_card_discount").keyup(function (e) {
        calPrice.run();
    });

    $("#refund").keyup(function (e) {
        calPrice.run();
    });

    $("#checked_vat").change(function (e) {
        calPrice.run();
    });

    $("#checked_nbt").change(function (e) {
        calPrice.run();
    });

    $("#convert_to_invoice").click(function (e) {

        var params = {
            job_card_id: $('#job_card_id').val()
        };
        e.preventDefault();
        $.ajax({
            url: BASE + 'job_card/convert_to_invoice',
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

    $("#generate_job_card_pdf").click(function (e) {

        if ($('#job_card_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            job_card_id: $('#job_card_id').val(),
        };

        $.ajax({
            url: BASE + 'job_card/generate_pdf',
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


    // var selected_item = new Option('Please select Item', '', true, true);
    // $('#item_product_code').append(selected_item).trigger('change.select2');

    // var selected_category = new Option('Please select category', '', true, true);
    // $('#item_category_code').append(selected_category).trigger('change.select2');

    $("#customer_id_selected ,  #customer_name_selected , #company_id_selected").change(function (e) {
        if ($('#job_card_id').val() != '') {
            return false;
        }
        // createJobCardCode.run();
    });

    window.createJobCardCode = {
        run: function () {
            var repair_details = $('#repair_details_panel :input').serialize();
            var radioValue = $("input[name='inquiry_status']:checked").val();

            var params = {
                customer_id: $("#customer_id").val(),
                job_card_id: $("#job_card_code_selected").val(),
                repair_details_panel : repair_details,
                status : radioValue,

            };
            var url;
            var method;
            if ($('#job_card_id').val() != '') {
                url = BASE + 'job_card/' + $('#job_card_id').val();
                method = 'PUT';
            } else {
                url = BASE + 'job_card';
                method = 'POST';
            }
            $.ajax({
                url: url,
                type: method,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response)
                    if (response.status == 'success') {
                        $('#overlay').hide('slow');
                        $("#job_card_id").val(response.job_card_details.id);
                        $("#ref_id").val(response.job_card_details.id);
                        $("#job_card_no").val(response.job_card_details.job_card_code);
                    } else {

                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });
        }
    }

    $('#ProductsTable tbody').on('click', 'tr', function () {
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else {
            ProductsTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'job_card/get_job_card_products/' + $('#job_card_id').val(),
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
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });


    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {

        if ($('#item_product_code').val() == 0) {
            return false;
        }

        var product_details = $('#product-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            job_card_id: $('#ref_id').val(),
            record_product_id: $('#record_product_update_id').val(),
        };

        $.ajax({
            url: BASE + 'job_card/add_job_card_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    ProductsTable.ajax.url(BASE + 'job_card/get_job_card_products/' + $('#job_card_id').val()).load();

                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');

                    $("#customer_filter :input").attr("disabled", true);
                    $('#products_info_alert').hide('slow');

                    calPrice.run();
                } else {
                    notification(response)
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });

        $("#item_category_code").val('').trigger('change.select2');
        $("#item_product_code").val('').trigger('change.select2');
        $('#checked_discarded').prop('checked', false);
        $('#description').val('');
        $('#quantity').val('');
        $('#unit_price').val('');
        $('#product_discount').val('');


    });


    $('#ProductsTable tbody').on('click', 'button.icon-edit', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();

        $('#record_product_update_id').val(data['id']);

        $("#item_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#item_product_code").val(data['product'].id).trigger('change.select2');
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

        var job_card_price_details = $('#price_panel :input').serialize();

        var params = {
            job_card_price_details: job_card_price_details,
            record_id: data['id'],
        };

        $.ajax({
            url: BASE + 'job_card/delete_job_card_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    ProductsTable.ajax.reload();
                    // calPrice.run();
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


    // updateJobCard = {
    //     run: function () {
    //         var repair_details = $('#repair_details_panel :input').serialize();
    //
    //         var params = {
    //
    //             remarks: $('#remarks').Editor("getText"),
    //             vehicle: $('#vehicle_selected').val(),
    //             customer_id: $('#customer_id').val(),
    //             job_card_id: $('#job_card_id').val(),
    //             job_card_status : repair_details,
    //             repair_details : repair_details,
    //         };
    //
    //         $.ajax({
    //             url: BASE + 'job_card/' + $('#job_card_id').val(),
    //             type: 'PUT',
    //             async: false,
    //             dataType: 'JSON',
    //             data: $.param(params),
    //             success: function (response) {
    //                 notification(response);
    //                 if (response.status == 'success') {
    //
    //                     $('#generate_job_card_pdf').show('slow');
    //
    //                     if (status == 'A') {
    //                         // $('#product-details-panel').hide('slow');
    //                         $('#customer_detail_panel :input').prop("disabled", true);
    //                         // $('#price_panel :input').prop("disabled", true);
    //                         $('#job_card_details_panel :input').prop("disabled", true);
    //                         $('#job_card_update_btn').show('slow');
    //                         $('#job_card_save_btn').hide('slow');
    //                         $('#display_status').html('<span class="label label-success">Completed</span>');
    //                     }
    //                 } else {
    //                     return false;
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //
    //                 notificationError(xhr, ajaxOptions, thrownError);
    //             }
    //         });
    //         return false;
    //     }
    // };

    $("#job_card_save_btn, #job_card_update_btn").click(function (e) {
        createJobCardCode.run();
        return false;
    });


});
