$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";
    $("#remarks").Editor();

    $('#update_item_row_btn').hide('slow');
    $('#create_new_supplier_model').hide('slow');


    if ($('#grn_id').val() != '') {

        $('#overlay').hide('slow');
        $('#grn_save_btn').hide('slow');
        $('#grn_save_and_new_btn').hide('slow');

       $("#supplier_filter :input").attr("disabled", true);


    } else {
        $('#grn-update-btn').hide('slow');
        $("#grn-approve-btn").hide('slow');
        $('#generate_grn_pdf').hide('slow');

    }


    $("#grn_save_btn, #grn_save_and_new_btn ,#grn-update-btn").click(function (e) {

        var btn = $(this).attr("id");
        if ($("#grn_id").val() == '') {
            return false
        }
        var valid = $("#grn_form").validationEngine('validate');
        if (valid != true) {

            return false;
        }
if($('#grn_status').val() == 'A')
{
    updateGRN.run('A',btn);
    return false;
}


        var grn_save_confirm = $.confirm({
            title: 'Save GRN',
            type: 'blue',
            buttons: {
                draft: {
                    text: 'Draft',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-default',
                    action: function () {

                        updateGRN.run('D', btn);
                    }
                },
                complete: {
                    text: 'Complete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-primary',
                    action: function () {
                        updateGRN.run('A', btn);

                    }
                },

            }
        });
        return false;
    });





    updateGRN = {
        run: function (status, btn) {
            var params = {

                remarks: $('#remarks').Editor("getText"),
                status : status
            };

            disable_save_button_group.run();

alert(btn)
            $.ajax({
                url: BASE + 'grn/' + $('#grn_id').val(),
                type: 'PUT',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {

                        if (btn == 'grn_save_and_new_btn') {

                            setTimeout(
                                function () {
                                    window.location.href = BASE + 'grn/create';
                                }, 1000);
                        }
                        if (btn == 'grn_save_btn') {

                            $('#grn-update-btn').show('slow');
                            $('#grn_save_btn').hide('slow');
                            $('#grn_save_and_new_btn').hide('slow');
                            $('#generate_grn_pdf').show('slow');
                            $("#grn_code").val(response.grn.grn_code);

                            if(status == 'A')
                            {
                                $('#grn-details-card').hide('slow');

                                GRNProductsTable.ajax.reload()
                                $('#grn_status').val('A');

                            }
                        }
                        if (btn == 'grn-update-btn') {
                            setTimeout(
                                function () {
                                    window.location.href = BASE + 'grn';
                                }, 1000);
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

return false;
        }
    };




    $("#supplier_code_selected ,#supplier_name_selected").change(function (e) {
        if ($('#grn_id').val() != '') {
            return false;
        }

        var params = {
            supplier_id: this.value,
            purchase_order_id : $('#purchase_order_code_selected').val()
        };

        var url;
        var method;

        if ($('#grn_id').val() != '') {
            url = BASE + 'grn/' + $('#grn_id').val();
            method = 'PUT';
        } else {
            url = BASE + 'grn';
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
                    $("#grn_id").val(response.grn_details.id);
                    $("#ref_id").val(response.grn_details.id);
                    // CommentsListTable.ajax.url(BASE + 'get_comments_list/' + $('#ref_type').val() + '/' + $('#ref_id').val()).load();


                    if (response.grn_details.status == 'D')
                        $('#display_status').html('<span class="label label-danger">Draft</span>');
                    else if (response.grn_details.status == 'A')
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


    window.createGRNCode = {
        run: function () {
            var params = {

                supplier_id: $("#supplier_code_selected").val(),
                purchase_order_id : $('#purchase_order_code_selected').val()

            };
            var url;
            var method;
            if ($('#grn_id').val() != '') {
                url = BASE + 'grn/' + $('#grn_id').val();
                method = 'PUT';
            } else {
                url = BASE + 'grn';
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
                        $("#grn_id").val(response.grn_details.id);
                        $("#ref_id").val(response.grn_details.id);
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

    $('#quantity ,  #description, #unit_price, #product_discount').keypress(function (e) {
        var key = e.which;
        if (key == 13)  // the enter key code
        {
            $('#add_item_row_btn').click();
            return false;
        }
    });

    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {


        var product_details = $('#grn-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            grn_id: $('#grn_id').val(),
            record_product_id: $('#record_product_update_id').val()
        };

        $.ajax({
            url: BASE + 'grn/add_grn_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                notification(response);

                if (response.status == 'success') {
                    $('#grn_id').val(response.grn_details.id);
                    GRNProductsTable.ajax.url(BASE + 'grn/get_grn_products/' +  response.grn_details.id).load();

                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');
                    $("#supplier_filter :input").attr("disabled", true);
                    $('#products_info_alert').hide('slow');
                    $("#item_category_code").val('').trigger('change.select2');
                    $("#item_product_code").val('').trigger('change.select2');
                    $('#description').val('');
                    $('#unit_price').val('');
                    $('#ordered_qty').val('');
                    $('#store_location').val('');
                    $('#delivered_qty').val('');
                    $('#selling_price').val('');


                } else {
                    return false;
                }

               // calPrice.run();


            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });


    });

    window.GRNProductsTable = $('#GRNProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'grn/get_grn_products/' +  $('#grn_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'stock_id', name: 'stock_id'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'unit_price', name: 'unit_price'},
            {data: 'selling_price', name: 'selling_price'},
            {data: 'delivered_qty', name: 'delivered_qty'},
            {data: 'sub_total', name: 'sub_total'},
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });

    $('#GRNProductsTable tbody').on('click', '.grn-product-edit', function (e) {

        var data = GRNProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#item_product_code").val(data['product'].id).trigger('change.select2');
        $('#description').val(data['description']);
        $("#store_location").val(data['store_id']).trigger('change.select2');
        $('#unit_price').val(data['unit_price']);
        $('#ordered_qty').val(data['ordered_qty']);
        $('#delivered_qty').val(data['delivered_qty']);
        $('#selling_price').val(data['selling_price']);

        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');
        return false;
    });

    $('#GRNProductsTable tbody').on('click', 'button.icon-circle-cross.grn-delete', function () {
        var data = GRNProductsTable.row($(this).parents('tr')).data();

        var params = {

            record_id: data['id'],
        };
        $.ajax({
            url: BASE + 'grn/delete_grn_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    GRNProductsTable.ajax.reload();

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


    $("#generate_grn_pdf").click(function (e) {

        if ($('#grn_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            grn_id: $('#grn_id').val(),
        };
        $.ajax({
            url: BASE + 'grn/generate_pdf',
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


    $("#grn-approve-btn").click(function (e) {

        if ($('#grn_id').val() == '') {
            return false;
        }
        var btn = $(this);


        $.confirm({
            title: 'Approve GRN',
            type: 'red',
            buttons: {
                delete: {
                    text: 'Approve',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-red',
                    action: function () {

                        btn.button('loading');
                        var params = {
                            grn_id: $('#grn_id').val(),
                            supplier_id : $('#supplier_code_selected').val(),
                            remarks: $('#remarks').Editor("getText"),


                        };
                        $.ajax({
                            url: BASE + 'grn/approve_grn',
                            type: 'POST',
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'success') {
                                    notification(response);
                                    $('#display_status').html(' <span class="label label-success">Approved</span>');
                                    $('#grn-approve-btn').hide('slow');
                                    btn.button('reset');

                                } else {
                                    notification(response);
                                    btn.button('reset');
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



    $('#po_discount_type').change(function (e) {
        calPrice.run();
    });

    $('#invoice_discount').keyup(function (e) {
        calPrice.run();
    });

    $("#checked_vat").change(function (e) {
        calPrice.run();
    });

    $("#checked_nbt").change(function (e) {
        calPrice.run();
    });

});
