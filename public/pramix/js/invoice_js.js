$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $("#invoice_form").validationEngine();

    $("#paid").prop("readonly", true);

    $('#update_item_row_btn').hide('slow');

    $("#invoice_discount_type").change(function (e) {
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

    $("#invoice_discount").keyup(function (e) {
        calPrice.run();
    });

    $("#paid").keyup(function (e) {
        calPrice.run();
    });

    if ($('#invoice_id').val() != '') {
        $('#overlay').hide('slow');
        $('#quotation_code_selected').prop("disabled", true);
        $('#job_card_code_selected').prop("disabled", true);
        calPrice.run();
    } else {
        $('#generate_invoice_pdf').hide('slow');
        $('#generate_mail').hide('slow');
        $('#duplicate_invoice').hide('slow');
        calPrice.run();
    }

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
        var table_row_count = $("#ProductsTable tbody tr").length;
        if (table_row_count == 12) {
            $.notify(
                {
                    // options
                    message:
                        "Maximum count of product reached. Save Invoice and create new one",
                },
                {
                    // settings
                    z_index: 10000000000,
                    type: "danger",
                }
            );
        } else {
            var product_details = $('#product-details-panel :input').serialize();
            var params = {
                product_details: product_details,
                invoice_id: $('#invoice_id').val(),
                record_product_id: $('#record_product_update_id').val()
            };
            $.ajax({
                url: BASE + 'invoice/add_invoice_product',
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {
                        $('#products_info_alert').hide('slow');
                        ProductsTable.ajax.url(BASE + 'invoice/get_invoice_products/' + $('#invoice_id').val()).load();
                        $('#record_product_update_id').val('');
                        $('#update_item_row_btn').hide('slow');
                        $('#add_item_row_btn').show('slow');
                        calPrice.run();
                        $("#item_stock_id").val('').trigger('change');
                        $("#item_product_code").val('').trigger('change.select2');
                        $('#description').val('');
                        $('#quantity').val('');
                        $('#store_location').val('');
                        $('#unit_price').val('');
                        $('#product_discount').val('');
                        $('#item_stock_id').select2('open');
                    } else {
                        notification(response);
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });
        }
    });

    $("#view_credit_note").click(function (e) {
        window.grn_history_model = $.confirm({
            title: 'Credit Note History',
            draggable: true,
            boxWidth: '80%',
            closeIcon: true,
            useBootstrap: false,
            buttons: {
                close: function () {
                }
            },
            content: 'url:' + BASE + 'get_view_used_credit_note_model/' + $('#invoice_id').val(),
            onContentReady: function () {
            },
            columnClass: 'medium',
        });
        return false;
    });

    $("#generate_invoice_pdf").click(function (e) {
        if ($('#invoice_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');
        var params = {
            invoice_id: $('#invoice_id').val(),
        };
        $.ajax({
            url: BASE + 'invoice/generate_pdf',
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

    $("#duplicate_invoice").click(function (e) {
        if ($('#invoice_id').val() == '')
            return false;
        var $btn = $(this);
        $btn.button('loading');
        var params = {
            invoice_id: $('#invoice_id').val(),
        };
        $.ajax({
            url: BASE + 'invoice/duplicate_invoice',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {
                    window.location.href = BASE + 'invoice/' + response.invoice_no + '/edit';
                } else {
                }
            },
            error: function (errors) {
            }
        });
    });


    $('#recurring_type').change(function (e) {

        if ($("#recurring_type").val() == 'Y') {
            $('#recurring_date_div').show('slow');
            $('#recurring_month_div').show('slow');
        }
        if ($("#recurring_type").val() == 'M') {
            $('#recurring_month_div').hide('slow');

        }
        if ($("#recurring_type").val() == '') {
            $('#recurring_date_div').hide('slow');
            $('#recurring_month_div').hide('slow');

        }

    });

    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'invoice/get_invoice_products/' + $('#invoice_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'stock_id', name: 'stock_id'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'quantity', name: 'quantity'},
            {data: 'unit_price', name: 'unit_price'},
            {data: 'discount', name: 'discount'},
            {data: 'discount_type_show', name: 'discount_type_show'},
            {data: 'discount_type', name: 'discount_type', 'bVisible': false},
            {data: 'sub_total', name: 'sub_total'},
            {data: 'store_location', name: 'store_location', 'bVisible': false},
            {data: 'actions', name: 'actions'},
        ]
    });

    $('#ProductsTable tbody').on('click', '.invoice_product_edit_btn', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id);
        $('#item_category_code').trigger('change.select2');
        $("#item_product_code").val(data['product'].id);
        $('#item_product_code').trigger('change.select2');

        $("#item_stock_id").val(data['product'].id);
        $('#item_stock_id').trigger('change.select2');


        $('#description').val(data['description']);
        $('#quantity').val(data['quantity']);
        $('#unit_price').val(data['unit_price']);
        $("#store_location").val(data['store_id']).trigger('change.select2');
        $('#product_discount').val(data['discount']);
        $('#product_discount_type').val(data['discount_type']);
        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');


        return false;

    });

    $('#ProductsTable tbody').on('click', '.invoice_product_delete_btn', function () {

        var data = ProductsTable.row($(this).parents('tr')).data();
        var invoice_price_details = $('#price_panel :input').serialize();
        var params = {
            invoice_price_details: invoice_price_details,
            record_id: data['id'],
            invoice_id: $('#invoice_id').val()
        };
        $.ajax({
            url: BASE + 'invoice/delete_invoice_product',
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


    $('#invoice_status').change(function () {

        show_invoice_status.run();
    })


    $('#payment_details_card_form').hide();
    window.show_invoice_status = {

        run: function () {

            var params = {
                invoice_id: $('#invoice_id').val()
            };

            if ($('#invoice_id').val() == '') {

                $('#invoice-update-btn').hide('slow');
                $('#convert_ready_to_dispatch').hide('slow');
                $('#convert_to_invoice').hide('slow');
                return false;
            } else {
                $('#sales_invoice_save_btn').hide('slow');
                $('#invoice-save-and-new-btn').hide('slow');
            }

            var url = BASE + 'get_invoice_status';
            var method = 'POST';

            $.ajax({
                url: url,
                type: method,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {

                        var status = response.invoice_status_text;
                        if (response.invoice_status == 'O' && response.invoice_code == '') {
                            $('#sales_invoice_save_btn').show('slow');
                            $('#invoice-save-and-new-btn').show('slow');
                            $('#convert_to_invoice').hide('slow');
                            $('#convert_ready_to_dispatch').hide('slow');
                            $('#invoice-update-btn').hide('slow');
                            $('#generate_invoice_pdf').hide('slow');

                        } else if (response.invoice_status == 'O') {
                            $('#convert_to_invoice').hide('slow');
                            $('#convert_ready_to_dispatch').show('slow');
                        } else if (response.invoice_status == 'D') {
                            $('#convert_ready_to_dispatch').hide('slow');
                            $('#convert_to_invoice').show('slow');

                        } else if (response.invoice_status == 'I') {
                            $('#convert_to_invoice').hide('slow');
                            $('#convert_ready_to_dispatch').hide('slow');
                            $('#payment_details_card_form').show();
                        }
                        $('#invoice_status_label').text(status);
                        $('#invoice_type_label').text(status);

                    } else {
                        notification(response)
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });


        }
    };

    $('#invoice_status').trigger("change");
    window.createInvoice = {
        run: function () {
            var customer_id = $("#customer_id_selected").val();
            if (customer_id == '')
                customer_id = $("#customer_name_selected").val();
            if (customer_id == '')
                customer_id = $("#company_id_selected").val();
            var quick_sell = 0;
            if ($('#quick_sell').prop("checked") == true) {
                quick_sell = 1
            }
            var params = {
                customer_id: customer_id,
                project_id: $('#project_code_selected').val(),
                staff_id: $('#staff_member_id_selected').val(),
                quick_sell: quick_sell
            };
            var url;
            var method;
            if ($('#invoice_id').val() != '') {
                url = BASE + 'invoice/' + $('#invoice_id').val();
                method = 'PUT';
            } else {
                url = BASE + 'invoice';
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
                        $("#invoice_id").val(response.invoice_details.id);
                        $("#invoice_no").val(response.invoice_details.invoice_code);
                        $("#ref_id").val(response.invoice_details.id);
                        if (response.invoice_details.status == 'D')
                            $('#display_status').html('<span class="label label-danger">Draft</span>');
                        else if (response.invoice_details.status == 'A')
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

    $("#customer_id_selected ,  #customer_name_selected , #company_id_selected").change(function (e) {
        if ($('#invoice_id').val() != '') {
            return false;
        }
        createInvoice.run();
    });

    $("#quick_sell").change(function () {
        if ($('#invoice_id').val() != '') {
            return false;
        }
        if ($('#quick_sell').prop("checked") == true) {
            createInvoice.run();
            $('#payment_details_card_form').show('slow');
        }
    });

    if ($('#invoice_id').val() == '' || $('#quotation_id').val() == '') {
        var selected_quotation = new Option('Please select quotation', '', true, true);
        $('#quotation_code_selected').append(selected_quotation).trigger('change.select2');
    }

    if ($('#invoice_id').val() == '' || $('#job_card_id').val() == '') {
        var selected_job_card = new Option('Please select job card', '', true, true);
        $('#job_card_code_selected').append(selected_job_card).trigger('change.select2');
    }


    window.getQuotation = {
        run: function () {
            var params = {
                customer_id: $('#customer_id_selected').val()
            };
            var url = BASE + 'quotation/get_quotations_by_customer_id';
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {
                        $("#quotation_code_selected").find('option').remove();
                        $.each(response.quotations, function () {
                            $("#quotation_code_selected").append($("<option />").val(this.id).text(this.quotation_code));
                        });
                        var selected_quotation = new Option('Please select quotation', '', true, true);
                        $('#quotation_code_selected').append(selected_quotation).trigger('change.select2');
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

        }
    };

    window.getJobCard = {
        run: function () {

            var params = {
                customer_id: $('#customer_id_selected').val()
            };
            var url = BASE + 'job_card/get_job_card_by_customer_id';


            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {

                        $("#job_card_code_selected").find('option').remove();
                        $.each(response.job_card, function () {
                            $("#job_card_code_selected").append($("<option />").val(this.id).text(this.job_card_code));
                        });
                        var selected_job_card = new Option('Please select job card', '', true, true);
                        $('#job_card_code_selected').append(selected_job_card).trigger('change.select2');
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

        }
    };

    $("#quotation_code_selected").change(function (e) {
        if ($('#quotation_code_selected').val() == '') {
            return false;
        }

        var params = {
            quotation_id: $('#quotation_code_selected').val(),
            invoice_id: $('#invoice_id').val()
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

    $("#job_card_code_selected").change(function (e) {
        if ($('#job_card_code_selected').val() == '') {
            return false;
        }

        var params = {
            job_card_id: $('#job_card_code_selected').val(),
            invoice_id: $('#invoice_id').val()
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


    //    payment part
    $('#payment_update_item_btn').hide('slow');
    window.InvoicePaymentsTable = $('#paymentsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'invoice/get_sales_payments/' + $('#invoice_id').val(),
        bInfo: false,
        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'payment_code', name: 'payment_code'},
            {data: 'invoice_code', name: 'invoice_code', 'bVisible': false},
            {data: 'payment_date', name: 'payment_date'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'cheque_date', name: 'cheque_date'},
            {data: 'cheque_bank', name: 'cheque_bank'},
            {data: 'payment_ref_no', name: 'payment_ref_no'},
            {data: 'payment_remarks', name: 'payment_remarks'},
            {data: 'payment_amount', name: 'payment_amount'},
            {data: 'cheque_status', name: 'cheque_status'},
            {data: 'actions', name: 'actions', 'bVisible': false}
        ]
    });


    $('#paymentsTable tbody').on('click', 'button.payment-edit-button', function (e) {

        var data = InvoicePaymentsTable.row($(this).parents('tr')).data();

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

        var data = InvoicePaymentsTable.row($(this).parents('tr')).data();

        var payment_details = $('#payment-details-panel-form :input').serialize();

        var params = {
            payment_details: payment_details,
            invoice_id: $('#invoice_id').val(),
            record_payment_id: data['id']
        };
        $.ajax({
            url: BASE + 'invoice/delete_payment',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    InvoicePaymentsTable.ajax.reload();
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

        var data = InvoicePaymentsTable.row($(this).parents('tr')).data();

        var params = {
            invoice_id: $('#invoice_id').val(),
            record_payment_id: data['id']
        };

        $.ajax({
            url: BASE + 'invoice/print_payment',
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


        var params = {
            payment_details: $('#payment_details_card_form :input').serialize(),
            invoice_id: $('#invoice_id').val(),
            record_payment_id: $('#record_payment_update_id').val()
        };
        if ($('#invoice_id').val() != '') {
            $.ajax({
                url: BASE + 'invoice/save_payment',
                type: 'POST',
                dataType: 'JSON',
                async: false,
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {
                        InvoicePaymentsTable.ajax.url(BASE + 'invoice/get_sales_payments/' + $('#invoice_id').val()).load();
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


    updateInvoice = {
        run: function (status, confirm_remarks = '', btn) {
            var recurring_details = $('#recurring_panel :input').serialize();
            if ($('#quick_sell').prop('checked') === true) {
                var quick_sell = '1';
            } else {
                var quick_sell = '0';
            }
            var params = {
                status: status,
                remarks: $('#remarks').Editor("getText"),
                customer_id: $('#customer_id').val(),
                project_id: $('#project_code_selected').val(),
                invoice_due_date: $('#invoice_due_date').val(),
                staff_id: $('#staff_member_id_selected').val(),
                invoice_company: $('#INVOICE_COMPANY').val(),
                recurring_details: recurring_details,
                confirm_remarks: confirm_remarks,
                quick_sell: quick_sell,
                rep_id: $('#rep').val(),
                invoice_date_created: $('#invoice_date_created').val()
            };
            disable_save_button_group.run();
            $.ajax({
                url: BASE + 'invoice/' + $('#invoice_id').val(),
                type: 'PUT',
                async: false,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {
                        if (btn == 'invoice-save-and-new-btn') {
                            setTimeout(
                                function () {
                                    window.location.href = BASE + 'invoice/create';
                                }, 1000);
                        } else if (btn == 'invoice-update-btn') {
                            setTimeout(
                                function () {
                                    window.location.href = BASE + 'invoice?invoice_type=invoice';
                                }, 1000);
                        } else {
                            $('#generate_invoice_pdf').show('slow');
                            $('#generate_mail').show('slow');
                            $('#duplicate_invoice').show('slow');
                            $('#invoice-update-btn').show('slow');
                            $('#sales_invoice_save_btn, #invoice-save-and-new-btn').hide('slow');
                            if (response.invoice_details.status == 'I' || response.invoice_details.status == 'Q') {
                                ProductsTable.ajax.reload();
                                $('#customer_detail_panel :input').prop("disabled", true);
                                $('#invoice_details_panel :input').prop("disabled", true);
                               // $('#price_panel :input').prop("disabled", true);
                                $('#product-details-panel').hide('slow');
                                $('#invoice-update-btn').show('slow');
                                $('#sales_invoice_save_btn').hide('slow');
                                $('#invoice-save-and-new-btn').hide('slow');
                            }
                            $('#invoice_no').val(response.invoice_details.invoice_code);
                            CommentsListTable.ajax.reload();
                            show_invoice_status.run();
                        }
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

    $("#sales_invoice_save_btn, #invoice-save-and-new-btn ,#invoice-update-btn, #convert_ready_to_dispatch , #convert_to_invoice").click(function (e) {

        var btn = $(this).attr("id");

        var valid = $("#invoice_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }
        if ($("#invoice_id").val() == '') {
            return false
        }

        var title = '';
        var status = '';
        if (btn == 'sales_invoice_save_btn' || btn == 'invoice-save-and-new-btn') {
            title = 'Save order';
            status = 'O';
        }
        if (btn == 'invoice-update-btn') {
            title = 'Update order';
            status = 'U';
        }
        if (btn == 'convert_ready_to_dispatch') {
            status = 'D';
            title = 'Create Ready to dispatch';
        }
        if (btn == 'convert_to_invoice') {
            status = 'I';
            title = 'Create Invoice';
        }
        $.confirm({
            title: title,
            type: 'red',
            content: '' +
                '<form action="" class="formName">' +
                '<div class="form-group">' +
                '<label>Enter something here</label>' +
                '<input type="text" placeholder="remarks" class="confirm_remarks form-control" required />' +
                '</div>' +
                '</form>',
            buttons: {
                confirm: {
                    text: 'Confirm',
                    btnClass: 'btn-red',
                    action: function () {
                        var remarks = this.$content.find('.confirm_remarks').val();
                        updateInvoice.run(status, remarks, btn);
                    }
                },
                close: function () {
                }
            }
        });
        return false;
    });


});
