$(document).ready(function () {



    $("#create_customer_form").validationEngine();

    if ($('#customer_id').val() != '')
    {
        $('#customer-save-btn').hide('slow');
        $('#customer-save-and-new').hide('slow');
    }
    else {
        $('#customer-update-btn').hide('slow');
        $('#customer_comment_content').hide('slow');
    }

    if ($('#shipping_address_status').val() == 1)
    {
        $('#add_shipping').prop('checked', true);
    }

    if ($('#add_as_company_name').val() == 'B')
    {
        $('#customer_type').val('B').trigger('change')
    }

    $('#add_user_account').prop('checked', true);

    $('#customer_type').change(function () {
        if ($('#customer_type').val()=='B') {
            $('#company_detail_panel').show(1000);
        } else {
            $('#company_detail_panel').hide(1000);
        }
    });


    $('#add_shipping').change(function () {
        if ($('#add_shipping').is(':checked')) {
            $('#shipping_address_panel').show(1000);
        } else {
            $('#shipping_address_panel').hide(1000);
        }
    });

    $('#add_user_account').change(function () {
        if ($('#add_user_account').is(':checked')) {
            $('#user_account_panel').show(1000);
        } else {
            $('#user_account_panel').hide(1000);
        }
    });



    $("#customer-save-btn ,#customer-update-btn , #customer-save-and-new").click(function (e) {

        var valid = $("#create_customer_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        disable_save_button_group.run();

        var btn = $(this).attr("id");

        var customer_details = $('#create_customer_form').serialize();
        var business_address_details = $('#business_address_panel :input').serialize();
        var shipping_address_details = $('#shipping_address_panel :input').serialize();
        var user_account_details = $('#user_account_panel :input').serialize();
        var mobile_iso = $("#customer_mobile").intlTelInput("getSelectedCountryData");
        var telephone_iso = $("#customer_telephone").intlTelInput("getSelectedCountryData");
        var fax_iso = $("#fax").intlTelInput("getSelectedCountryData");
        var params = {

            customer_details: customer_details,
            business_address_details: business_address_details,
            shipping_address_details: shipping_address_details,
            mobile: $('#customer_mobile').intlTelInput("getNumber"),
            telephone: $('#customer_telephone').intlTelInput("getNumber"),
            fax: $('#fax').intlTelInput("getNumber"),
            mobile_country: mobile_iso['iso2'],
            fax_country: fax_iso['iso2'],
            telephone_country: telephone_iso['iso2'],
            user_account_details: user_account_details,
            add_user_account: $('#add_user_account').val()
        };
        var method = '';
        var url = '';

        if ($('#customer_id').val() != '') {
            method = 'PUT';
            url = BASE + 'customer/' + $('#customer_id').val();
        } else {
            url = BASE + 'customer';
            method = 'POST';
        }

        e.preventDefault();
        $.ajax({
            url: url,
            type: method,
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'success') {
                    notification(response);

                    if ($('#isajax').val() == 1)
                    {
                        $("#customer_name_selected").append('<option value="' + response.id + '" selected>' + response.business_name + '</option>');
                        $('#customer_name_selected').val(response.id).trigger('change');
                        $("#customer_id_selected").append('<option value="' + response.id + '" selected>' + response.full_name + '</option>');
                        $('#customer_id_selected').val(response.id).trigger('change');
                        customer_model.close()
                    }
                    if (btn == 'customer-save-and-new') {

                        setTimeout(
                            function () {
                                window.location.href = BASE + 'customer/create';
                            }, 1000);
                    }
                    if (btn == 'customer-save-btn') {

                        $('#customer-save-btn').hide('slow');
                        $('#customer-save-and-new').hide('slow');
                        $('#customer-update-btn').show('slow');
                        $('#customer_comment_content').show('slow');
                        $('#customer_id').val(response.id);
                        $('#ref_id').val(response.id);
                    }
                    if (btn == 'customer-update-btn') {
                        setTimeout(
                            function () {
                                window.location.href = BASE + 'customer';
                            }, 1000);
                    }
                    enable_save_button_group.run();
                } else {
                    enable_save_button_group.run();
                    notification(response);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                enable_save_button_group.run();
                notificationError(xhr, ajaxOptions, thrownError);
            }
        });
        e.preventDefault();
        return false;
    });

});
