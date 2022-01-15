$(document).ready(function () {

    // $("#remarks").Editor();

    $("#cheque_form").validationEngine();

    if ($('#cheque_status').val() == '' || $('#cheque_status').val() == '0') {
        $('#cheque_update_btn').hide('slow');
        $('#cheque_print').hide('slow');

    } else {
        $('#cheque_save_btn').hide('slow');
    }


    $("#cheque_print").click(function (e) {

        if ($('#cheque_id').val() == '') {
            return false;
        }
        var $btn = $(this);
        // $btn.button('loading');

        var params = {
            cheque_id: $('#cheque_id').val(),
        };

        $.ajax({
            url: BASE + 'cheque/cheque_print',
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

        $(".common_auto_load_data").each(function () {
            var $this = $(this);

            if ($this.attr("data-loading_value") != '' && typeof $this.attr("data-loading_value") !== "undefined") {
                var $option = $('<option selected>' + $this.attr("data-loading_value") + '</option>').val($this.attr("data-loading_value"));

                $this.append($option).trigger('change'); // append the option and update Select2
            }

            $this.select2({


                tags: true,
                ajax: {
                    url: BASE + 'get_auto_data',
                    dataType: 'json',
                    delay: 500,
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.payer,
                                    id: item.payer
                                }
                            })
                        };
                    },
                },
            });
        });


    $("#duplicate_cheque").click(function (e) {

        if ($('#cheque_id').val() == '')
            return false;

        var $btn = $(this);
        $btn.button('loading');

        var params = {
            cheque_id: $('#cheque_id').val(),
        };

        $.ajax({
            url: BASE + 'cheque/duplicate_cheque',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                notification(response);
                if (response.status == 'success') {

                    window.location.href = BASE + 'cheque/' + response.cheque_no + '/edit';

                } else {


                }
            },
            error: function (errors) {


            }
        });
    });



    $('#recurring_type').change(function (e) {

        if ($("#recurring_type").val() == 'Y')
        {
            $('#recurring_date_div').show('slow');
            $('#recurring_month_div').show('slow');
        }
        if($("#recurring_type").val() == 'M')
        {
            $('#recurring_month_div').hide('slow');

        }
        if($("#recurring_type").val() == '')
        {
            $('#recurring_date_div').hide('slow');
            $('#recurring_month_div').hide('slow');

        }

    });


    $("#cheque_save_btn, #cheque_update_btn").click(function (e) {

        var valid = $("#cheque_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        var cheque_save_confirm = $.confirm({
            title: 'Save Cheque',
            type: 'blue',
            buttons: {
                draft: {
                    text: 'Save',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-success',
                    action: function () {

                        var cheque_details = $('#cheque_form').serialize();

                        var params = {
                            cheque_details: cheque_details,
                            cheque_id: $('#cheque_id').val(),
                        };

                        $.ajax({
                            url: BASE + 'cheque',
                            type: 'POST',
                            async: false,
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                notification(response);
                                if (response.status == 'success') {
                                        setTimeout(
                                            function () {
                                                window.location.href = BASE + 'cheque';
                                            }, 1000);
                                } else
                                    {


                                }

                            },
                            error: function (xhr, ajaxOptions, thrownError) {

                                notificationError(xhr, ajaxOptions, thrownError);
                            }
                        });
                    }
                },
                complete: {
                    text: 'Cancel',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-primary',
                    action: function () {

                    }
                },

            }
        });
        return false;


    });


});
