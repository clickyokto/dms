

$(document).ready(function () {



    $("#create_product_form").validationEngine();

    if ($('#product_id').val() != '') {
        $('#product-save-btn').hide('slow');
        $('#product-save-and-new').hide('slow');
        $('#qty_on_hand').prop( "disabled", true );

    } else {
        $('#product-update-btn').hide('slow');

    }

    if ($('#product_type') != '') {
        if ($('#product_type').val() == 'service') {
            $('#measurement-panel').hide('slow');
            $('#inventory-panel').hide('slow');
            $('#stock_reorder_details').hide('slow');

        } else if ($('#product_type').val() == 'stock') {
            $('#measurement-panel').show('slow');
            $('#inventory-panel').show('slow');
            $('#stock_reorder_details').show('slow');

        } else if ($('#product_type').val() == 'non_stock') {
            $('#measurement-panel').show('slow');
            $('#inventory-panel').hide('slow');
            $('#stock_reorder_details').hide('slow');

        } else if ($('#product_type').val() == 'production') {
            $('#measurement-panel').show('slow');
            $('#inventory-panel').hide('slow');
            $('#stock_reorder_details').hide('slow');

        }

    }

    if ($('#discount_id').val() != '') {
        $('#product_discount_form').hide('slow');
    }

    $("#btn_discount_edit").click(function () {
        $('#product_discount_form').show('slow');
        return false;
    });

    $("#product-save-btn , #product-update-btn ,#product-save-and-new").click(function () {
        var valid = $("#create_product_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        var btn = $(this).attr("id");
        var params = {
            basic_details: $('#product-basic-details-panel :input').serialize(),
            inventory_details: $('#inventory-panel :input').serialize(),
            cost_price: $('#cost-and-price-panel :input').serialize(),
            storage_info: $('#storage_info_panel :input').serialize(),
            measurement_details: $('#measurement-panel :input').serialize(),
            product_discount: $('#product-discount-panel :input').serialize(),
            product_pictures : $('#product-pic-panel :input').serialize()
        };
        var method = '';
        var url = '';
        if ($('#product_id').val() != '') {
            method = 'PUT';
            url = BASE + 'product/' + $('#product_id').val();
        } else {
            url = BASE + 'product';
            method = 'POST';
        }
        disable_save_button_group.run();
        $.ajax({
            url: url,
            type: method,
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                if (response.status == 'success') {

                    notification(response);

                    if ($('#isajax').val() == 1) {

                        $("#item_product_code").append('<option value="' + response.id + '" selected>' + response.item_code + '</option>');
                        $('#item_product_code').val(response.id).trigger('change');


                        product_model.close()
                    }

                    if (btn == 'product-save-and-new') {

                        setTimeout(
                            function () {
                                window.location.href = BASE + 'product/create';
                            }, 1000);
                    }
                    if (btn == 'product-save-btn') {

                        $('#product-save-btn').hide('slow');
                        $('#product-save-and-new').hide('slow');
                        $('#product-update-btn').show('slow');
                        $('#product_id').val(response.id);

                    }
                    if (btn == 'product-update-btn') {
                        setTimeout(
                            function () {
                                window.location.href = BASE + 'product';
                            }, 1000);

                    }
                } else {
                    notification(response);

                }
                enable_save_button_group.run();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                enable_save_button_group.run();
                notificationError(xhr, ajaxOptions, thrownError);
            }
        });

        return false;
    });


    $("#product_type").change(function (e) {

        if ($(this).val() == 'service') {
            $('#measurement-panel').hide(1000);
            $('#inventory-panel').hide(1000);
            $('#stock_reorder_details').hide(1000);
            $('#storage_info_panel').hide(1000);

        } else if ($(this).val() == 'stock') {
            $('#measurement-panel').show(1000);
            $('#inventory-panel').show(1000);
            $('#stock_reorder_details').show(1000);
            $('#storage_info_panel').show(1000);

        } else if ($(this).val() == 'non_stock') {
            $('#measurement-panel').show(1000);
            $('#inventory-panel').hide(1000);
            $('#stock_reorder_details').hide(1000);
            $('#storage_info_panel').hide(1000);


        } else if ($(this).val() == 'production') {
            $('#measurement-panel').show(1000);
            $('#inventory-panel').hide(1000);
            $('#storage_info_panel').hide(1000);

        }
    });

    $("#product-movement-history-btn").click(function (e) {
        window.product_movement_history = $.confirm({
            title: '',
            draggable: true,
            boxWidth: '80%',
            closeIcon: true,
            useBootstrap: false,
            buttons: {

                close: function () {
                }
            },
            content: 'url:' + BASE + 'product/product_movement_history/' + $('#product_id').val(),
            onContentReady: function () {

            },
            columnClass: 'medium',
        });
    });






});





