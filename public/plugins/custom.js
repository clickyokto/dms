// scrollUp full options
function notification(response) {

    var msg_type;

    if (response.status == 'error') {
        msg_type = 'danger';
    } else {
        msg_type = 'success';
    }

    if (response.msg != undefined) {

        $.notify({
            // options
            message: response.msg
        }, {
            // settings
            z_index: 10000000000,
            type: msg_type,

        });
    } else if (response.errors != undefined) {

        $.each(response.errors, function (key, value) {
            $.notify({
                // options
                message: value
            }, {
                // settings
                z_index: 10000000000,
                type: msg_type,

            });
//
        });
    }

}

function notificationError(xhr, ajaxOptions, thrownError) {

    if (xhr.status == 403) {
        $.notify({
            // options
            message: '403 : Unauthorized action.'
        }, {
            // settings
            z_index: 100000,
            type: 'danger',

        });


    } else {
        $.notify({
            // options
            message: 'Oops! Something went wrong'
        }, {
            // settings
            z_index: 100000,
            type: 'danger',

        });
    }

}


$(document).ready(function () {


    $(document).on('blue', 'input[type=text]', function (e) {
        var x = document.getElementById("userInput");
       this.value.toLocaleUpperCase();
    });


        $('input[type=text]').val (function () {
        return this.value.toLocaleUpperCase();
    })

    $("#txtEditor").Editor();


    setTimeout(
        function()
        {

            $(document.body).addClass('enlarge-menu');
        }, 1000);


    // $(".left-sidenav").on("mouseover", function () {
    //     $(document.body).removeClass("enlarge-menu");
    // });
    // $(".left-sidenav").on("mouseout", function () {
    //     $(document.body).addClass('enlarge-menu');
    // });



    $('#animated_bar').css('visibility', 'hidden');



    $("#search_mobile").intlTelInput({
        preferredCountries: ['LK'],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc

    });

    $("#search_telephone").intlTelInput({
        preferredCountries: ['LK'],
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/11.1.2/js/utils.js" // just for formatting/placeholders etc
    });


    $('#change_user_theme').click(function (e) {
        var params = {};
        e.preventDefault();
        $.ajax({
            url: BASE + 'change_user_theme',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                if (response.status == 'success') {
                    location.reload();

                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }

        });
        e.preventDefault();
        return false;
    });

    $("#project_code_selected").change(function (e) {
        if ($("#project_code_selected").val() == '') {
            return false;
        }
        var params = {
            project_id: $(this).val()
        };

        e.preventDefault();
        $.ajax({
            url: BASE + 'get_customer_by_project_id',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                if (response.status == 'success') {

                    changeCustomer.run(response.customer_id, '', '', '');

                    if ($('#page').val() == 'invoice')
                        if ($('#invoice_id').val() == '')
                            createInvoice.run();
                    if ($('#page').val() == 'quotation')
                        if ($('#quotation_id').val() == '')
                            createQuotation.run();


                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }

        });
        e.preventDefault();
        return false;
    });

    var selecte_payment_type = new Option('Select Type', '', true, true);
    $('#payment_method').append(selecte_payment_type).trigger('change');


    $("#payment_method").change(function (e) {
        var btn_id = $("#payment_method").val();
        if ($("#payment_method").val() == 'cheque') {
            //$("#cheque_date_col").show('slow')
            $("#cheque_bank_col").show('slow')
        }
        else
        {
         //   $("#cheque_date_col").hide('slow');
            $("#cheque_bank_col").hide('slow');
        }
        if ($("#payment_method").val() == '') {
            $("#payment_amount").val('');
            return false;
        }
        url = null;
        if ($("#ref_type").val() == 'CPM' || $("#ref_type").val() == 'IN') {
            url = BASE + 'get_customer_balance_details';
            var params = {
                customer_id: $('#customer_id_selected').val(),
            };
        }
        else
        {
            url = BASE + 'get_supplier_balance_details';
            var params = {
                supplier_id: $('#supplier_code_selected').val(),
            };
        }
        e.preventDefault();
        $.ajax({
            url:url,
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {



                if (response.status == 'success') {

                    if ($("#ref_type").val() == 'CPM'|| $("#ref_type").val() == 'IN') {
                        if (btn_id == 'credit') {
                            $("#payment_amount").val(response.customer.credit_balance);
                        } else {
                            $("#payment_amount").val(response.customer.outstanding_amount);
                        }
                    }


                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }

        });
        e.preventDefault();
        return false;
    });


    $(document).on('change', '#business_district_id , #shipping_district_id', function (e) {

        if ($(this).val() == '')
            return false;

        var btn_id = $(this).attr("id");

        var params = {
            district_id: $(this).val()
        };
        e.preventDefault();
        $.ajax({
            url: BASE + 'get_cities_list_by_district_id',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                if (response.status == 'success') {

                    if (btn_id == 'business_district_id') {
                        $("#business_city_id").find('option').remove();
                        $.each(response.cities, function () {
                            $("#business_city_id").append($("<option />").val(this.id).text(this.name_en));
                        });
                        // $('#business_city_id').select2('open');
                    } else {
                        $("#shipping_city_id").find('option').remove();
                        $.each(response.cities, function () {
                            $("#shipping_city_id").append($("<option />").val(this.id).text(this.name_en));
                        });
                        // $('#shipping_city_id').select2('open');
                    }
                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }

        });
        e.preventDefault();
        return false;
    });

    $(document).on('change', '#business_city_id , #shipping_city_id', function (e) {

        if ($(this).val() == '')
            return false;

        btn_id = $(this).attr("id");
        var params = {
            city_id: $(this).val()
        };

        e.preventDefault();
        $.ajax({
            url: BASE + 'get_district_by_city_id',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {

                if (response.status == 'success') {

                    if (btn_id == 'business_city_id') {
                        $('#business_district_id').val(response.district_id).trigger('change.select2');
                    } else {
                        $('#shipping_district_id').val(response.district_id).trigger('change.select2');
                    }
                } else {
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }

        });
        e.preventDefault();
        return false;
    });

    var selectedcustomerCode = new Option('Select Customer Code', '', true, true);
    var selectedcustomerName = new Option('Select Customer name', '', true, true);
    var selectedcompanyName = new Option('Select Company name', '', true, true);
    var selectedstaffidName = new Option('Select Staff id', '', true, true);
    var selectedstaffnameName = new Option('Select Staff Name ', '', true, true);

    if ($('#ref_id').val() == '') {
        $('#customer_name_selected').append(selectedcustomerCode).trigger('change');
        $('#customer_id_selected').append(selectedcustomerName).trigger('change');
        $('#company_id_selected').append(selectedcompanyName).trigger('change');
        $('#staff_member_id_selected').append(selectedstaffidName).trigger('change');
        $('#staff_member_name_selected').append(selectedstaffnameName).trigger('change');
    }

    $("#staff_member_id_selected , #staff_member_name_selected").change(function (e) {
        var btn = $(this).attr("id");
        if (btn == 'staff_member_id_selected') {
            $("#staff_member_name_selected").val(this.value).trigger('change.select2');
        } else {
            $("#staff_member_id_selected").val(this.value).trigger('change.select2');
        }
    });

    $('#search_telephone').keyup(function (e) {

        if (this.value.length > 8) {
            changeCustomer.run('', '', $('#search_telephone').intlTelInput("getNumber"), 'tel');
        } else {
            reset('tel');
        }

    });


    $('#search_mobile').keyup(function (e) {
        if (this.value.length > 8) {
            changeCustomer.run('', $('#search_mobile').intlTelInput("getNumber"), '', 'mobile');
        } else

            reset('mobile');
    });


    var customer_name_selected = $('#customer_name_selected').select2({
        ajax: {
            url: BASE + 'get_select_two_customer_code_filter',
            dataType: 'json',
            delay: 500,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.business_name,
                            id: item.id
                        }
                    })
                };
            },
        }
    });


    var customer_id_selected = $('#customer_id_selected').select2({
        ajax: {
            url: BASE + 'get_select_two_customer_name_filter',
            dataType: 'json',
            delay: 500,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        if(item.company_name != '') {
                            return {
                                text: item.company_name,
                                id: item.id
                            }
                        }
                    })
                };
            },
        }
    });


    window.changeCustomer = {
        run: function (id, phone, tel, filterBy) {


            var mobile_iso = $("#search_mobile").intlTelInput("getSelectedCountryData");
            var telephone_iso = $("#search_telephone").intlTelInput("getSelectedCountryData");

            var params = {
                id: id,
                phone: phone,
                tel: tel,
                mobile_country: mobile_iso['iso2'],
                telephone_country: telephone_iso['iso2'],
            };
            $.ajax({
                url: BASE + 'customer/get_customer_details',
                type: 'POST',
                dataType: 'JSON',
                async: false,
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {

                        if ($("#invoice_id").val() == '') {
                            $("#invoice_discount").val(response.customer.discount);
                        }

                        $("#customer_id").val(response.customer.id);


                        $("#rep").val(response.customer.rep_id).change();

                        $("#invoice_discount_type").val(response.customer.discount_type);
                        $("#customer_name").val(response.customer.fname);
                        $("#customer_type").val(response.customer.customer_type);
                        $("#search_telephone").val(response.customer.telephone);
                        $("#search_mobile").val(response.customer.mobile);

                        var customer_id_option = new Option(response.customer.company_name, response.customer.id, true, true);
                        $("#customer_id_selected").append(customer_id_option).trigger('change.select2');

                        var customer_name_option = new Option(response.customer.business_name, response.customer.id, true, true);
                        $("#customer_name_selected").append(customer_name_option).trigger('change.select2');


                        //  $("#customer_id_selected").val(response.customer.id).trigger('change.select2');
                        //    $("#customer_name_selected").val(response.customer.id).trigger('change.select2');

                        if (response.customer.company_name != null) {
                            $("#company_id_selected").val(response.customer.id).trigger('change.select2');
                        } else {
                            $("#company_id_selected").val('').trigger('change.select2');
                        }


                        var outstanding_amount = '0.00';
                        var credit_balance = 0;


                        if (response.customer.outstanding_amount != null)
                            outstanding_amount = response.customer.outstanding_amount;

                        if (response.customer.credit_balance != null)
                            credit_balance = response.customer.credit_balance;

                        var tooltip = "<div class='row'>" +
                            "<div class='col-sm-12'>" +
                            "<strong><a href='" + BASE + "customer/" + response.customer.id + "/edit' target='_blank'>" + response.customer.company_name + "</a></strong><br>" +
                            "<strong><a href='" + BASE + "customer/" + response.customer.id + "/edit' target='_blank'>" + response.customer.business_name + "</a></strong><br>" +
                            "<strong><a href='" + BASE + "customer/" + response.customer.id + "/edit' target='_blank'>" + response.customer.fname + " " + response.customer.lname + "</a></strong>" +
                            "</div>" +
                            "<div class='col-sm-12'>Outstanding amount : Rs. " + outstanding_amount + "</div>" +
                            "<div class='col-sm-12'>Credits : Rs. " + credit_balance + "</div>" +
                            "<div class='col-sm-12'>Pending Cheques : Rs. " + response.pending_cheques + "</div>" +
                            "<div class='col-sm-12'><button class='btn btn-primary' id='customer_history_btn'>View Customer History</button> </div> " +
                            "</div>";

                        $('#customer_info_alert').show('slow');
                        $('#customer_info_alert').html(tooltip);


                    } else {
                        reset(filterBy);
                        notification(response);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });
            return false;

        }
    }

    var selectedproject = new Option('Please select project', '', true, true);
    $('#project_code_selected').append(selectedproject).trigger('change.select2');




    window.getInvoices = {
        run: function () {

            var params = {
                customer_id: $('#customer_id_selected').val()
            };
            var url = BASE + 'invoice_return/get_invoices';


            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {

                        $("#invoice_code_selected").find('option').remove();
                        $.each(response.invoices, function () {
                            $("#invoice_code_selected").append($("<option />").val(this.id).text(this.invoice_code));
                        });
                        var selectedinvoice = new Option('Select Invoice Code', '', true, true);
                        $('#invoice_code_selected').append(selectedinvoice).trigger('change.select2');
                        $('#invoice_code_selected').select2('open');
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

    window.getPurchaseOrder = {
        run: function () {

            var params = {
                supplier_id: $('#supplier_code_selected').val()
            };
            var url = BASE + 'po_return/get_purchase_order';

            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'JSON',
                data: $.param(params),
                success: function (response) {

                    if (response.status == 'success') {

                        $("#purchase_order_code_selected").find('option').remove();
                        $.each(response.purchase_order, function () {
                            $("#purchase_order_code_selected").append($("<option />").val(this.id).text(this.purchase_order_code));
                        });
                        var selectedpurchaseorder = new Option('Select Purchase Order Code', '', true, true);
                        $('#purchase_order_code_selected').append(selectedpurchaseorder).trigger('change.select2');
                        $('#purchase_order_code_selected').select2('open');
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

    $("#customer_name_selected , #customer_id_selected ,#company_id_selected").change(function (e) {

        if (this.value != '') {
            changeCustomer.run(this.value, '', '');
        } else
            reset('');
        if ($("#project_code_selected").length) {
            getProject.run();
        }
        if ($("#invoice_code_selected").length) {
            getInvoices.run();
        }
        if ($("#quotation_code_selected").length) {
            getQuotation.run();
        }
        if ($("#job_card_code_selected").length) {
            getJobCard.run();
        }
        if ($("#ref_type").val() == 'CPM') {

            InvoicePaymentsTable
                .clear()
                .draw();
            if ($('#customer_id_selected').val() == '') {
                $('#customer_info_alert').hide('slow');
                $('#customer_id_selected').val(0)
            }
            customer_outstanding_table.ajax.url(BASE + 'invoices_list/' + $('#customer_id_selected').val() + '/customer/payment').load();
            InvoicePaymentsTable.ajax.url(BASE + 'invoice/get_sales_payments/' + $('#customer_id_selected').val()+'/'+ 'customer').load();
        }

        if ($("#ref_type").val() == 'INR') {

            PendingInvoiceTable.ajax.url(BASE + 'return_from_invoice_list/' + $('#customer_id_selected').val()).load();
        }
    });

    function reset(come) {
        if (come == 'mobile') {
            // $("#invoice_discount").val('');
            $("#invoice_discount_type").val('');
            $("#customer_name").val('');
            $("#customer_type").val('');
            $("#company_id_selected").val('').trigger('change.select2');
            $("#customer_id_selected").val('').trigger('change.select2');
            $("#customer_name_selected").val('').trigger('change.select2');
            $("#supplier_code_selected").val('').trigger('change.select2');
            $("#supplier_name_selected").val('').trigger('change.select2');
            $("#search_telephone").val('');
            $("#supplier_search_telephone").val('');

        }
        if (come == 'tel') {
            // $("#invoice_discount").val('');
            $("#invoice_discount_type").val('');
            $("#customer_name").val('');
            $("#customer_type").val('');
            $("#company_id_selected").val('').trigger('change.select2');
            $("#customer_id_selected").val('').trigger('change.select2');
            $("#customer_name_selected").val('').trigger('change.select2');
            $("#supplier_code_selected").val('').trigger('change.select2');
            $("#supplier_name_selected").val('').trigger('change.select2');
            $("#search_mobile").val('');
            $("#supplier_search_mobile").val('');
        }
        if (come == '') {
            // $("#invoice_discount").val('');
            $("#invoice_discount_type").val('');
            $("#customer_name").val('');
            $("#customer_type").val('');
            $("#company_id_selected").val('').trigger('change.select2');
            $("#customer_id_selected").val('').trigger('change.select2');
            $("#customer_name_selected").val('').trigger('change.select2');
            $("#search_mobile").val('');
            $("#search_telephone").val('');
            $("#supplier_code_selected").val('').trigger('change.select2');
            $("#supplier_name_selected").val('').trigger('change.select2');
            $("#supplier_search_mobile").val('');
            $("#supplier_search_telephone").val('');
        }

    }

    var selected_supplier_name = new Option('Select Supplier name', '', true, true);
    $('#supplier_name_selected').append(selected_supplier_name).trigger('change');

    var selected_supplier_Code = new Option('Select Supplier Code', '', true, true);
    $('#supplier_code_selected').append(selected_supplier_Code).trigger('change');

    if ($('#grn_id').val() == '' || $('#po_return_id').val() == '') {
        var selected_purchase_order_Code = new Option('Select Purchase Order Code', '', true, true);
        $('#purchase_order_code_selected').append(selected_purchase_order_Code).trigger('change.select2');
    }


    var selected_invoice_Code = new Option('Select Invoice Code', '', true, true);
    $('#invoice_code_selected').append(selected_invoice_Code).trigger('change');


    $("#supplier_code_selected ,#supplier_name_selected").change(function (e) {
        if (this.value != '') {
            changeSupplier.run(this.value, '', '');
        } else
            reset('');
        if ($("#purchase_order_code_selected").length) {
            getPurchaseOrder.run();
        }
        if ($("#ref_type").val() == 'SB') {

            if ($('#supplier_code_selected').val() == '') {
                $('#supplier_info_alert').hide('slow');
                $('#supplier_code_selected').val(0)
            }
            supplier_outstanding_table.ajax.url(BASE + 'purchase_orders_list/' + $('#supplier_code_selected').val() + '/supplier/payment').load();
        }


    });


    $('#supplier_search_telephone').keyup(function (e) {
        if (this.value.length > 8) {
            changeSupplier.run('', '', $('#supplier_search_telephone').intlTelInput("getNumber"), 'tel');
        } else {
            reset('tel');
        }
    });


    $('#supplier_search_mobile').keyup(function (e) {
        if (this.value.length > 8) {
            changeSupplier.run('', $('#supplier_search_mobile').intlTelInput("getNumber"), '', 'mobile');
        } else {
            reset('mobile');
        }
    });

    window.changeSupplier = {

        run: function (id, phone, tel, filterBy) {

            var mobile_iso = $("#supplier_search_mobile").intlTelInput("getSelectedCountryData");
            var telephone_iso = $("#supplier_search_telephone").intlTelInput("getSelectedCountryData");

            var params = {
                id: id,
                phone: phone,
                tel: tel,
                mobile_country: mobile_iso['iso2'],
                telephone_country: telephone_iso['iso2'],
            };
            $.ajax({
                url: BASE + 'supplier/get_supplier_details',
                type: 'POST',
                dataType: 'JSON',
                async: false,
                data: $.param(params),
                success: function (response) {
                    if (response.status == 'success') {

                        $("#supplier_id").val(response.supplier.id);
                        $("#supplier_search_telephone").val(response.supplier.telephone);
                        $("#supplier_search_mobile").val(response.supplier.mobile);
                        $("#supplier_code_selected").val(response.supplier.id).trigger('change.select2');
                        $("#supplier_name_selected").val(response.supplier.id).trigger('change.select2');



                        var outstanding_amount = '0.00';
                        var credit_balance = 0;


                        if (response.supplier.due_amount != null)
                            outstanding_amount = response.supplier.due_amount;

                        if (response.supplier.credit_balance != null)
                            credit_balance = response.supplier.debit;

                        var tooltip = "<div class='row'>" +
                            "<div class='col-sm-12'>" +
                            "<strong><a href='" + BASE + "customer/" + response.supplier.id + "/edit' target='_blank'>" + response.supplier.business_name + "</a></strong><br>" +
                            "<strong><a href='" + BASE + "customer/" + response.supplier.id + "/edit' target='_blank'>" + response.supplier.fname + " " + response.supplier.lname + "</a></strong>" +
                            "</div>" +
                            "<div class='col-sm-12'>Due amount : Rs. " + outstanding_amount + "</div>" +
                            "<div class='col-sm-12'>Debit : Rs. " + credit_balance + "</div>" +
                            "</div>";

                        $('#supplier_info_alert').show('slow');
                        $('#supplier_info_alert').html(tooltip);



                    } else {
                        reset(filterBy);
                        notification(response);
                    }

                },
                error: function (xhr, ajaxOptions, thrownError) {

                    notificationError(xhr, ajaxOptions, thrownError);
                }
            });
            return false;


        }
    }

})
;


$('#ProductsTable tbody').on('click', 'tr', function () {
    if ($(this).hasClass('selected')) {
        $(this).removeClass('selected');
    } else {
        ProductsTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
    }
});


var selected_product_category = new Option('Select Product Category', '', true, true);
$('#production_category_code').append(selected_product_category).trigger('change');
var selected_product = new Option('Select Product', '', true, true);
$('#production_product_code').append(selected_product).trigger('change');
var selected_store_location = new Option('Select Store Location', '', true, true);
$('#production_store_location').append(selected_store_location).trigger('change');


$('#production_category_code').change(function (e) {
    // if($('#ref_type').val()=='IN')
    // {
    //     var only_available_stock_product = true;
    // }
    // else
    // {
        var only_available_stock_product = false;
    // }
    var params = {
        category_id: $("#production_category_code").val(),
        only_available_stock_product : only_available_stock_product
    };
    var method = '';
    var url = '';

    url = BASE + 'get_products_by_category';
    method = 'POST';

    e.preventDefault();
    $.ajax({
        url: url,
        type: method,
        dataType: 'JSON',
        async: false,
        data: $.param(params),
        success: function (response) {

            if (response.status == 'success') {

                $("#production_product_code").find('option').remove();
                $.each(response.products, function () {
                    if (this.category_id==27)
                    $("#production_product_code").append($("<option />").val(this.id).text(this.item_code));
                });

                var selected_product = new Option('Please select product', '', true, true);
                $('#production_product_code').append(selected_product).trigger('change.select2');
                $('#production_product_code').select2('open');
            } else
                {
                notification(response);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

            notificationError(xhr, ajaxOptions, thrownError);
        }

    });

});

$('#production_product_code').change(function (e) {
    if ($("#production_product_code").val() == '')
        $("#production_product_code").tooltip('hide');

    if ($("#production_product_code").val() == '') {
        $('#production_quantity').val('');
        return false;
    }

    $('#record_product_update_id').val('');
    var params = {
        product_id: $("#production_product_code").val()
    };
    var method = '';
    var url = '';


    url = BASE + 'get_product_details';
    method = 'POST';

    e.preventDefault();
    $.ajax({
        url: url,
        type: method,
        dataType: 'JSON',
        data: $.param(params),
        success: function (response) {
            if (response.status == 'success') {
                $('#production_category_code').val(response.products.category_id).trigger('change.select2');

            } else {
                notification(response);

            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

            notificationError(xhr, ajaxOptions, thrownError);
        }

    });

});

var selected_product_category = new Option('Select Product Category', '', true, true);
$('#item_category_code').append(selected_product_category).trigger('change');
var selected_product = new Option('Select Product', '', true, true);
$('#item_product_code').append(selected_product).trigger('change');
var selected_store_location = new Option('Select Store Location', '', true, true);
$('#store_location').append(selected_store_location).trigger('change');


$('#item_category_code').change(function (e) {

    if($('#ref_type').val()=='IN')
    {
        var only_available_stock_product = true;
    }
    else
    {
        var only_available_stock_product = false;
    }
    var params = {
        category_id: $("#item_category_code").val(),
        only_available_stock_product : only_available_stock_product
    };
    var method = '';
    var url = '';

    url = BASE + 'get_products_by_category';
    method = 'POST';

    e.preventDefault();
    $.ajax({
        url: url,
        type: method,
        dataType: 'JSON',
        async: false,
        data: $.param(params),
        success: function (response) {

            if (response.status == 'success') {


                $("#item_product_code").find('option').remove();
                $.each(response.products, function () {
                    $("#item_product_code").append($("<option />").val(this.id).text(this.item_code));
                });

                var selectedproduct = new Option('Please select product', '', true, true);
                $('#item_product_code').append(selectedproduct).trigger('change.select2');
                $('#item_product_code').select2('open');
            } else {
                notification(response);
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

            notificationError(xhr, ajaxOptions, thrownError);
        }

    });

});

$('#products_info_alert').hide();
$('#customer_info_alert').hide();




$('#item_product_code').change(function (e) {

    $('#item_stock_id').val($("#item_product_code").val()).trigger('change.select2');

    if ($("#item_product_code").val() == '')
        $("#item_product_code").tooltip('hide');

    if ($("#item_product_code").val() == '') {

        $('#quantity').val('');
        $('#unit_price').val('');
        $('#product_discount').val('');
        $('#description').val('');
        $('#product_discount_type').val('P').trigger('change');
        $('#store_location').val('').trigger('change');
        $('#item_category_code').val('').trigger('change.select2');
        return false;
    }

    $('#record_product_update_id').val('');
    var params = {
        product_id: $("#item_product_code").val()
    };
    var method = '';
    var url = '';


    url = BASE + 'get_product_details';
    method = 'POST';
    e.preventDefault();
    $.ajax({
        url: url,
        type: method,
        dataType: 'JSON',
        data: $.param(params),
        success: function (response) {

            if (response.status == 'success') {

                $('#item_category_code').val(response.products.category_id).trigger('change.select2');
                $('#description').val(response.products.description).tooltip('show');

                if ($('#ref_type').val() == 'IN' || $('#ref_type').val() == 'Production') {
                    $('#quantity').val(1).tooltip('show');
                    if (response.price_edit == false )
                    {
                        $('#unit_price').val(response.products.price).attr("disabled", true).tooltip('show');
                    }
                    else
                    {
                        $('#unit_price').val(response.products.price).attr("disabled", false).tooltip('show');
                    }

                    $('#product_discount').val(response.products.discount_amount).tooltip('show');
                    $('#product_discount_type').val(response.products.discount_type).trigger('change');
                    if (response.products.default_store_location!= null)
                    {
                        $('#store_location').val(response.products.default_store_location).trigger('change');
                    }
                    else
                    {
                        $('#store_location').val('').trigger('change');
                    }
                    $("#description").prop("readonly", true);
                    $('#quantity').focus().select();

                }

                if ($('#ref_type').val() == 'PO') {
                    $('#quantity').val(response.products.reorder_qty).tooltip('show');
                    $('#unit_price').val(response.products.cost).tooltip('show');


                }

                if ($('#ref_type').val() == 'GRN') {

                    $('#ordered_qty').val(response.products.reorder_qty).tooltip('show');
                    $('#unit_price').val(response.products.cost).tooltip('show');
                    $('#selling_price').val(response.products.price).tooltip('show');
                    $('#delivered_qty').val(response.products.reorder_qty).tooltip('show');

                    $("#description").prop("readonly", true);

                    $('#delivered_qty').val(1).tooltip('show').focus().select();

                }




                if (typeof response.products.store_location === undefined || response.products.store_location === null) {
                    var rack = '-';
                } else
                    var rack = response.products.store_location.location;

                var tooltip = "<div class='row'>" +
                    "<div class='col-sm-12'>" +
                    "<strong>" + response.products.item_code + "</strong>" +
                    "</div>" +
                    "<div class='col-sm-12'>";
                if (response.products.type == 'non_stock')
                    tooltip = tooltip + "Non-stock";
                else if (response.products.type == 'service')
                    tooltip = tooltip + "Service";
                else {
                            tooltip += response.available_stock;


                }


                tooltip = tooltip + "</div></div>";

                $('#products_info_alert').show('slow');
                $('#products_info_alert').html(tooltip);

                // $("#item_product_code").attr('title', tooltip).tooltip('fixTitle').tooltip('show');


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
    $('#item_product_code').val($("#item_stock_id").val()).trigger('change');

});


$('#product_filter_barcode').keyup(function (e) {

    if ($("#product_filter_barcode").val() == '') {
        $('#item_product_code').val('');
        $('#item_product_code').trigger('change');
        return false;
    }


    var params = {
        barcode: $("#product_filter_barcode").val()
    };
    var method = '';
    var url = '';


    url = BASE + 'get_product_details_by_barcode';
    method = 'POST';

    e.preventDefault();
    $.ajax({
        url: url,
        type: method,
        dataType: 'JSON',
        data: $.param(params),
        success: function (response) {

            if (response.status == 'success') {

                if (!$("#item_product_code option[value=" + response.products.id + "]").length > 0) {
                    var product = new Option(response.products.item_code, response.products.id, true, true);
                    $('#item_product_code').append(product).trigger('change');
                } else {
                    $('#item_product_code').val(response.products.id);
                    $('#item_product_code').trigger('change');
                }


            } else {
                $('#item_product_code').val('');
                $('#item_product_code').trigger('change');

            }
        },
        error: function (xhr, ajaxOptions, thrownError) {

            notificationError(xhr, ajaxOptions, thrownError);
        }

    });

});


// window.disable_save_button_group = {
//     run: function () {
//         $(':button').prop('disabled', true);
//     }
// };
//
// window.enable_save_button_group = {
//     run: function () {
//         $(':button').prop('disabled', false);
//     }
// };

window.disable_save_button_group = {
    run: function () {
        $('#animated_bar').css('visibility', 'visible');
        $('#save_button_group :button').prop('disabled', true);
        $('#save_button_group a').prop('disabled', true);
        $(':button').prop('disabled', true);
    }
};

window.enable_save_button_group = {
    run: function () {
        $('#animated_bar').css('visibility', 'hidden');
        $('#save_button_group :button').prop('disabled', false);
        $('#save_button_group a').prop('disabled', false);
        $(':button').prop('disabled', false);
    }
};



$(document).ajaxStart(function () {
    disable_save_button_group.run();
}).ajaxStop(function () {
    enable_save_button_group.run();
});

window.calPrice = {
    run: function () {

        var price_details = $('#price_panel :input').serialize();

        if ($('#ref_type').val() == 'QU') {
            var params = {
                quotation_price_details: price_details,
                quotation_id: $('#quotation_id').val()
            };

            var url = BASE + 'quotation/cal_quotation_price';
        }
        if ($('#ref_type').val() == 'PO') {
            var params = {
                payment_details: payment_details,
                purchase_order_id: $('#purchase_order_id').val()
            };

            var url = BASE + 'purchase_order/cal_purchase_order_price';
        }
        if ($('#ref_type').val() == 'CPM') {
            var payment_details = $('#payment_panel :input').serialize();
            var params = {
                payment_details: payment_details,
                invoice_id: $('#invoice_id').val()
            };
            var url = BASE + 'payment/cal_payment_price';
        }
        if ($('#ref_type').val() == 'SB') {
            var payment_details = $('#payment_panel :input').serialize();
            var params = {
                payment_details: payment_details,
                purchase_order_id: $('#purchase_order_id').val()
            };
            var url = BASE + 'bill/cal_bill_price';
        }
        if ($('#ref_type').val() == 'PAY') {

            var params = {
                ref_type: $('#ref_type').val(),
                cancel_payment_amount: $("#cancel_amount").val(),
                invoice_id: $('#invoice_id').val()
            };
            var url = BASE + 'invoice/cal_invoice_price';
        }

        if ($('#ref_type').val() == 'IN') {
            var params = {
                invoice_price_details: price_details,
                invoice_id: $('#invoice_id').val()
            };
            var url = BASE + 'invoice/cal_invoice_price';
        }
        if ($('#ref_type').val() == 'JC') {
            var params = {
                job_card_price_details: price_details,
                job_card_id: $('#job_card_id').val()
            };
            var url = BASE + 'job_card/cal_job_card_price';
        }
        if ($('#ref_type').val() == 'GRN') {
            var params = {
                grn_price_details: price_details,
                grn_id: $('#grn_id').val()
            };
            var url = BASE + 'grn/cal_order_price';
        }
        if ($('#ref_type').val() == 'GRNR') {
            var params = {
                grn_return_price_details: price_details,
                grn_return_id: $('#grn_return_id').val()
            };
            var url = BASE + 'grn_return/cal_order_price';
        }
        if ($('#ref_type').val() == 'INR') {
            var params = {
                invoice_return_price_details: price_details,
                invoice_return_id: $('#invoice_return_id').val()
            };
            var url = BASE + 'invoice_return/cal_invoice_price';
        }
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'JSON',
            async: false,
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {


                    var focus = $(document.activeElement);
                    var presskey = focus[0].id;
                    if (response.record.discount != 0 && presskey != 'po_discount') {

                        $('#po_discount').val(response.record.discount);
                    }
                    if ($('#ref_type').val() != 'IN') {
                        if (response.record.paid_amount != 0 && presskey != 'paid') {
                            $('#paid').val(response.record.paid_amount);

                        }
                    }

                    $('#sub_total').val(response.record.sub_total);
                    $('#invoice_discount').val(response.record.discount);
                    $('#po_discount_type').val(response.record.discount_type);
                    $('#total').val(response.record.total);
                    $('#vat_amount').val(response.record.vat_amount);
                    $('#nbt_amount').val(response.record.nbt_amount);

                    if ($('#ref_type').val() == 'IN') {

                        $('#paid').val(response.record.paid_amount);
                        $('#balance').val(response.record.balance);
                        $('#returned_amount').val(response.record.returned_amount);

                        refreshCustomerDetails.run();
                    }

                    if ($('#ref_type').val() == 'INR') {
                        $('#refund').val(response.record.refund);
                        $('#customer_credit').val(response.record.customer_credit);
                        refreshCustomerDetails.run();
                    }

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

window.refreshCustomerDetails = {
    run: function () {
        if($("#customer_id").val() == '')
            return false;

        var params = {
            id:  $("#customer_id").val(),
        };
        $.ajax({
            url: BASE + 'customer/get_customer_details',
            type: 'POST',
            dataType: 'JSON',
            async: false,
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {

                    var outstanding_amount = '0.00';
                    var credit_balance = 0;


                    if (response.customer.outstanding_amount != null)
                        outstanding_amount = response.customer.outstanding_amount;

                    if (response.customer.credit_balance != null)
                        credit_balance = response.customer.credit_balance;

                    var tooltip = "<div class='row'>" +
                        "<div class='col-sm-12'>" +
                        "<strong><a href='" + BASE + "customer/" + response.customer.id + "/edit' target='_blank'>" + response.customer.company_name + "</a></strong><br>" +
                        "<strong><a href='" + BASE + "customer/" + response.customer.id + "/edit' target='_blank'>" + response.customer.business_name + "</a></strong><br>" +
                        "<strong><a href='" + BASE + "customer/" + response.customer.id + "/edit' target='_blank'>" + response.customer.fname + " " + response.customer.lname + "</a></strong>" +
                        "</div>" +
                        "<div class='col-sm-12'>Outstanding amount : Rs. " + outstanding_amount + "</div>" +
                        "<div class='col-sm-12'>Credits : Rs. " + credit_balance + "</div>" +
                        "<div class='col-sm-12'>Pending Cheques : Rs. " + response.pending_cheques + "</div>" +
                        "<div class='col-sm-12'><button class='btn btn-primary' id='customer_history_btn'>View Customer History</button> </div> " +
                        "</div>";

                    $('#customer_info_alert').show('slow');
                    $('#customer_info_alert').html(tooltip);

                } else {

                    notification(response);
                }

            },
            error: function (xhr, ajaxOptions, thrownError) {

                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        return false;
    }
}

//$("#cheque_date_col").hide('slow');
$("#cheque_bank_col").hide('slow');



var CommentsListTable = $('#CommentsListTable').DataTable({
    searching: false,
    paging: false,
    responsive: true,
    "order": [[0, 'desc']],
    serverSide: true,
    ajax: BASE + 'get_comments_list/' + $('#ref_type').val() + '/' + $('#ref_id').val(),
    bInfo: false,

    columns: [
        {data: 'id', name: 'id', 'bVisible': false},
        {data: 'created_at', name: 'created_at'},
        {data: 'comments', name: 'comments'},
        {data: 'user', name: 'user'},
    ]
});


$("#comment_save_btn").click(function (e) {
    var params = {

        ref_id: $('#ref_id').val(),
        ref_type: $('#ref_type').val(),
        comment: $('#txtEditor').Editor("getText")
    };
    e.preventDefault();
    $.ajax({
        url: BASE + 'save_comment',
        type: 'POST',
        dataType: 'JSON',
        data: $.param(params),
        success: function (response) {
            notification(response);
            CommentsListTable.ajax.url(BASE + 'get_comments_list/' + $('#ref_type').val() + '/' + $('#ref_id').val()).load();
            $("#txtEditor").Editor("setText", "");

        }

    });
    e.preventDefault();
    return false;
});


//custom models


$("#select_invoice_product_btn").click(function (e) {

    window.search_invoice_products = $.confirm({
        title: 'Invoice Products',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'get_invoice_products_modal',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});


$("#create_new_customer_model").click(function (e) {

    window.customer_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
               return true;
            }
        },
        content: 'url:' + BASE + 'customer/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});


$("#generate_mail").click(function (e) {

    var id;

    if ($("#ref_type").val() == 'IN') {
        id = $("#invoice_id").val();
        var link = 'invoice_mail/create_mail_model/' + id;
        var link2 = 'invoice_send_email'
    }
    if ($("#ref_type").val() == 'PO') {
        id = $("#purchase_order_id").val();
        var link = 'purchase_order_mail/create_mail_model/' + id;
        var link2 = 'purchase_order_send_email'
    }
    if ($("#ref_type").val() == 'QU') {
        id = $("#quotation_id").val();
        var link = 'quotation_mail/create_mail_model/' + id;
        var link2 = 'quotation_send_email'
    }
    window.mail_body_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {
            close: function () {
            }
        },
        content: 'url:' + BASE + link,
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});

$('body').on('click', '#customer_history_btn', function () {

    window.customer_history_model = $.confirm({
        title: 'Customer History',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'customer_invoices/customer_history_modal/' + $('#customer_id_selected').val(),
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
    return false;
});


$("#create_new_quotation_model").click(function (e) {
    window.quotation_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'quotation/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});

$("#create_new_invoice_model").click(function (e) {
    window.invoice_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'invoice/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});

$("#create_new_income_model").click(function (e) {
    window.income_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'income/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});

$("#create_new_expense_model").click(function (e) {
    window.expense_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'expense/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});

$("#create_new_inquiry_model").click(function (e) {
    window.inquiry_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'inquiry/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});


$("#create_new_supplier_model").click(function (e) {

    window.supplier_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'supplier/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });
});

$("#create_new_product_model").click(function (e) {

    window.product_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        buttons: {

            close: function () {
            }
        },
        content: 'url:' + BASE + 'product/create',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });


});

$("#create_new_category_model").click(function (e) {

    window.category_model = $.confirm({
        title: '',
        draggable: true,
        boxWidth: '80%',
        closeIcon: true,
        useBootstrap: false,
        closeIcon: false,
        buttons: {

            close: function () {
                e.preventDefault();
                $.ajax({
                    url: BASE + 'finance_category/restore_category_list/' + $('#finance_type').val(),
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        if (response.status == 'success') {

                            $("#finance_category").find('option').remove();
                            $.each(response.category, function () {
                                $("#finance_category").append($("<option />").val(this.id).text(this.name));
                            });
                            var selected_category = new Option('Please select category', '', true, true);
                            $('#finance_category').append(selected_category).trigger('change.select2');


                        } else {
                            notification(response);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }

                });
                e.preventDefault();

            }
        },
        content: 'url:' + BASE + 'finance_category',
        onContentReady: function () {

        },
        columnClass: 'medium',
    });









});


