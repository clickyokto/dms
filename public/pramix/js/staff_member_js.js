$(document).ready(function () {



    $("#create_staff_member_form").validationEngine();

    if ($('#staff_member_id').val() != '')
    {
        $('#staff_member-save-btn').hide('slow');
        $('#staff_member-save-and-new').hide('slow');
    }
    else {
        $('#staff_member-update-btn').hide('slow');
        $('#staff_member_comment_content').hide('slow');
    }

    if ($('#shipping_address_status').val() == 1)
    {
        $('#add_shipping').prop('checked', true);
    }

    if ($('#add_as_company_name').val() == 'B')
    {
        $('#staff_member_type').val('B').trigger('change')
    }

    $('#add_user_account').prop('checked', true);

    $('#staff_member_type').change(function () {
        if ($('#staff_member_type').val()=='B') {
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



    $("#staff_member-save-btn ,#staff_member-update-btn , #staff_member-save-and-new").click(function (e) {

        var valid = $("#create_staff_member_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        $('#staff_member-save-btn ,#staff_member-update-btn , #staff_member-save-and-new').prop('disabled', true);

        var btn = $(this).attr("id");

        var staff_member_details = $('#create_staff_member_form').serialize();
        var business_address_details = $('#business_address_panel :input').serialize();
        var shipping_address_details = $('#shipping_address_panel :input').serialize();
        var user_account_details = $('#user_account_panel :input').serialize();
        var mobile_iso = $("#staff_member_mobile").intlTelInput("getSelectedCountryData");
        var telephone_iso = $("#staff_member_telephone").intlTelInput("getSelectedCountryData");

        var params = {

            staff_member_details: staff_member_details,
            business_address_details: business_address_details,
            shipping_address_details: shipping_address_details,
            mobile: $('#staff_member_mobile').intlTelInput("getNumber"),
            telephone: $('#staff_member_telephone').intlTelInput("getNumber"),
            mobile_country: mobile_iso['iso2'],
            telephone_country: telephone_iso['iso2'],
            user_account_details: user_account_details,
            add_user_account: $('#add_user_account').val()
        };
        var method = '';
        var url = '';

        if ($('#staff_member_id').val() != '') {
            method = 'PUT';
            url = BASE + 'staff_member/' + $('#staff_member_id').val();
        } else {
            url = BASE + 'staff_member';
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
                        $("#staff_member_name_selected").append('<option value="' + response.id + '" selected>' + response.business_name + '</option>');
                        $('#staff_member_name_selected').val(response.id).trigger('change');
                        $("#staff_member_id_selected").append('<option value="' + response.id + '" selected>' + response.full_name + '</option>');
                        $('#staff_member_id_selected').val(response.id).trigger('change');
                        staff_member_model.close()
                    }
                    if (btn == 'staff_member-save-and-new') {

                        setTimeout(
                            function () {
                                window.location.href = BASE + 'staff_member/create';
                            }, 1000);
                    }
                    if (btn == 'staff_member-save-btn') {

                        $('#staff_member-save-btn').hide('slow');
                        $('#staff_member-save-and-new').hide('slow');
                        $('#staff_member-update-btn').show('slow');
                        $('#staff_member_comment_content').show('slow');
                        $('#staff_member_id').val(response.id);
                        $('#ref_id').val(response.id);
                    }
                    if (btn == 'staff_member-update-btn') {
                        setTimeout(
                            function () {
                                window.location.href = BASE + 'staff_member';
                            }, 1000);
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

});
