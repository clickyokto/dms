$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $('#update_item_row_btn').hide('slow');

    $('#create_new_supplier_model').hide('slow');

    $("#checked_vat").change(function (e) {
        calPrice.run();
    });

    $("#checked_nbt").change(function (e) {
        calPrice.run();
    });

    $("#grn_return_discount_type").change(function (e) {
        calPrice.run();
    });

    $("#grn_return_discount").keyup(function (e) {
        calPrice.run();
    });

    if ($('#grn_return_id').val() != '') {

        $('#overlay').hide('slow');
        $('#grn_return_save_btn').hide('slow');
        $("#grn_filter :input, #supplier_filter :input").attr("disabled", true);


    } else {

        $('#grn_return_approve_btn').hide('slow');
        $('#generate_grn_return_pdf').hide('slow');
        $('#grn_return_update_btn').hide('slow');
    }

    var selected_item = new Option('Please select Item', '', true, true);
    $('#grn_return_item_product_code').append(selected_item).trigger('change.select2');


    var selected_category = new Option('Please select category', '', true, true);
    $('#grn_return_item_category_code').append(selected_category).trigger('change.select2');


    var selected_grn = new Option('Please select GRN', '', true, true);
    $('#grn_code_selected').append(selected_grn).trigger('change.select2');

    window.createPOreturnCode = {
        run: function () {
            var params = {
                supplier_id: $("#supplier_id").val(),
                grn_id: $("#grn_code_selected").val()
            };
            var url;
            var method;
            if ($('#grn_return_id').val() != '') {
                url = BASE + 'grn_return/' + $('#grn_return_id').val();
                method = 'PUT';
            } else {
                url = BASE + 'grn_return';
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
                        $("#grn_return_id").val(response.order_details.id);
                        $("#ref_id").val(response.order_details.id);
                        $('#display_status').html('<span class="label label-danger">Draft</span>');
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
        ajax: BASE + 'grn_return/get_grn_return_products/' + $('#grn_return_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'quantity', name: 'quantity'},
            {data: 'unit_price', name: 'unit_price'},
            {data: 'sub_total', name: 'sub_total'},
            {data: 'store_location', name: 'store_location'},
            {
                data: 'actions',
                name: 'actions'

            }
        ]
    });


    $('#grn_return_item_product_code').change(function (e) {

        if ($("#grn_return_item_product_code").val() == '') {
            $('#grn_return_item_category_code').val('').trigger('change.select2');
            $('#description').val('').tooltip('show');
            $('#quantity').val('').tooltip('show');
            $('#unit_price').val('').tooltip('show');
            return false;
        }
        var params = {
            grn_id: $("#grn_code_selected").val(),
            product_id: $("#grn_return_item_product_code").val()
        };
        var method = '';
        var url = '';
        url = BASE + 'get_grn_return_product_details';
        method = 'POST';
        e.preventDefault();
        $.ajax({
            url: url,
            type: method,
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    $('#grn_return_item_category_code').val(response.grn_return_product_detail.product.category_id).trigger('change.select2');
                    $('#description').val(response.grn_return_product_detail.description).tooltip('show');
                    $('#quantity').val(1).tooltip('show');
                    $('#unit_price').val(response.grn_return_product_detail.unit_price).tooltip('show');
                    var tooltip = " <a href='#' class='close' data-dismiss='alert' aria-label='close'> </a>" +
                        "<div class='row'>" +
                        "<div class='col-sm-12'>" +
                        "<strong>" + response.grn_return_product_detail.product.item_code + "</strong>" +
                        "</div>" +
                        "<div class='col-sm-9'>Buy Item : " + response.grn_return_product_detail.delivered_qty + "</div>" +
                        "</div>";
                    $('#products_info_alert').show('slow');
                    $('#products_info_alert').html(tooltip);
                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
    });

    $('#grn_return_item_category_code').change(function (e) {

        var params = {
            category_id: $("#grn_return_item_category_code").val(),
            grn_id: $('#grn_code_selected').val()
        };
        var method = '';
        var url = '';
        url = BASE + 'get_grn_products_by_category';
        method = 'POST';
        e.preventDefault();
        $.ajax({
            url: url,
            type: method,
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    $('#products_info_alert').hide('slow');
                    $("#grn_return_item_product_code").find('option').remove();
                    $.each(response.products, function () {
                        $("#grn_return_item_product_code").append($("<option />").val(this.id).text(this.item_code));
                    });
                    var selectedproduct = new Option('Please select product', '', true, true);
                    $('#grn_return_item_product_code').append(selectedproduct).trigger('change.select2');

                     $('#grn_return_item_product_code').select2('open');
                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
    });

    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {
        if ($('#grn_return_item_product_code').val() == '') {
            return false;
        }
        var product_details = $('#product-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            grn_return_id: $('#ref_id').val(),
            record_product_id: $('#record_product_update_id').val(),
            grn_id: $('#grn_code_selected').val()
        };
        $.ajax({
            url: BASE + 'grn_return/add_grn_return_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if(response.status == 'success') {
                    ProductsTable.ajax.url(BASE + 'grn_return/get_grn_return_products/' + $('#grn_return_id').val()).load();
                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');
                    $("#grn_filter :input, #supplier_filter :input").attr("disabled", true);
                    $('#products_info_alert').hide('slow');
                    $("#grn_return_item_category_code").val('');
                    $('#grn_return_item_category_code').trigger('change');
                    $("#grn_return_item_product_code").val('');
                    $('#grn_return_item_product_code').trigger('change.select2');
                    $('#description').val('');
                    $('#store_location').val('');
                    $('#quantity').val('');
                    $('#unit_price').val('');
                    calPrice.run();
                }else
                {

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
    });


    $('#ProductsTable tbody').on('click', 'button.icon-edit', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();

        $('#record_product_update_id').val(data['id']);
        $("#grn_return_item_category_code").val(data['product'].category_id);
        $('#grn_return_item_category_code').trigger('change.select2');
        $("#grn_return_item_product_code").val(data['product'].id);
        $('#grn_return_item_product_code').trigger('change.select2');
        $('#description').val(data['description']);
        $('#quantity').val(data['quantity']);
        $("#store_location").val(data['store_id']).trigger('change.select2');
        $('#unit_price').val(data['unit_price']);
        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');
        return false;

    });


    $('#ProductsTable tbody').on('click', 'button.icon-circle-cross', function () {
        var data = ProductsTable.row($(this).parents('tr')).data();

        var order_price_details = $('#price_panel :input').serialize();

        var params = {
            order_price_details: order_price_details,
            product_id: data['id'],
            grn_return_id: $('#grn_return_id').val()
        };

        $.ajax({
            url: BASE + 'grn_return/delete_grn_return_product',
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

    $("#grn_return_save_btn, #grn_return_update_btn,#grn_return_approve_btn").click(function (e) {

        var btn = $(this).attr("id");


        if ($("#grn_return_id").val() == '') {
            return false
        }
        var valid = $("#grn_return_form").validationEngine('validate');
        if (valid != true) {

            return false;
        }

        var status = '';
        if(btn == 'grn_return_approve_btn')
        {
            status = 'A';
            $('#grn_return_approve_btn').hide('slow');
        }



        var params = {

            remarks: $('#remarks').Editor("getText"),
            supplier_id: $('#supplier_code_selected').val(),
            grn_id: $("#grn_code_selected").val(),
            status : status
        };
        disable_save_button_group.run();
        e.preventDefault();
        $.ajax({
            url: BASE + 'grn_return/' + $('#grn_return_id').val(),
            type: 'PUT',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                    $('#grn_return_update_btn').show('slow');
                    $('#grn_return_save_btn').hide('slow');
                    $('#generate_grn_return_pdf').show('slow');
                    if(btn == 'grn_return_save_btn')
                        $('#grn_return_approve_btn').show('slow');

                    if (status == 'A'){
                        $('#display_status').html('<span class="label label-success">Approved</span>');
                        $('#all_details_panel :input').prop("disabled", true);
                        $('#grn_return_details_panel :input').prop("disabled", true);

                        $('#price_panel :input').prop("disabled", true);
                        $('#product-details-panel').hide('slow');
                    }

                } else {
                    notification(response);
                    return false;
                }
                enable_save_button_group.run();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                enable_save_button_group.run();
                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        e.preventDefault();
        return false;
    });

    $("#generate_grn_return_pdf").click(function (e) {

        if ($('#grn_return_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            grn_return_id: $('#grn_return_id').val(),
        };
        $.ajax({
            url: BASE + 'grn_return/generate_pdf',
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
