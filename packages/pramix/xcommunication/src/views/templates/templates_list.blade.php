@extends('layouts.app')


@section('include_css')
<!-- Data Tables -->



@endsection

@section('content')


    <section class="content-header">
    <div class="header-icon">
        <i class="fa fa-mobile"></i>
    </div>
    <div class="header-title">
 @if($type == 'email')
        <h1>{{__('xcommunication::templates.headings.email_templates')}}</h1>
         @elseif($type == 'sms')
          <h1>{{__('xcommunication::templates.headings.sms_templates')}}</h1>
          @endif
          @if($type == 'email')
                    <small>{{ __('xcommunication::templates.headings.email_list')}}</small>
              @elseif($type == 'sms')
                    <small>{{ __('xcommunication::templates.headings.sms_list')}}</small>
              @endif

        <ol class="breadcrumb hidden-xs">
            <li><a href="{{url('')}}"><i class="pe-7s-home"></i> Home</a></li>
              @if($type == 'email')
                    <li class="active">{{ __('xcommunication::templates.headings.email_list')}}</li>
              @elseif($type == 'sms')
                    <li class="active">{{ __('xcommunication::templates.headings.sms_list')}}</li>
              @endif

        </ol>
    </div>
</section>


    <section class="content">
        <div class="row">
 <div class="col-sm-12">
      <div class="card card-bd lobidrag">
                   <div class="card-header">
                    <div class="btn-group">


                             @if($type == 'email')
                    {{ Html::link('templates/create/email', __('xcommunication::templates.headings.create_email_template'),array('class="btn btn-primary"'))}}
                    @elseif($type == 'sms')
                    @if (Auth::user()->can(['ADD_SMS_TEMPLATE']))
                    {{ Html::link('templates/create/sms', __('xcommunication::templates.headings.create_sms_template'),array('class="btn btn-primary"'))}}
                    @endif

                    @endif
                    </div>
                </div>





            <div class="card-body">
                <input type="hidden" name="template_type" id="template_type" value="{{$type or ''}}">
                <div class="table-responsive">
                    <table id="templatesTable" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{{ __('xcommunication::templates.labels.template_name')}}</th>
                                <th>{{ __('xcommunication::templates.labels.template_description')}}</th>
                                <th>{{ __('xcommunication::templates.labels.template_action')}}</th>
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
    var template_type = $('#template_type').val();
    var templates_list_table = $('#templatesTable').DataTable({
        processing: true,
        serverSide: true,
        'iDisplayLength': 10,
        ajax: BASE + 'gettemplatelist/' + template_type,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [1, 2, 3]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [1, 2, 3]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [1, 2, 3]
                }
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: [1, 2, 3]
                }
            },
        ],
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'template_name', name: 'template_name'},
            {data: 'description', name: 'description'},
            {data: 'action', name: 'action'},
        ]
    });


       $(document).on('click', '#delete_template', function (e) {
        var data = templates_list_table.row($(this).parents('tr')).data();
        var parent = $(this).parents('tr');
        var rowidx = templates_list_table.row(parent).index();
        var delete_confirm = $.confirm({
            title: "{{__('xcommunication::templates.messages.delete_template')}}",
            type: 'red',
            buttons: {
                delete: {
                    text: 'Delete',
                    keys: ['shift', 'alt'],
                    btnClass: 'btn-red',
                    action: function () {

                        e.preventDefault();
                        var params = {

                        };

                        $.ajax({
                            url: BASE + 'templates/' + data['id'],
                            type: 'DELETE',
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'error')
                                {
                                     delete_confirm.close();
                                     notification(response);
                                } else
                                {

                                    delete_confirm.close();

                                     notification(response);

                                     templates_list_table
                                            .row(parent)
                                            .remove()
                                            .draw();

                                }
                            },
                            error: function (errors) {

                            }
                        });
                        e.preventDefault();
                        return false;
                    }
                },
                close: function () {
                }
            }
        });
    });


});
</script>
@endsection