$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $('#update_item_row_btn').hide('slow');
    $('#create_new_customer_model').hide('slow');



    if ($('#aod_id').val() != '') {

        $('#overlay').hide('slow');
        $('#aod_save_btn').hide('slow');
       $("#customer_filter :input").attr("disabled", true);


    } else {
        $('#aod-update-btn').hide('slow');
        $("#aod-approve-btn").hide('slow');
        $('#generate_aod_pdf').hide('slow');


    }



    $("#aod_save_btn, #aod-update-btn").click(function (e) {

        var btn = $(this).attr("id");
        if ($("#aod_id").val() == '') {
            return false
        }
        var valid = $("#aod_form").validationEngine('validate');
        if (valid != true) {

            return false;
        }

        var params = {

            reason: $('#aod_reason').val(),
            customer_id: $('#customer_id_selected').val(),
        };

        e.preventDefault();
        $.ajax({
            url: BASE + 'aod/' + $('#aod_id').val(),
            type: 'PUT',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                    $('#aod-update-btn').show('slow');
                    $('#aod_save_btn').hide('slow');
                    $('#generate_aod_pdf').show('slow');
                    if(btn == 'aod_save_btn')
                    $("#aod-approve-btn").show('slow');

                } else {
                    notification(response);
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

    $("#customer_id_selected ,#customer_name_selected").change(function (e) {
        if ($('#aod_id').val() != '') {
            return false;
        }

        var params = {
            customer_id: this.value,
        };

        var url;
        var method;

        if ($('#aod_id').val() != '') {
            url = BASE + 'aod/' + $('#aod_id').val();
            method = 'PUT';
        } else {
            url = BASE + 'aod';
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
                    $("#aod_id").val(response.aod_details.id);
                    $("#aod_code").val(response.aod_details.aod_code);
                    $("#ref_id").val(response.aod_details.id);
                    // CommentsListTable.ajax.url(BASE + 'get_comments_list/' + $('#ref_type').val() + '/' + $('#ref_id').val()).load();


                    if (response.aod_details.status == 'D')
                        $('#display_status').html('<span class="label label-danger">Draft</span>');
                    else if (response.aod_details.status == 'A')
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

    $("#add_item_row_btn , #update_item_row_btn").click(function (e) {

        if ($('#item_category_code').val() == 0) {
            return false;
        }
        var product_details = $('#aod-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            aod_id: $('#aod_id').val(),
            record_product_id: $('#record_product_update_id').val()
        };

        $.ajax({
            url: BASE + 'aod/add_aod_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                AODProductsTable.ajax.url(BASE + 'aod/get_aod_products/' +  $('#aod_id').val()).load();

                $('#record_product_update_id').val('');
                $('#update_item_row_btn').hide('slow');
                $('#add_item_row_btn').show('slow');
                $("#customer_filter :input").attr("disabled", true);
                $('#products_info_alert').hide('slow');


            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        $("#item_category_code").val('').trigger('change.select2');
        $("#item_product_code").val('').trigger('change.select2');
        $('#description').val('');
        $('#model_no').val('');
        $('#serial_no').val('');
        $('#qty').val('');

    });

    window.AODProductsTable = $('#AODProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'aod/get_aod_products/' +  $('#aod_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},

            {data: 'category', name: 'category'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'model_no', name: 'model_no'},
            {data: 'serial_no', name: 'serial_no'},
            {data: 'qty', name: 'qty'},
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });

    $('#AODProductsTable tbody').on('click', 'button.icon-edit.aod-edit', function (e) {

        var data = AODProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#item_product_code").val(data['product'].id).trigger('change.select2');
        $('#description').val(data['description']);

        $('#model_no').val(data['model_no']);
        $('#serial_no').val(data['serial_no']);
        $('#qty').val(data['qty']);

        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');
        return false;
    });

    $('#AODProductsTable tbody').on('click', 'button.icon-circle-cross.aod-delete', function () {
        var data = AODProductsTable.row($(this).parents('tr')).data();

        var params = {

            record_id: data['id'],
        };
        $.ajax({
            url: BASE + 'aod/delete_aod_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    AODProductsTable.ajax.reload();

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


    $("#generate_aod_pdf").click(function (e) {

        if ($('#aod_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            aod_id: $('#aod_id').val(),
        };
        $.ajax({
            url: BASE + 'aod/generate_pdf',
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


    $("#aod-approve-btn").click(function (e) {

        if ($('#aod_id').val() == '') {
            return false;
        }
        var btn = $(this);


        $.confirm({
            title: 'Approve AOD',
            type: 'red',
            buttons: {
                delete: {
                    text: 'Approve',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-red',
                    action: function () {

                        btn.button('loading');
                        var params = {
                            aod_id: $('#aod_id').val(),
                            customer_id : $('#customer_id_selected').val(),
                            reason: $('#aod_reason').val(),


                        };
                        $.ajax({
                            url: BASE + 'aod/approve_aod',
                            type: 'POST',
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'success') {
                                    notification(response);
                                    $('#display_status').html(' <span class="label label-success">Approved</span>');
                                    $('#aod-approve-btn').hide('slow');
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
});
