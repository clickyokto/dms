@extends('layouts.app')


@section('include_css')
<!-- Data Tables -->



@endsection

@section('content')


    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>SMS Report</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                <ul class="right-stats" id="mini-nav-right">


                    @if(Auth::user()->can(['SEND_NEW_SMS']))
                    <li>
                        <div class="btn-group">

                            {{ Html::link('sendsms/create', __('xcommunication::sendsms.buttons.send_new_sms'),array('class="btn btn-primary"'))}}


                        </div>
                    </li>
@endif

                </ul>
            </div>
        </div>
    </div>


    <div class="main-container">
        <div class="row gutter">
        <div class="col-sm-12">
            <div class="card" id="sms_list_panel">





                <div class="card-body">

                    <div class="table-responsive">
                        <table id="smsListTable" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Customer</th>
                                    <th>{{ __('xcommunication::sendsms.labels.recipient_phone_no')}}</th>
                                    <th>{{ __('xcommunication::sendsms.labels.message')}}</th>
                                    <th>{{ __('xcommunication::sendsms.labels.send_time')}}</th>
                                    <th>{{ __('xcommunication::sendsms.labels.status')}}</th>
                                    <th>{{ __('xcommunication::sendsms.labels.send_by')}}</th>
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
</div>


@endsection

@section('include_js')



@endsection

@section('custom_script')
    <script>
    $(document).ready(function () {

        var smsListTable = $('#smsListTable').DataTable({
            processing: true,
            serverSide: true,
            'iDisplayLength': 25,
            ajax: BASE + 'listallsendsms',

             order: [[ 0, "desc" ]],
            "pageLength": 20,
            columns: [
                {data: 'id', name: 'id', 'bVisible': false},
                {data: 'customer', name: 'customer'},
                {data: 'recipient_phone_no', name: 'recipient_phone_no'},
                {data: 'message', name: 'message'},
                 {data: 'send_time', name: 'send_time'},
                 {data: 'status', name: 'status'},
                 {data: 'send_by', name: 'send_by'},
            ]
        });





    });
</script>
@endsection