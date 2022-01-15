$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $('#update_item_row_btn').hide('slow');
    $('#update_production_item_row_btn').hide('slow');
    $('#cost_update_item_row_btn').hide('slow');

    if ($('#production_id').val() != '') {

        $('#overlay').hide('slow');
        $('#production_save_btn').hide('slow');
        $("#production_filter :input").attr("disabled", true);

    } else {
        $('#production-update-btn').hide('slow');
        $("#production-approve-btn").hide('slow');
    }

    $("#production_save_btn, #production-update-btn").click(function (e) {

        var btn = $(this).attr("id");
        if ($("#production_id").val() == '') {
            return false
        }

        var params = {
            date: $('#order_date_created').val(),
            status: 'D'
        };
        disable_save_button_group.run();
        e.preventDefault();
        $.ajax({
            url: BASE + 'production/' + $('#production_id').val(),
            type: 'PUT',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                    $('#production-update-btn').show('slow');
                    $('#production_save_btn').hide('slow');

                    $("#production_code").val(response.production_details.code);
                    if (btn == 'production_save_btn')
                        $("#production-approve-btn").show('slow');

                } else {
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

    $("#production_product_code").change(function (e) {

        var params = {
            selected_product_id: this.value,
            date: $('#order_date_created').val()
        };

        var url;
        var method;

        if ($('#production_id').val() != '') {
            url = BASE + 'production/' + $('#production_id').val();
            method = 'PUT';
        } else {
            url = BASE + 'production';
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
                    $("#production_id").val(response.production_details.id);
                    if (response.product_details.default_store_location != null)
                        $("#production_store_location").val(response.product_details.default_store_location).trigger('change.select2');


                    $("#ref_id").val(response.production_details.id);
                    // CommentsListTable.ajax.url(BASE + 'get_comments_list/' + $('#ref_type').val() + '/' + $('#ref_id').val()).load();


                    if (response.production_details.status == 'D')
                        $('#display_status').html('<span class="label label-danger">Draft</span>');
                    else if (response.production_details.status == 'A')
                        $('#display_status').html('<span class="label label-success">Completed</span>');
                } else {
                    notification(response)

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
    });

    $("#add_production_item_row_btn , #update_production_item_row_btn").click(function (e) {

        if ($('#production_category_code').val() == 0) {
            return false;
        }
        var production_product_details = $('#production-product-details-panel :input').serialize();
        var params = {
            production_product_details: production_product_details,
            production_id: $('#production_id').val(),
            record_production_product_update_id: $('#record_production_product_update_id').val()
        };

        $.ajax({
            url: BASE + 'production/add_making_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                    MakingProductsTable.ajax.url(BASE + 'production/get_making_products/' + $('#production_id').val()).load();
                    ProductionProductsTable.ajax.url(BASE + 'production/get_production_products/' + $('#production_id').val()).load();
                    $('#record_production_product_update_id').val('');
                    $('#update_production_item_row_btn').hide('slow');
                    $('#add_production_item_row_btn').show('slow');
                    $('#products_info_alert').hide('slow');
                    $("#production_category_code").val('').trigger('change.select2');
                    $("#production_product_code").val('').trigger('change.select2');
                    $('#production_store_location').val('');
                    $('#qty').val('');

                    $("#item_product_code").val(response.consume_paddy.product_id).trigger('change');
                    $('#ordered_qty').val(response.consume_paddy.qty);
                    $('#ordered_qty').focus();

                } else {
                    return false;
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });

    });

    window.MakingProductsTable = $('#MakingProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'production/get_making_products/' + $('#production_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'category', name: 'category'},
            {data: 'item', name: 'item'},
            {data: 'quantity', name: 'quantity'},
            {data: 'store_location', name: 'store_location'},
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });

    $('#MakingProductsTable tbody').on('click', 'button.icon-edit.production-product-edit', function (e) {

        var data = MakingProductsTable.row($(this).parents('tr')).data();
        $('#record_production_product_update_id').val(data['id']);
        $("#production_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#production_product_code").val(data['product'].id).trigger('change.select2');

        $("#production_store_location").val(data['store_location_id']).trigger('change.select2');
        $('#qty').val(data['quantity']);

        $('#add_production_item_row_btn').hide('slow');
        $('#update_production_item_row_btn').show('slow');
        return false;
    });

    $('#MakingProductsTable tbody').on('click', 'button.icon-circle-cross.production-product-delete', function () {
        var data = MakingProductsTable.row($(this).parents('tr')).data();

        var params = {

            record_id: data['id'],
        };
        $.ajax({
            url: BASE + 'production/delete_making_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    MakingProductsTable.ajax.reload();

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

    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {

        if ($('#item_category_code').val() == 0) {
            return false;
        }
        var product_details = $('#production-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            production_id: $('#production_id').val(),
            record_product_id: $('#record_product_update_id').val()
        };

        $.ajax({
            url: BASE + 'production/add_production_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                    ProductionProductsTable.ajax.url(BASE + 'production/get_production_products/' + $('#production_id').val()).load();
                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');
                    $("#customer_filter :input").attr("disabled", true);
                    $('#products_info_alert').hide('slow');
                    $("#item_category_code").val('').trigger('change.select2');
                    $("#item_product_code").val('').trigger('change.select2');
                    $('#description').val('');
                    $('#store_location').val('');
                    $('#ordered_qty').val('');
                } else {

                    return false;
                }


            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });

    });

    window.ProductionProductsTable = $('#ProductionProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'production/get_production_products/' + $('#production_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'category', name: 'category'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'quantity', name: 'quantity'},
            {data: 'store_location', name: 'store_location'},
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });

    $('#ProductionProductsTable tbody').on('click', 'button.icon-edit.production-edit', function (e) {

        var data = ProductionProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#item_product_code").val(data['product'].id).trigger('change.select2');
        $('#description').val(data['description']);
        $("#store_location").val(data['store_location_id']).trigger('change.select2');
        $('#ordered_qty').val(data['quantity']);

        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');
        return false;
    });

    $('#ProductionProductsTable tbody').on('click', 'button.icon-circle-cross.production-delete', function () {
        var data = ProductionProductsTable.row($(this).parents('tr')).data();

        var params = {

            record_id: data['id'],
        };
        $.ajax({
            url: BASE + 'production/delete_production_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    ProductionProductsTable.ajax.reload();

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


    $("#cost_add_item_row_btn , #cost_update_item_row_btn").click(function (e) {

        if ($('#cost_type').val() == '') {
            return false;
        }
        var cost_details = $('#cost-details-panel :input').serialize();
        var params = {
            cost_details: cost_details,
            production_id: $('#production_id').val(),
            record_cost_id: $('#record_cost_update_id').val()
        };

        $.ajax({
            url: BASE + 'production/add_production_cost',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {

                    ProductionCostTable.ajax.url(BASE + 'production/get_production_cost/' + $('#production_id').val()).load();
                    $('#record_cost_update_id').val('');
                    $('#cost_update_item_row_btn').hide('slow');
                    $('#cost_add_item_row_btn').show('slow');
                    $("#cost_type").val('').trigger('change.select2');
                    $('#remarks').val('');
                    $('#amount').val('');

                } else {
                    return false;
                }


            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });


    });

    window.ProductionCostTable = $('#ProductionCostTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'production/get_production_cost/' + $('#production_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'cost_type_name', name: 'cost_type_name'},
            {data: 'description', name: 'description'},
            {data: 'cost', name: 'cost'},
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });

    $('#ProductionCostTable tbody').on('click', 'button.icon-edit.cost-edit', function (e) {

        var data = ProductionCostTable.row($(this).parents('tr')).data();
        $('#record_cost_update_id').val(data['id']);

        $("#cost_type").val(data['cost_type']).trigger('change.select2');
        // $("#item_product_code").val(data['product'].id).trigger('change.select2');
        $('#remarks').val(data['description']);
        $('#amount').val(data['cost']);
        $('#cost_add_item_row_btn').hide('slow');
        $('#cost_update_item_row_btn').show('slow');
        return false;
    });

    $('#ProductionCostTable tbody').on('click', 'button.icon-circle-cross.cost-delete', function () {
        var data = ProductionCostTable.row($(this).parents('tr')).data();

        var params = {

            record_id: data['id'],
        };
        $.ajax({
            url: BASE + 'production/delete_production_cost',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    ProductionCostTable.ajax.reload();

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


    $("#production-approve-btn").click(function (e) {

        if ($('#production_id').val() == '') {
            return false;
        }
        var btn = $(this);


        $.confirm({
            title: 'Approve Production',
            type: 'red',
            buttons: {
                delete: {
                    text: 'Approve',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-red',
                    action: function () {

                        btn.button('loading');
                        var params = {
                            date: $('#order_date_created').val(),
                            status: 'A'
                        };

                        e.preventDefault();
                        $.ajax({
                            url: BASE + 'production/' + $('#production_id').val(),
                            type: 'PUT',
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                notification(response);
                                btn.button('reset');
                                if (response.status == 'success') {

                                    if (response.production_details.status == 'A') {
                                        MakingProductsTable.ajax.url(BASE + 'production/get_making_products/' + $('#production_id').val()).load();
                                        ProductionProductsTable.ajax.url(BASE + 'production/get_production_products/' + $('#production_id').val()).load();

                                        $('#order_details_panel :input').prop("disabled", true);
                                        $('#cost-details-panel').hide('slow');
                                        $('#production-product-details-panel').hide('slow');
                                        $('#production-details-panel').hide('slow');
                                        $('#display_status').html(' <span class="label label-success">Approved</span>');
                                        $('#production-approve-btn').hide('slow');
                                    }


                                } else {


                                    return false;
                                }
                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                notificationError(xhr, ajaxOptions, thrownError);
                            }
                        });
                    }
                },
                close: function () {
                }
            }
        });
    });
});
