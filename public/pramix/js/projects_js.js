$(document).ready(function () {

    if ($('#project_id').val() != '') {
        $('#project_save_btn').hide('slow');
        $('#project_save_and_new').hide('slow');

    } else {
        $('#project_panel li').not('.active').addClass('disabled');
        $('#project_panel li').not('.active').find('a').removeAttr("data-toggle");
        $('#project_update_btn').hide('slow');

    }

    $("#project_save_btn ,#project_update_btn , #project_save_and_new").click(function (e) {
        var valid = $("#create_project_form").validationEngine('validate');
        if (valid != true) {
            return false;
        }

        $('#project_save_btn ,#project_update_btn , #project_save_and_new').prop('disabled', true);

        var btn = $(this).attr("id");


        var params = {

            project_details: $('#create_project_form').serialize(),
            customer_id: $('#customer_id_selected').val()
        };
        var method = '';
        var url = '';

        if ($('#project_id').val() != '') {
            method = 'PUT';
            url = BASE + 'projects/' + $('#project_id').val();
        } else {
            url = BASE + 'projects';
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
                    $('#ref_id').val(response.project_details.id);
                    $('#project_id').val(response.project_details.id);
                    $('#project_update_btn').show('slow');
                    $('#project_panel li').not('.active').addClass('disabled');
                    $('#project_save_btn').hide('slow');
                    $('#project_save_and_new').hide('slow');
                    $('#project_panel li').removeClass('disabled');
                    $('#project_panel li').find('a').attr("data-toggle", "tab")
                    getTableData.run();

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


    window.getTableData = {
        run: function () {


    if ( $('#ref_id').val()!= '') {


                $('#estimateListTable').DataTable({
                    'iDisplayLength': 15,
                    ajax: BASE + 'quotations_list/' + $('#project_id').val(),
                    order: [[0, "desc"]],

                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'quotation_code', name: 'quotation_code'},
                        {data: 'quotation_date', name: 'quotation_date'},
                        {data: 'total', name: 'total', className: 'dt-body-right'},
                        {data: 'status', name: 'status'},
                        {data: 'created_by', name: 'created_by'},
                        {data: 'action', name: 'action'},
                    ]
                });


                $('#invoiceListTable').DataTable({
                    'iDisplayLength': 15,
                    ajax: BASE + 'invoices_list/' + $('#project_id').val()+'/project',
                    order: [[0, "desc"]],

                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
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


                $('#incomesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    'iDisplayLength': 10,
                    ajax: BASE + 'incomelist/' + $('#project_id').val(),


                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'finance_category', name: 'finance_category'},
                        {data: 'general_category', name: 'general_category'},
                        {data: 'name', name: 'name'},
                        {data: 'amount', name: 'amount'},
                        {data: 'date', name: 'date'},
                        {data: 'description', name: 'description'},
                        {data: 'create_by', name: 'create_by'},
                        {data: 'action', name: 'action'},
                    ]
                });

                $('#expensesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    'iDisplayLength': 10,
                    ajax: BASE + 'expenselist/' + $('#project_id').val(),


                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'finance_category', name: 'finance_category'},
                        {data: 'general_category', name: 'general_category'},
                        {data: 'name', name: 'name'},
                        {data: 'amount', name: 'amount'},
                        {data: 'date', name: 'date'},
                        {data: 'description', name: 'description'},
                        {data: 'create_by', name: 'create_by'},
                        {data: 'action', name: 'action'},
                    ]
                });

                $('#inquiryListTable').DataTable({
                    'iDisplayLength': 15,
                    ajax: BASE + 'get_inquiry_list/' + $('#project_id').val(),
                    "order": [[0, 'desc']],

                    columns: [
                        {data: 'id', name: 'id', 'bVisible': false},
                        {data: 'inquiry_code', name: 'inquiry_code'},
                        {data: 'phone', name: 'phone'},
                        {data: 'title', name: 'title'},
                        {data: 'description', name: 'description'},
                        {data: 'priority', name: 'priority'},
                        {data: 'status', name: 'status'},
                        {data: 'user', name: 'user'},
                        {data: 'action', name: 'action'},


                    ]
                });
        $('#paymentListTable').DataTable({
            'iDisplayLength': 15,
            ajax: BASE + 'project/get_payment_list/' + $('#project_id').val(),
            "order": [[0, 'desc']],

            columns: [
                {data: 'id', name: 'id', 'bVisible': false},
                {data: 'invoice_code', name: 'invoice_code'},
                {data: 'payment_date', name: 'payment_date'},
                {data: 'payment_method', name: 'payment_method'},
                {data: 'payment_ref_no', name: 'payment_ref_no'},
                {data: 'payment_remarks', name: 'payment_remarks'},
                {data: 'payment_amount', name: 'payment_amount'},


            ]
        });

            }
        }
    };

    getTableData.run();

});
