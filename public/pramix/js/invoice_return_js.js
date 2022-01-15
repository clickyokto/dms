$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";


    window.PendingInvoiceTable = $('#PendingInvoiceTable').DataTable({
        order: [[0, "desc"]],
        processing: true,
        serverSide: false,
        order: [[1, "desc"]],
        iDisplayLength: 15,
        data: [],
        bInfo: false,
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": true,
        "bInfo": false,
        "bAutoWidth": false,
        searching: false,
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



    $("#remarks").Editor();

    $('#update_item_row_btn').hide('slow');

    $('#create_new_customer_model').hide('slow');

    if ($('#invoice_return_id').val() != '') {

        $('#overlay').hide('slow');

        $("#customer_filter :input").attr("disabled", true);
        calPrice.run();

    } else {
        $('#generate_invoice_return_pdf').hide('slow');

    }
    if ($('#invoice_return_status').val() == '' || $('#invoice_return_status').val() == 'D') {
        $('#invoice_return_update_btn').hide('slow');

    } else {
        $('#invoice_return_save_btn').hide('slow');
        $('#invoice_return_save_and_new_btn').hide('slow');
    }

    $("#invoice_return_discount_type").change(function (e) {
        calPrice.run();
    });

    $("#invoice_return_discount").keyup(function (e) {
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

    $("#generate_invoice_return_pdf").click(function (e) {

        if ($('#invoice_return_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            invoice_return_id: $('#invoice_return_id').val(),
        };

        $.ajax({
            url: BASE + 'invoice_return/generate_pdf',
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


    var selected_item = new Option('Please select Item', '', true, true);
    $('#invoice_return_item_product_code').append(selected_item).trigger('change.select2');

    var selected_category = new Option('Please select category', '', true, true);
    $('#invoice_return_item_category_code').append(selected_category).trigger('change.select2');



    window.createInvoiceReturnCode = {
        run: function ()
        {
            var params = {
                customer_id: $("#customer_id").val(),
                invoice_id: $("#invoice_code_selected").val()
            };
            var url;
            var method;
            if ($('#invoice_return_id').val() != '') {
                url = BASE + 'invoice_return/' + $('#invoice_return_id').val();
                method = 'PUT';
            } else {
                url = BASE + 'invoice_return';
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
                        $("#invoice_return_id").val(response.invoice_return_details.id);
                        $("#ref_id").val(response.invoice_return_details.id);
                        $("#invoice_return_no").val(response.invoice_return_details.invoice_return_code);


                        if (response.invoice_return_details.status == 'D')
                            $('#display_status').html('<span class="label label-danger">Draft</span>');
                        else if (response.invoice_return_details.status == 'A')
                            $('#display_status').html('<span class="label label-success">Completed</span>');
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




    $('#invoice_return_item_category_code').change(function (e) {

        var params = {
            category_id: $("#invoice_return_item_category_code").val(),
            invoice_id: $("#invoice_code_selected").val(),
        };
        var method = '';
        var url = '';
        url = BASE + 'get_invoice_products_by_category';
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
                    $("#invoice_return_item_product_code").find('option').remove();
                    $.each(response.products, function () {
                        $("#invoice_return_item_product_code").append($("<option />").val(this.id).text(this.item_code));
                    });
                    var selectedproduct = new Option('Please select product', '', true, true);
                    $('#invoice_return_item_product_code').append(selectedproduct).trigger('change.select2');
                    $('#invoice_return_item_product_code').select2('open');
                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
    });

    $('#item_stock_id').change(function (e) {
        $('#invoice_return_item_product_code').val($('#item_stock_id').val()).trigger('change.select2');


    });

        $('#invoice_return_item_product_code').change(function (e) {

        if ($("#invoice_return_item_product_code").val() == '') {
            $('#invoice_return_item_category_code').val('').trigger('change.select2');
            $('#description').val('').tooltip('show');
            $('#quantity').val('').tooltip('show');
            $('#unit_price').val('').tooltip('show');
            $('#discount').val('').tooltip('show');
            return false;
        }


        var params = {
            invoice_id: $("#invoice_code_selected").val(),
            product_id: $("#invoice_return_item_product_code").val()
        };

        var method = '';
        var url = '';


        url = BASE + 'get_invoice_return_product_details';
        method = 'POST';

        e.preventDefault();
        $.ajax({
            url: url,
            type: method,
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                if (response.status == 'success') {

                    $('#item_stock_id').val($("#invoice_return_item_product_code").val()).trigger('change.select2');
                    $('#description').val(response.invoice_return_product_detail.description).tooltip('show');
                    $('#quantity').val(1).tooltip('show').focus();
                    $('#unit_price').val(response.invoice_return_product_detail.unit_price).tooltip('show');
                    $('#discount').val(response.invoice_return_product_detail.discount).tooltip('show');
                    $('#discount_type').val(response.invoice_return_product_detail.discount_type);


                    var tooltip = " <a href='#' class='close' data-dismiss='alert' aria-label='close'> </a>" +
                        "<div class='row'>" +
                        "<div class='col-sm-12'>" +
                        "<strong>" + response.invoice_return_product_detail.product.item_code + "</strong>" +
                        "</div>" +
                        "<div class='col-sm-9'>No.of Items Purchased : " + response.invoice_return_product_detail.qty + "</div>" +
                        "</div>";

                    $('#products_info_alert').show('slow');
                    $('#products_info_alert').html(tooltip);

                    // $("#invoice_return_item_product_code").attr('title', tooltip).tooltip('fixTitle').tooltip('show');


                } else {
                    notification(response);

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }

        });

    });


    $('#quantity ,  #description, #unit_price, #product_discount').keypress(function (e) {
        var key = e.which;
        if (key == 13)  // the enter key code
        {
            $('#add_item_row_btn').click();
            return false;
        }
    });

    $('#item_stock_id').change(function (e) {
        $('#invoice_return_item_product_code').val($(this).val());
        $('#invoice_return_item_product_code').change()
    });

        $("#add_item_row_btn , #update_item_row_btn").click(function (e) {

        if ($('#invoice_return_item_product_code').val() == 0) {
            return false;
        }

        var product_details = $('#product-details-card :input').serialize();
        var params = {
            product_details: product_details,
            invoice_return_id: $('#ref_id').val(),
            record_product_id: $('#record_product_update_id').val(),
            invoice_id :  $('#invoice_code_selected').val()
        };

        $.ajax({
            url: BASE + 'invoice_return/add_invoice_return_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    ProductsTable.ajax.url(BASE + 'invoice_return/get_invoice_return_products/' + $('#invoice_return_id').val()).load();

                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');

                    $("#invoice_return_item_category_code").val('').trigger('change.select2');
                    $("#invoice_return_item_product_code").val('').trigger('change.select2');
                    $('#checked_discarded').prop('checked', false);
                    $('#description').val('');
                    $('#quantity').val('');
                    $('#store_location').val('');
                    $('#unit_price').val('');
                    $('#product_discount').val('');

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




    });




    $('#ProductsTable tbody').on('click', 'button.icon-edit', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();

        $('#record_product_update_id').val(data['id']);

        $("#invoice_return_item_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#invoice_return_item_product_code").val(data['product'].id).trigger('change.select2');
        $('#description').val(data['description']);
        $('#quantity').val(data['quantity']);
        $('#unit_price').val(data['unit_price']);
        $('#product_discount').val(data['discount']);
        $("#store_location").val(data['store_id']).trigger('change.select2');
        $('#product_discount_type').val(data['discount_type']);
        if (data['stock_status'] == 'Discarded')
            $('#checked_discarded').prop('checked', true);
        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');
        return false;

    });


    $('#ProductsTable tbody').on('click', 'button.icon-circle-cross', function () {
        var data = ProductsTable.row($(this).parents('tr')).data();

        var invoice_price_details = $('#price_panel :input').serialize();

        var params = {
            invoice_price_details: invoice_price_details,
            record_id: data['id'],
        };

        $.ajax({
            url: BASE + 'invoice_return/delete_invoice_return_product',
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


    updateInvoiceReturn = {
        run: function (status, btn) {

            var return_from_invoices_selected = PendingInvoiceTable.column(0).checkboxes.selected();

            var return_from_invoices_array = [];

            $.each(return_from_invoices_selected, function (index, rowId) {
                return_from_invoices_array.push(rowId);
            });

            var params = {
                status: status,
                remarks: $('#remarks').Editor("getText"),
                customer_id: $('#customer_id').val(),
                invoice_id: $('#invoice_code_selected').val(),
                return_from_invoices_array: return_from_invoices_array

            };

            $.ajax({
                url: BASE + 'invoice_return/' + $('#invoice_return_id').val(),
                type: 'PUT',
                async: false,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {
                        ProductsTable.ajax.reload();
                        if (btn == 'invoice_return_save_and_new_btn') {
                            setTimeout(
                                function () {
                                    window.location.href = BASE + 'invoice_return/create';
                                }, 1000);
                        }
                        else if (btn == 'invoice_return_update_btn') {
                            setTimeout(
                                function () {
                                    window.location.href = BASE + 'invoice_return';
                                }, 1000);
                        }
                        else {
                            $('#settle_outstanding_invoices').show('slow');
                            if (status == 'A') {
                                $('#product-details-panel').hide('slow');
                                $('#customer_detail_panel :input').prop("disabled", true);
                                $('#price_panel :input').prop("disabled", true);
                                $('#invoice_return_details_panel :input').prop("disabled", true);
                                $('#invoice_return_update_btn').show('slow');
                                $('#invoice_return_save_btn').hide('slow');
                                $('#invoice_return_save_and_new_btn').hide('slow');
                                $('#display_status').html('<span class="label label-success">Completed</span>');
                        }
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

    $("#settle_outstanding_invoices").hide();

    $("#settle_outstanding_invoices").click(function (e) {

        if($('#customer_id_selected').val() != '')
        {
            var win = window.open(BASE + 'payment/create?customer_id='+$('#customer_id_selected').val(), '_blank');
            if (win) {
                //Browser has allowed it to be opened
                win.focus();
            } else {
                //Browser has blocked it
                alert('Please allow popups for this website');
            }
        }

    });


    $("#invoice_return_save_btn, #invoice_return_save_and_new_btn ,#invoice_return_update_btn").click(function (e) {

        var btn = $(this).attr("id");

        var valid = $("#invoice_return_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }
        if ($("#invoice_id").val() == '') {
            return false
        }

        if(btn == 'invoice_return_update_btn')
        {
            updateInvoiceReturn.run('A', btn);
            return false;
        }
        var invoice_return_save_confirm = $.confirm({
            title: 'Save Invoice Return',
            type: 'blue',
            buttons: {
                draft: {
                    text: 'Draft',
                    btnClass: 'btn-default',
                    action: function () {

                        updateInvoiceReturn.run('D', btn);
                    }
                },
                complete: {
                    text: 'Complete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-primary',
                    action: function () {
                        updateInvoiceReturn.run('A' , btn);

                    }
                },

            }
        });
        return false;


    });


    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'invoice_return/get_invoice_return_products/' + $('#invoice_return_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'quantity', name: 'quantity'},
            {data: 'unit_price', name: 'unit_price'},
            {data: 'discount', name: 'discount'},
            {data: 'discount_type_show', name: 'discount_type_show'},
            {data: 'discount_type', name: 'discount_type', 'bVisible': false},
            {data: 'sub_total', name: 'sub_total'},
            {data: 'stock_status', name: 'stock_status'},
            {
                data: 'actions',
                name: 'actions',
            }
        ]
    });

});
