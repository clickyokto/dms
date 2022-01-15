$(document).ready(function () {

    document.getElementById("overlay").style.display = "block";

    $("#remarks").Editor();

    $("#delivery_note_form").validationEngine();

    $('#update_item_row_btn').hide('slow');


    if ($('#delivery_note_status').val() == '' || $('#delivery_note_status').val() == 'D') {
        $('#delivery_note-update-btn').hide('slow');
    } else {
        $('#sales_delivery_note_save_btn').hide('slow');
    }

    if ($('#delivery_note_id').val() != '') {

        $('#overlay').hide('slow');

    } else {
        $('#generate_delivery_note_pdf').hide('slow');
        $('#generate_mail').hide('slow');
        $('#duplicate_delivery_note').hide('slow');
    }


    $('#quantity ,  #description').keypress(function (e) {
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
            delivery_note_id: $('#delivery_note_id').val(),
            record_product_id: $('#record_product_update_id').val()
        };

        $.ajax({
            url: BASE + 'delivery_note/add_delivery_note_product',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    $('#products_info_alert').hide('slow');
                    ProductsTable.ajax.url(BASE + 'delivery_note/get_delivery_note_products/' + $('#delivery_note_id').val()).load();

                    $('#record_product_update_id').val('');
                    $('#update_item_row_btn').hide('slow');
                    $('#add_item_row_btn').show('slow');

                    $("#item_category_code").val('').trigger('change');
                    $("#item_product_code").val('').trigger('change.select2');
                    $('#description').val('');
                    $('#quantity').val('');

                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });


    });


    $("#generate_delivery_note_pdf").click(function (e) {

        if ($('#delivery_note_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        $btn.button('loading');

        var params = {
            delivery_note_id: $('#delivery_note_id').val(),
        };

        $.ajax({
            url: BASE + 'delivery_note/generate_pdf',
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


    $("#duplicate_delivery_note").click(function (e) {

        if ($('#delivery_note_id').val() == '')
            return false;

        var $btn = $(this);
        $btn.button('loading');

        var params = {
            delivery_note_id: $('#delivery_note_id').val(),
        };

        $.ajax({
            url: BASE + 'delivery_note/duplicate_delivery_note',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {

                    window.location.href = BASE + 'delivery_note/' + response.delivery_note_no + '/edit';

                } else {


                }
            },
            error: function (errors) {


            }
        });
    });

    window.ProductsTable = $('#ProductsTable').DataTable({
        searching: false,
        paging: false,
        responsive: true,
        "ordering": false,
        "destroy": true,
        ajax: BASE + 'delivery_note/get_delivery_note_products/' + $('#delivery_note_id').val(),
        bInfo: false,

        iDisplayLength: 5,
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'category', name: 'category'},
            {data: 'item', name: 'item'},
            {data: 'description', name: 'description'},
            {data: 'quantity', name: 'quantity'},
            {data: 'actions', name: 'actions'},
        ]
    });

    $('#ProductsTable tbody').on('click', '.delivery_note_product_edit_btn', function (e) {

        var data = ProductsTable.row($(this).parents('tr')).data();
        $('#record_product_update_id').val(data['id']);
        $("#item_category_code").val(data['product'].category_id).trigger('change.select2');
        $("#item_product_code").val(data['product'].id).trigger('change.select2');
        $('#description').val(data['description']);
        $('#quantity').val(data['quantity']);
        $('#add_item_row_btn').hide('slow');
        $('#update_item_row_btn').show('slow');
        return false;
    });

    $('#ProductsTable tbody').on('click', '.delivery_note_product_delete_btn', function () {

        var data = ProductsTable.row($(this).parents('tr')).data();
        var delivery_note_price_details = $('#price_panel :input').serialize();
        var params = {
            delivery_note_price_details: delivery_note_price_details,
            record_id: data['id'],
            delivery_note_id: $('#delivery_note_id').val()
        };
        $.ajax({
            url: BASE + 'delivery_note/delete_delivery_note_product',
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

    window.createDeliveryNote = {
        run: function () {
            var invoice_id = $("#invoice_code_selected").val();

            var params = {
                invoice_id: invoice_id
            };
            var url;
            var method;

            if ($('#delivery_note_id').val() != '') {
                url = BASE + 'delivery_note/' + $('#delivery_note_id').val();
                method = 'PUT';

            } else {
                url = BASE + 'delivery_note';
                method = 'POST';
            }

            $.ajax({
                url: url,
                type: method,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {

                        $('#overlay').hide('slow');

                        $("#delivery_note_id").val(response.delivery_note_details.id);
                        $("#delivery_note_no").val(response.delivery_note_details.delivery_note_code);

                        if (response.delivery_note_details.status == 'D')
                            $('#display_status').html('<span class="label label-danger">Draft</span>');
                        else if (response.delivery_note_details.status == 'A')
                            $('#display_status').html('<span class="label label-success">Completed</span>');

                        if ($("#customer_name_selected").val() == '') {
                            changeCustomer.run(response.delivery_note_details.customer_id, '', '');
                        }
                        ProductsTable.ajax.url(BASE + 'delivery_note/get_delivery_note_products/' + $('#delivery_note_id').val()).load();
                    } else {

                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });

        }
    }

    $("#invoice_code_selected").change(function (e) {
        if ($('#delivery_note_id').val() != '') {
            return false;
        }

        createDeliveryNote.run();

    });

    if ($('#delivery_note_id').val() == '' || $('#quotation_id').val() == '') {
        var selected_quotation = new Option('Please select quotation', '', true, true);
        $('#quotation_code_selected').append(selected_quotation).trigger('change.select2');
    }

    if ($('#delivery_note_id').val() == '' || $('#job_card_id').val() == '') {
        var selected_job_card = new Option('Please select job card', '', true, true);
        $('#job_card_code_selected').append(selected_job_card).trigger('change.select2');
    }


    // window.getQuotation = {
    //     run: function () {
    //
    //         var params = {
    //             customer_id: $('#customer_id_selected').val()
    //         };
    //         var url = BASE + 'quotation/get_quotations_by_customer_id';
    //
    //
    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             dataType: 'JSON',
    //             data: $.param(params),
    //             success: function (response) {
    //                 if (response.status == 'success') {
    //
    //                     $("#quotation_code_selected").find('option').remove();
    //                     $.each(response.quotations, function () {
    //                         $("#quotation_code_selected").append($("<option />").val(this.id).text(this.quotation_code));
    //                     });
    //                     var selected_quotation = new Option('Please select quotation', '', true, true);
    //                     $('#quotation_code_selected').append(selected_quotation).trigger('change.select2');
    //                 } else {
    //                     notification(response);
    //                     return false;
    //                 }
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //
    //                 notificationError(xhr, ajaxOptions, thrownError);
    //             }
    //         });
    //
    //         return false;
    //
    //     }
    // };


    //    payment part
    // $('#payment_update_item_btn').hide('slow');
    //
    //
    // window.DeliveryNotePaymentsTable = $('#paymentsTable').DataTable({
    //     searching: false,
    //     paging: false,
    //     responsive: true,
    //     "ordering": false,
    //     "destroy": true,
    //     ajax: BASE + 'delivery_note/get_sales_payments/' + $('#delivery_note_id').val(),
    //     bInfo: false,
    //     iDisplayLength: 5,
    //     columns: [
    //         {data: 'id', name: 'id', 'bVisible': false},
    //         {data: 'payment_date', name: 'payment_date'},
    //         {data: 'payment_method', name: 'payment_method'},
    //         {data: 'payment_ref_no', name: 'payment_ref_no'},
    //         {data: 'payment_remarks', name: 'payment_remarks'},
    //         {data: 'payment_amount', name: 'payment_amount'},
    //         {data: 'actions',name: 'actions',}
    //     ]
    // });
    //
    //
    // $('#paymentsTable tbody').on('click', 'button.payment-edit-button', function (e) {
    //
    //     var data = DeliveryNotePaymentsTable.row($(this).parents('tr')).data();
    //
    //     $('#record_payment_update_id').val(data['id']);
    //     $('#payment_date').val(data['payment_date']);
    //     $("#payment_method").val(data['payment_method']);
    //     $('#payment_ref_no').val(data['payment_ref_no']);
    //     $('#payment_remarks').val(data['payment_remarks']);
    //     $('#payment_amount').val(data['payment_amount']);
    //     $('#payment_add_item_btn').hide('slow');
    //     $('#payment_update_item_btn').show('slow');
    //     return false;
    //
    // });
    //
    // $('#paymentsTable tbody').on('click', 'button.payment-delete-button', function (e) {
    //
    //     var data = DeliveryNotePaymentsTable.row($(this).parents('tr')).data();
    //
    //     var payment_details = $('#payment-details-panel-form :input').serialize();
    //
    //     var params = {
    //         payment_details: payment_details,
    //         delivery_note_id: $('#delivery_note_id').val(),
    //         record_payment_id: data['id']
    //     };
    //     $.ajax({
    //         url: BASE + 'delivery_note/delete_payment',
    //         type: 'POST',
    //         dataType: 'JSON',
    //         data: $.param(params),
    //         success: function (response) {
    //             if (response.status == 'success') {
    //
    //                 DeliveryNotePaymentsTable.ajax.reload();
    //             } else {
    //                 notification(response);
    //                 return false;
    //             }
    //         },
    //         error: function (xhr, ajaxOptions, thrownError) {
    //
    //             notificationError(xhr, ajaxOptions, thrownError);
    //         }
    //     });
    //     return false;
    // });
    //
    // $('#paymentsTable tbody').on('click', 'button.payment-print-button', function (e) {
    //     var $btn = $(this);
    //     $btn.button('loading');
    //
    //     var data = DeliveryNotePaymentsTable.row($(this).parents('tr')).data();
    //
    //     var params = {
    //         delivery_note_id: $('#delivery_note_id').val(),
    //         record_payment_id: data['id']
    //     };
    //
    //     $.ajax({
    //         url: BASE + 'delivery_note/print_payment',
    //         type: 'POST',
    //         dataType: 'JSON',
    //         data: $.param(params),
    //         success: function (response) {
    //             if (response.status == 'success') {
    //                 window.open(response.url);
    //                 $btn.button('reset');
    //
    //             } else {
    //                 notification(response);
    //                 $btn.button('reset');
    //                 return false;
    //             }
    //         },
    //         error: function (xhr, ajaxOptions, thrownError) {
    //
    //             notificationError(xhr, ajaxOptions, thrownError);
    //         }
    //     });
    //
    //     return false;
    // });
    //
    //
    // $("#payment_add_item_btn , #payment_update_item_btn").click(function (e) {
    //     if ($('#payment_amount').val() == '') {
    //         return false;
    //     }
    //
    //     var payment_details = $('#payment-details-panel-form :input').serialize();
    //
    //     var params = {
    //         payment_details: payment_details,
    //         delivery_note_id: $('#delivery_note_id').val(),
    //         record_payment_id: $('#record_payment_update_id').val()
    //     };
    //     if ($('#delivery_note_id').val() != '') {
    //         $.ajax({
    //             url: BASE + 'delivery_note/save_payment',
    //             type: 'POST',
    //             dataType: 'JSON',
    //             async: false,
    //             data: $.param(params),
    //             success: function (response) {
    //
    //                 DeliveryNotePaymentsTable.ajax.url(BASE + 'delivery_note/get_sales_payments/' + $('#delivery_note_id').val()).load();
    //
    //                 $('#record_payment_update_id').val('');
    //                 $('#payment_update_item_btn').hide('slow');
    //                 $('#payment_add_item_btn').show('slow');
    //             },
    //             error: function (xhr, ajaxOptions, thrownError) {
    //
    //                 notificationError(xhr, ajaxOptions, thrownError);
    //             }
    //         });
    //     }
    //     $('#payment_ref_no').val('');
    //     $('#payment_remarks').val('');
    //     $('#payment_amount').val('');
    //
    //
    // });

    $("#item_product_code").tooltip({
        trigger: "hover",
        html: true,
        placement: "top"
    });

    $("#view_invoice").click(function (e) {

        window.location.href = BASE + 'invoice/' + $('#invoice_id').val() + '/edit';
    });


    updateDeliveryNote = {
        run: function (status) {

            var params = {
                status: status,
                remarks: $('#remarks').Editor("getText"),
            };


            $.ajax({
                url: BASE + 'delivery_note/' + $('#delivery_note_id').val(),
                type: 'PUT',
                async: false,
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    notification(response);
                    if (response.status == 'success') {

                        $('#generate_delivery_note_pdf').show('slow');
                        $('#generate_mail').show('slow');

                        if (status == 'A') {

                            $('#customer_detail_panel :input').prop("disabled", true);
                            $('#delivery_note_details_panel :input').prop("disabled", true);
                            $('#product-details-panel').hide('slow');
                            $('#delivery_note-update-btn').show('slow');
                            $('#sales_delivery_note_save_btn').hide('slow');
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


    $("#sales_delivery_note_save_btn, #delivery_note-update-btn").click(function (e) {

        var btn = $(this).attr("id");

        var valid = $("#delivery_note_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }
        if ($("#delivery_note_id").val() == '') {
            return false
        }

        if (btn == 'delivery_note-update-btn') {
            updateDeliveryNote.run('A');
            return false;
        }

        var delivery_note_save_confirm = $.confirm({
            title: 'Save Delivery Note',
            type: 'blue',
            buttons: {
                draft: {
                    text: 'Draft',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-default',
                    action: function () {

                        updateDeliveryNote.run('D');
                    }
                },
                complete: {
                    text: 'Complete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-primary',
                    action: function () {
                        updateDeliveryNote.run('A');

                    }
                },

            }
        });
        return false;


    });


});
