$(document).ready(function ()
{
    $("#create_customer_form").validationEngine();

    var selecteddistrict = new Option('Please select district', '', true, true);
    $('#business_district_id ,#shipping_district_id').append(selecteddistrict).trigger('change');


    var selectedcity = new Option('Please select city', '', true, true);
    $('#business_city_id , #shipping_city_id').append(selectedcity).trigger('change');

    if ($('#supplier_id').val() != '')
    {
        $('#supplier-save-btn').hide('slow');
        $('#supplier-save-and-new').hide('slow');
    }
    else {
        $('#supplier-update-btn').hide('slow');
        $('#supplier_comment_content').hide('slow');
    }

    if ($('#shipping_address_status').val() == 1)
    {
        $('#add_shipping').prop('checked', true);
    }

    $('#add_shipping').change(function () {
        if ($('#add_shipping').is(':checked')) {
            $('#shipping_address_panel').show(1000);
        } else {
            $('#shipping_address_panel').hide(1000);
        }
    });


    $("#supplier-save-btn ,#supplier-update-btn , #supplier-save-and-new").click(function (e) {
        var valid = $("#create_supplier_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        $('#supplier-save-btn ,#supplier-update-btn , #supplier-save-and-new').prop('disabled', true);
        var btn = $(this).attr("id");

        var supplier_details = $('#create_supplier_form').serialize();
        var business_address_details = $('#business_address_panel :input').serialize();
        var shipping_address_details = $('#shipping_address_panel :input').serialize();
        var mobile_iso = $("#supplier_mobile").intlTelInput("getSelectedCountryData");
        var telephone_iso = $("#supplier_telephone").intlTelInput("getSelectedCountryData");

        var params = {

            supplier_details: supplier_details,
            business_address_details: business_address_details,
            shipping_address_details: shipping_address_details,
            mobile: $('#supplier_mobile').intlTelInput("getNumber"),
            telephone: $('#supplier_telephone').intlTelInput("getNumber"),
            mobile_country: mobile_iso['iso2'],
            telephone_country: telephone_iso['iso2'],
        };
        var method = '';
        var url = '';

        if ($('#supplier_id').val() != '') {
            method = 'PUT';
            url = BASE + 'supplier/' + $('#supplier_id').val();
        } else {
            url = BASE + 'supplier';
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
                        $("#supplier_code_selected").append('<option value="'+ response.id +'" selected>'+ response.business_name +'</option>');
                        $('#supplier_code_selected').val(response.id).trigger('change');
                        supplier_model.close()
                    }

                    if (btn == 'supplier-save-and-new') {

                        setTimeout(
                            function () {
                                window.location.href = BASE + 'supplier/create';
                            }, 1000);
                    }
                    if (btn == 'supplier-save-btn') {

                        $('#supplier-save-btn').hide('slow');
                        $('#supplier-save-and-new').hide('slow');
                        $('#supplier-update-btn').show('slow');
                        $('#supplier_comment_content').show('slow');
                        $('#supplier_id').val(response.id);
                        $('#ref_id').val(response.id);
                    }
                    if (btn == 'supplier-update-btn') {
                        setTimeout(
                            function () {
                                window.location.href = BASE + 'supplier';
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
