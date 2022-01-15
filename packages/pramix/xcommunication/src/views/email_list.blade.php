@extends('layouts.app')


@section('include_css')
<!-- Data Tables -->



@endsection

@section('content')


<section class="content-header">
    <div class="header-icon">
        <i class="fa fa-envelope"></i>
    </div>
    <div class="header-title">

        <h1>{{__('xcommunication::sendemail.headings.send_emails_list')}}</h1>
        <small>{{__('xcommunication::sendemail.headings.emails_send_by_users_and_system')}}</small>


        <ol class="breadcrumb hidden-xs">
            <li><a href="{{url('')}}"><i class="pe-7s-home"></i> Home</a></li>

            <li class="active">{{ __('xcommunication::sendemail.headings.send_sms')}}</li>


        </ol>
    </div>
</section>


<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-bd lobidrag">
                <div class="card-header">
                    <div class="btn-group">

                        {{ Html::link('emails/create', __('xcommunication::sendemail.buttons.send_new_email'),array('class="btn btn-primary"'))}}


                    </div>
                </div>





                <div class="card-body">

                    <div class="table-responsive">
                        <table id="emailListTable" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ __('xcommunication::sendemail.labels.customer')}}</th>
                                    <th>{{ __('xcommunication::sendemail.labels.recipient_email')}}</th>
                                    <th>{{ __('xcommunication::sendemail.labels.send_time')}}</th>
                                    <th>{{ __('xcommunication::sendemail.labels.status')}}</th>
                                    <th>{{ __('xcommunication::sendemail.labels.send_by')}}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>



                            </tbody>
                        </table>
                    </div>


                </div>

                <!-- Row ends -->
            </div>
        </div>
    </div>
</section>


@endsection

@section('include_js')



@endsection

@section('custom_scripts')
<script>
    $(document).ready(function () {

        var emailListTable = $('#emailListTable').DataTable({
            processing: true,
            serverSide: true,
            'iDisplayLength': 25,
            ajax: BASE + 'listallsendemails',
            dom: 'Bfrtip',
             order: [[ 0, "desc" ]],
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5]
                    }
                },
            ],
            columns: [
                {data: 'id', name: 'id', 'bVisible': false},
                {data: 'customer', name: 'customer'},
                {data: 'recipient_email', name: 'recipient_email'},
                {data: 'send_time', name: 'send_time'},
                {data: 'status', name: 'status'},
                {data: 'send_by', name: 'send_by'},
                 {data: 'actions', name: 'actions'},
            ]
        });


          $(document).on('click', '#view_email', function (e) {
 var data = emailListTable.row($(this).parents('tr')).data();
            window.view_email_model = $.confirm({
                title: "{{ __('xcommunication::sendemail.labels.sent_email_preview')}}",
                draggable: true,
                boxWidth: '80%',
                closeIcon: true,
                useBootstrap: false,
                type: 'orange',
                buttons: {

                    close: function () {
                        isHidden: true;// hide the button
                    }
                },
                content: 'url:' + BASE + 'emails/preview_sent_email/' + data['id'],
                onContentReady: function () {

                },
                columnClass: 'small',
            });
        });


    });
</script>
@endsection