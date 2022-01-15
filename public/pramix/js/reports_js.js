$(document).ready(function ()
{

    $('#custom_date_range').hide();

    $("#date_range").change(function (e) {
        if ($(this).val() == 'custom_date_range') {
            $('#custom_date_range').show("slow");
        } else {
            $('#custom_date_range').hide("slow");
        }
    });
});