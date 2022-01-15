$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $("#mrf_form").validationEngine();

    $('#update_item_row_btn').hide('slow');


    if ($('#mrf_status').val() == '' || $('#mrf_status').val() == 'D') {
        $('#mrf_update_btn').hide('slow');
    } else {
        $('#mrf_save_btn').hide('slow');
    }

    if ($('#mrf_id').val() != '') {
        $('#overlay').hide('slow');

    } else {
        $('#generate_mrf_pdf').hide('slow');
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
        var product_details = $('#product-details-panel :input').serialize();
        var params = {
            product_details: product_details,
            mrf_id: $('#mrf_id').val(),
            record_product_id: $('#record_product_update_id').val()
        };
        $.ajax({
            url: BASE + 'mrf/add_mrf_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    $('#products_info_alert').hide('slow');
                    ProductsTable.ajax.url(BASE + 'mrf/get_mrf_products/' + $('#mrf_id').val()).load();

                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');
                    $("#item_category_code").val('').trigger('change');
                    $("#item_product_code").val('').trigger('change.select2');
                    $('#description').val('');
                    $('#requested_qty').val('');
                    $('#issued_qty').val('');

                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });


    });

    $("#generate_mrf_pdf").click(function (e) {

        if ($('#mrf_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            mrf_id: $('#mrf_id').val(),
        };

        $.ajax({
            url: BASE + 'mrf/generate_pdf',
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

    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'mrf/get_mrf_products/' + $('#mrf_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'category', name: 'category'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'requested_qty', name: 'requested_qty'},
            {data: 'issued_qty', name: 'issued_qty'},
            {data: 'actions', name: 'actions'},
        ]
    });

    $('#ProductsTable tbody').on('click', '.mrf_product_edit_btn', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id);
        $('#item_category_code').trigger('change.select2');
        $("#item_product_code").val(data['product'].id);
        $('#item_product_code').trigger('change.select2');
        $('#description').val(data['description']);
        $('#requested_qty').val(data['requested_qty']);
        $('#issued_qty').val(data['issued_qty']);
        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');


        return false;

    });

    $('#ProductsTable tbody').on('click', '.mrf_product_delete_btn', function () {

        var data = ProductsTable.row($(this).parents('tr')).data();
        var mrf_price_details = $('#price_panel :input').serialize();
        var params = {
            mrf_price_details: mrf_price_details,
            record_id: data['id'],
            mrf_id: $('#mrf_id').val()
        };
        $.ajax({
            url: BASE + 'mrf/delete_mrf_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    ProductsTable.ajax.reload();

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

    window.createMRF = {
        run: function () {
            var customer_id = $("#customer_id_selected").val();
            if (customer_id == '')
                customer_id = $("#customer_name_selected").val();
            var params = {
                customer_id: customer_id,
                project_id: $('#project_code_selected').val()
            };

            var url;
            var method;

            if ($('#mrf_id').val() != '') {
                url = BASE + 'mrf/' + $('#mrf_id').val();
                method = 'PUT';

            } else {
                url = BASE + 'mrf';
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
                        $("#mrf_id").val(response.mrf_details.id);
                        $("#mrf_no").val(response.mrf_details.mrf_code);
                        $("#ref_id").val(response.mrf_details.id);

                        if (response.mrf_details.status == 'D')
                            $('#display_status').html('<span class="label label-danger">Draft</span>');
                        else if (response.mrf_details.status == 'A')
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

    $("#customer_id_selected ,  #customer_name_selected").change(function (e) {
        if ($('#mrf_id').val() != '') {
            return false;
        }
        createMRF.run();
    });

    $("#item_product_code").tooltip({
        trigger: "hover",
        html: true,
        placement: "top"
    });

    updateMRF = {
        run: function (status) {

            var params = {
                status: status,
                remarks: $('#remarks').Editor("getText"),
                customer_id: $('#customer_id').val(),
                project_id: $('#project_code_selected').val(),
                mrf_due_date: $('#mrf_due_date').val()
            };


            $.ajax({
                url: BASE + 'mrf/' + $('#mrf_id').val(),
                type: 'PUT',
                async: false,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {

                        $('#generate_mrf_pdf').show('slow');
                        $('#duplicate_mrf').show('slow');

                        if (status == 'A') {

                            $('#customer_detail_panel :input').prop("disabled", true);
                            $('#mrf_details_panel :input').prop("disabled", true);
                            $('#price_panel :input').prop("disabled", true);
                            $('#product-details-panel').hide('slow');
                            $('#mrf_update_btn').show('slow');
                            $('#mrf_save_btn').hide('slow');
                            $('#display_status').html('<span class="label label-success">Completed</span>');
                        }
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


    $("#mrf_save_btn, #mrf_update_btn").click(function (e) {

        var btn = $(this).attr("id");
        var valid = $("#mrf_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }
        if ($("#mrf_id").val() == '') {
            return false
        }

        if (btn == 'mrf_update_btn') {
            updateMRF.run('A');
            return false;
        }

        var mrf_save_confirm = $.confirm({
            title: 'Save MRF',
            type: 'blue',
            buttons: {
                draft: {
                    text: 'Draft',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-default',
                    action: function () {

                        updateMRF.run('D');
                    }
                },
                complete: {
                    text: 'Complete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-primary',
                    action: function () {
                        updateMRF.run('A');

                    }
                },

            }
        });
        return false;
    });
});
