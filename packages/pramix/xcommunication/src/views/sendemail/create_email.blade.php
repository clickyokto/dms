@extends('layouts.app')



@section('include_css')
<link href="{{ asset('pramix/xcommunication/fastselect/fastselect.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('pramix/xcommunication/tagsinput/bootstrap-tagsinput.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('datetimepicker/jquery.datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

<section class="content-header">
    <div class="header-icon">
        <i class="fa fa-envelope"></i>
    </div>
    <div class="header-title">


        <h1>{{__('xcommunication::sendemail.headings.sendemail')}}</h1>

        <small>{{ __('xcommunication::sendemail.headings.sendemailstocustomers')}} </small>

        <ol class="breadcrumb hidden-xs">
            <li><a href="{{url('')}}"><i class="pe-7s-home"></i> Home</a></li>



        </ol>
    </div>
</section>



<!-- Dashboard wrapper starts -->
<section class="content">
    <div class="row">
        <!-- Form controls -->
        <div class="col-sm-12">



            <!-- Main container starts -->
            <div class="card card-bd lobidrag">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <form action="{{url('/templates')}}" method="POST" id="create_email_send_form">
                        {!! csrf_field() !!}
                        <input type="hidden" name="sms_id" id="sms_id" value="{{ $template_details->id or '' }}">

                        <div class="card" id="customer-basic-details-card">

                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="radio">
                                        <label><input class="validate[required]" type="radio" name="email_type" value="customers_list">{{ __('xcommunication::sendemail.labels.send_to_customers')}}</label>
                                    </div>
                                    <div id="customers_select_div">
                                        {{ Form::select('customers_select', $customers, '',array('class'=> 'form-control selectpicker', 'id' => 'customers_select','data-live-search'=>'true')) }}
                                    </div>
                                    <div class="radio">
                                        <label><input class="validate[required]" type="radio" name="email_type" value="all_customers">{{ __('xcommunication::sendemail.labels.send_to_all_customers')}}</label>
                                    </div>
                                    <div class="radio">
                                        <label><input class="validate[required]" type="radio" name="email_type" value="other_emails">{{ __('xcommunication::sendemail.labels.send_to_other_emails')}}</label>
                                    </div>
                                    <div id="other_number_div">
                                        <input type="text" value="" name="other_emails" data-role="tagsinput">
                                    </div>
                                    <div class="radio">
                                        <label><input class="validate[required]" type="radio" name="email_type" value="by_industry">{{ __('xcommunication::sendemail.labels.send_to_customers_by_industry')}}</label>
                                    </div>
                                    <div id="by_industry_div" class="col-sm-6">
                                        {{ Form::select('industry', config('xcustomer.industries'), '',array('class'=> 'form-control selectpicker', 'id' => 'industry','data-live-search'=>'true')) }}
                                        <!--div id="customers_select_by_industry_div">
                                            <div>
                                                <select class="multipleSelect form-control" id="customers_select_by_industry" multiple name="customer_ids_by_industry">
                                                </select>
                                            </div>
                                        </div-->

                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive" id="customer_table_div">
                                    <table id="customerListTable" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>{{ __('xcustomer::customer.labels.full_name_business_name')}}</th>
                                                <th>{{ __('xcustomer::customer.labels.full_name')}}</th>
                                                <th>{{ __('xcustomer::customer.labels.telephone')}}</th>
                                                <th>{{ __('xcustomer::customer.labels.mobile')}}</th>
                                                <th>{{ __('xcustomer::customer.labels.email')}}</th>
                                                <th>{{ __('xcustomer::customer.labels.nic')}}</th>
                                                <th>{{ __('xcustomer::customer.labels.industry')}}</th>
                                                <th></th>

                                            </tr>
                                        </thead>

                                        <tbody>



                                        </tbody>
                                    </table>
                                </div>
                                <hr>

                                <div class="form-group" id="shordcodesdiv">
                                    <label>{{ __('xcommunication::templates.labels.shortcodes')}}</label>
                                    <p id="shortcodes">{{$shortcodes or ''}}</p>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="description">{{ __('xcommunication::templates.labels.template_message')}}</label>
                                            <?php $editor_url = url('/email/email_editor'); ?>
                                            <iframe src="{{$editor_url }}" width="100%" height="850px" id="emailbuilder">
                                                <p>Your browser does not support iframes.</p>
                                            </iframe>
                                        </div>

                                    </div>
                                </div>

                            </div></div>



                        @if(isset($template_details->id))
                        <a href="javascript:void(0)" class="btn btn-danger" id="template_update_btn">{{ __('xcommunication::common.buttons.btn_update')}}</a>
                        @else
                        <a href="javascript:void(0)" class="btn btn-success" id="email_send_btn">{{ __('xcommunication::templates.buttons.send')}}</a>
                        @endif

                        <a href="javascript:void(0)" class="btn btn-warning" id="sms_send_later_btn" ><span class="icon-clock2"></span> {{ __('xcommunication::templates.labels.send_later')}}</a>

                    </form>
                </div>

            </div>

            <!-- Row starts -->
        </div></div>
</section>


<!-- Main container ends -->
<div class="modal fade " id="campain_schedule_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ __('xcommunication::sendemail.labels.shedule_your_campaign')}}</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label class="control-label" for="email_attachment">{{ __('xcommunication::sendemail.labels.dilivery_date_time')}}</label></br>
                    <input type="text" class="form-control validate[required]" id="schedule_date" name="calender">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-default" id="schedule_campaign_btn">{{ __('xcommunication::sendemail.buttons.schedule_campaign')}}</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Dashboard wrapper ends -->

@endsection

@section('include_js')
<script src="{{ asset('pramix/xcommunication/fastselect/fastselect.standalone.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('pramix/xcommunication/tagsinput/bootstrap-tagsinput.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('datetimepicker/jquery.datetimepicker.full.min.js') }}" type="text/javascript"></script>
@endsection


@section('custom_scripts')


<script>




$(document).ready(function () {

   window._emailBuilder;

    $("#customers_select").append($('<option>', {value: '', text: '{{ __('xcommunication::sendemail.labels.select_customer')}}'}));
    $('#customers_select').selectpicker('val', '');

    $("#industry").append($('<option>', {value: '', text: '{{ __('xcommunication::sendemail.labels.select_industry')}}'}));
    $('#industry').selectpicker('val', '');

    $('.selectpicker').selectpicker('refresh');

    $('#schedule_date').datetimepicker({
    //inline: true,
    format: 'Y/m/d H:i',
    timepicker:true,
    });

    //var customers_select = $('#customers_select').fastselect();
    //var customers_select_by_industry = $('#customers_select_by_industry').fastselect();


    /*
     function trainClick(){
     //
     }*/

    $('#customers_select_div').hide();
    $('#other_number_div').hide();
    $('#by_industry_div').hide();


    var customer_list_table =   $('#customerListTable').DataTable({
          processing: true,
          serverSide: false,
          'iDisplayLength': 15,
           'order': [[ 1, "desc" ]],
          //ajax: '{!! route('get.customers') !!}',

          columns: [
              {data: 'id', name: 'id', 'bVisible': false},
              {data: 'business_name', name: 'business_name'},
              {data: 'fullName', name: 'fullName'},
              {data: 'telephone', name: 'telephone', 'bVisible': false},
              {data: 'mobile', name: 'mobile'},
              {data: 'email', name: 'email'},
              {data: 'nic', name: 'nic'},
              {data: 'industry', name: 'industry'},
              {data: 'action', name: 'action'},
          ]
      });



    $("#email_send_btn, #schedule_campaign_btn").click(function (e) {
        var valid = $("#create_email_send_form").validationEngine('validate');
        var sendTime;
        if (valid != true) {
            return false;
        }
        var btn = $(this).attr("id");



        $('#email_send_btn').addClass('disabled');

        if (btn == 'campain_email_send_now_btn')
        {
            sendTime = 'now';
        } else {
            sendTime = $('#schedule_date').val();
        }

         var content = _emailBuilder;
            message = content.getContentHtml();
            editor_content= $("iframe").contents().find('.bal-content-wrapper').html();

        var params = {
            campain_details: $('#create_email_send_form').serialize(),
            sendTime: sendTime,
            sendCustomers: $('#customers_select').val(),
            message: message,
            editor_content: editor_content,
            data_table_content: customer_list_table.column( 0 ).data().toArray(),
        };
        var url;
        var method;


        url = BASE + 'emails',
                method = 'POST';



        e.preventDefault();
        $.ajax({
            url: url,
            type: method,
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'error')
                {
                    notification(response);
                    $('#campain_sms_send_now_btn').removeClass('disabled');
                } else
                {

                    notification(response);
                    var view_url;

                   view_url = 'emails';
                            setTimeout(
                                    function ()
                                    {
                                        window.location.href = BASE + view_url;
                                    }, 200
                                    );


                }
            },
            error: function (errors) {

            }
        });
        e.preventDefault();
        return false;
    });

    $('#sms_message').keyup(function (e) {

        $('#character_count').html($(this).val().length);
    });

    //var content = _emailBuilder;
    //    content.setLoadPageHtml('\n                &lt;div class=&quot;bal-content-main lg-width&quot;&gt;&lt;div class=&quot;email-editor-elements-sortable ui-sortable&quot;&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;&lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n          &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;background,text-style,padding,image-settings&quot; data-last-type=&quot;text-style&quot;&gt;\n              &lt;tbody&gt;\n                  &lt;tr&gt;\n                      &lt;td align=&quot;left&quot; class=&quot;page-header element-content&quot; style=&quot;padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;text-align:center&quot;&gt;\n\n                          &lt;table width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot;&gt;\n                            &lt;tbody&gt;&lt;tr&gt;\n                              &lt;td style=&quot;text-align:left&quot; contenteditable=&quot;true&quot; class=&quot;&quot;&gt;\n                                &lt;img border=&quot;0&quot; class=&quot;content-image&quot; src=&quot;http://emailbuilder.cidcode.net/demo/uploads/upload-file-10171418102016.png&quot; style=&quot;display: inline-block; margin: 0px; width: 102px; height: 19px;&quot;&gt;\n                              &lt;/td&gt;\n                              &lt;td contenteditable=&quot;true&quot; style=&quot;text-align: right; font-size: 10px;&quot; class=&quot;&quot;&gt;\n                                 Envato Pty Ltd (ABN: 11 119 159 741)&lt;br&gt;\nEnvato, PO Box 16122, Collins Street West, Victoria 8007, Australia\n                              &lt;/td&gt;\n                            &lt;/tr&gt;\n                          &lt;/tbody&gt;&lt;/table&gt;\n                      &lt;/td&gt;\n                  &lt;/tr&gt;\n              &lt;/tbody&gt;\n          &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; align=&quot;center&quot; data-types=&quot;background,padding,border-radius,image-settings&quot; data-last-type=&quot;padding&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td align=&quot;left&quot; class=&quot;image element-content&quot; contenteditable=&quot;true&quot; style=&quot;padding: 30px 0px 20px; text-align: center; background-color: rgb(49, 44, 41);&quot;&gt;\n                            &lt;img class=&quot;content-image &quot; style=&quot;width: 400px; height: 80px;&quot; src=&quot;http://emailbuilder.cidcode.net/demo/uploads/upload-file-36281412102016.gif&quot;&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;background,border-radius,text-style,padding&quot; data-last-type=&quot;text-style&quot; style=&quot;background-color:#FFFFFF&quot; align=&quot;center&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;element-content&quot; align=&quot;left&quot; style=&quot;padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;font-family:Arial;font-size:13px;color:#000000;line-height:22px&quot;&gt;\n                            &lt;div contenteditable=&quot;true&quot; class=&quot;test-text&quot; style=&quot;text-align: center; font-size: 14px; color: rgb(96, 102, 109);&quot;&gt;\n                               Now, nearly a year in the making, comes the powerful Infinite multi-purpose theme&mdash;recently released and &lt;b&gt;30% off for a limited time.&lt;/b&gt;&amp;nbsp;&lt;div&gt;&amp;nbsp;                               &lt;br&gt;With a super-powerful drag and drop page builder, huge selection of page templates and layouts for extensive customization, and 20 high-quality demos, it has almost every industry category covered. \n                            &lt;/div&gt;&lt;/div&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; align=&quot;center&quot; style=&quot;background-color:#FFFFFF&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;divider-simple&quot; style=&quot;padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;&quot;&gt;\n                            &lt;div style=&quot;border-top: 1px solid #DADFE1;&quot;&gt;&lt;/div&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;background,text-style,padding,image-settings&quot; data-last-type=&quot;padding&quot; style=&quot;background-color:#FFFFFF&quot; align=&quot;center&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;element-content&quot; contenteditable=&quot;true&quot; style=&quot;padding: 10px 50px; font-family: Arial; font-size: 13px; color: rgb(0, 0, 0); line-height: 22px; text-align: left;&quot;&gt;\n\n                            &lt;img border=&quot;0&quot; class=&quot;content-image  &quot; align=&quot;left&quot; src=&quot;http://emailbuilder.cidcode.net/demo/uploads/upload-file-2219419102016.jpg&quot; style=&quot;display: inline-block; margin: 0px; padding: 0px 10px 0px 0px; width: 280px; height: 140px;&quot;&gt;\n                            &lt;b&gt;&lt;span id=&quot;span_73108&quot; class=&quot;&quot; style=&quot;font-size: 24px;&quot;&gt;&lt;/span&gt;&lt;span id=&quot;span_46608&quot; class=&quot;&quot;&gt;Bal &ndash; Email Newsletter Builder&amp;nbsp;&lt;br&gt;&lt;/span&gt;&lt;br&gt;&lt;/b&gt;&lt;div style=&quot;margin: 0px 0px 10px 0px; line-height: 22px;&quot;&gt;With 21 different elements create your own Creative Email Newsletter.If you know HTML or prefer to design your own emails,Bal &ndash; Email Newsletter Builder is an excellent newsletter builder for you.Bal &ndash; Email Newsletter Builder also offers about 3 templates to help beginners send professional-looking emails\n                            &lt;/div&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 \n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; align=&quot;center&quot; style=&quot;background-color:#FFFFFF&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;divider-simple&quot; style=&quot;padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;background-color:#FFFFFF;&quot;&gt;\n                            &lt;div style=&quot;border-top: 1px solid #DADFE1;&quot;&gt;&lt;/div&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;background,border-radius,text-style,padding,image-settings&quot; data-last-type=&quot;image-settings&quot; style=&quot;background-color:#FFFFFF&quot; align=&quot;center&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;element-content&quot; contenteditable=&quot;true&quot; style=&quot;text-align:left;padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;font-family:Arial;font-size:13px;color:#000000;line-height:22px&quot;&gt;\n\n                            &lt;img border=&quot;0&quot; align=&quot;right&quot; class=&quot;content-image &quot; src=&quot;http://emailbuilder.cidcode.net/demo/uploads/upload-file-4133419102016.jpg&quot; style=&quot;display: block; margin: 0px; padding: 0px 0px 0px 10px; width: 280px; height: 140px;&quot;&gt;\n\n                            &lt;div style=&quot;margin: 0px 0px 10px 0px; line-height: 22px;&quot;&gt;&lt;div style=&quot;margin: 0px 0px 10px 0px; line-height: 22px;&quot;&gt;&lt;span id=&quot;span_57945&quot; class=&quot;&quot;&gt;&lt;b&gt;Avada | Responsive Multi-Purpose Theme&lt;/b&gt;&lt;/span&gt;&lt;/div&gt;&lt;div style=&quot;margin: 0px 0px 10px 0px; line-height: 22px;&quot;&gt;&lt;span id=&quot;span_54469&quot; class=&quot;&quot;&gt;Avada is the #1 selling WordPress theme on the market. Simply put, it is the most versatile, easy to use multi-purpose WordPress theme. It is truly one of a kind, other themes can only attempt to include the vast network options that Avada includes. Avada is all about building unique, creative and professional websites through industry leading options network without having to touch a line of code&lt;/span&gt;&lt;/div&gt;&lt;/div&gt;\n\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;background,border-radius,text-style,padding,hyperlink&quot; data-last-type=&quot;text-style&quot; style=&quot;background-color:#FFFFFF&quot; align=&quot;center&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;element-content&quot; align=&quot;left&quot; style=&quot;padding: 10px 50px; font-family: Arial; font-size: 13px; color: rgb(0, 0, 0); line-height: 22px; text-align: center;&quot;&gt;\n\n                            &lt;a contenteditable=&quot;true&quot; style=&quot;margin-top: 10px; background-color: rgb(131, 181, 65); font-family: Arial; color: rgb(255, 255, 255); display: inline-block; border-radius: 6px; text-align: center; padding: 12px 20px; text-decoration: none; font-size: 16px;&quot; class=&quot;button-1 hyperlink&quot; href=&quot;#&quot; data-default=&quot;1&quot;&gt;\n                                GET 30% OFF\n                            &lt;/a&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;background,border-radius,text-style,padding&quot; data-last-type=&quot;text-style&quot; style=&quot;background-color:#FFFFFF&quot; align=&quot;center&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;element-content&quot; align=&quot;left&quot; style=&quot;padding-left:50px;padding-right:50px;padding-top:10px;padding-bottom:10px;font-family:Arial;font-size:13px;color:#000000;line-height:22px&quot;&gt;\n                            &lt;div contenteditable=&quot;true&quot; class=&quot;test-text&quot; style=&quot;text-align: center; font-size: 18px; color: rgb(115, 102, 109); font-style: italic;&quot;&gt;\n\n*Offer expires 11:59pm PDT Friday October 21 2016.\n\n&lt;/div&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;div class=&quot;bal-elements-list-item ui-draggable ui-draggable-handle&quot; style=&quot;width: auto; height: auto;&quot;&gt;\n                                                 &lt;div class=&quot;sortable-row&quot;&gt;\n                                                     &lt;div class=&quot;sortable-row-container&quot;&gt;\n                                                         &lt;div class=&quot;sortable-row-actions&quot;&gt;\n                                                             &lt;div class=&quot;row-move row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-arrows-alt&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-remove row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-remove&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                             &lt;div class=&quot;row-duplicate row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-files-o&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n																														 &lt;div class=&quot;row-code row-action&quot;&gt;\n                                                                 &lt;i class=&quot;fa fa-code&quot;&gt;&lt;/i&gt;\n                                                             &lt;/div&gt;\n                                                         &lt;/div&gt;\n                                                         &lt;div class=&quot;sortable-row-content&quot;&gt;\n                                                             \n            &lt;table class=&quot;main&quot; width=&quot;100%&quot; cellspacing=&quot;0&quot; cellpadding=&quot;0&quot; border=&quot;0&quot; data-types=&quot;social-content,background,padding&quot; data-last-type=&quot;social-content&quot; style=&quot;background-color:#FFFFFF;&quot;&gt;\n                &lt;tbody&gt;\n                    &lt;tr&gt;\n                        &lt;td class=&quot;element-content  social-content&quot; style=&quot;padding:20px 50px;text-align:center&quot;&gt;\n                            &lt;a href=&quot;#insta3&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;instagram&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/insta-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;pinterest&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/pin-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;google-plus&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/gplus-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;facebook&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/fb-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;twitter&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/twt-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;linkedin&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/in-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;youtube&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/ytb-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                            &lt;a href=&quot;#&quot; style=&quot;border: none;display: inline-block;margin-top: 10px;&quot; class=&quot;skype&quot;&gt;\n                                &lt;img border=&quot;0&quot; src=&quot;http://emailbuilder.cidcode.net/demo/assets/images/social-icons/skype-03.png&quot; width=&quot;32&quot;&gt;\n                            &lt;/a&gt;\n                        &lt;/td&gt;\n                    &lt;/tr&gt;\n                &lt;/tbody&gt;\n            &lt;/table&gt;\n\n                                                         &lt;/div&gt;\n                                                     &lt;/div&gt;\n                                                 &lt;/div&gt;\n                                                 &lt;/div&gt;&lt;/div&gt;&lt;/div&gt;\n            ');





    $("#sms_send_later_btn").click(function (e) {

        $('#campain_schedule_modal').modal('show');
    });

    $('#shordcodesdiv').hide();

    $('input[type=radio][name=email_type]').change(function () {

        if (this.value == 'customers_list') {
            customer_list_table.clear().draw(false);
            $('#shordcodesdiv').show(800);
            $('#customers_select_div').show(800);
            $('#other_number_div').hide(800);
            $('#by_industry_div').hide(800);
            $('#customer_table_div').show(800);
        } else if (this.value == 'other_emails') {
            customer_list_table.clear().draw(false);
            $('#shordcodesdiv').hide(800);
            $('#other_number_div').show(800);
            $('#customers_select_div').hide(800);
            $('#by_industry_div').hide(800);
            $('#customer_table_div').hide(800);
        } else if (this.value == 'all_customers') {
            $('#shordcodesdiv').show(800);
            $('#other_number_div').hide(100);
            $('#customers_select_div').hide(800);
            $('#by_industry_div').hide(800);
            $('#customer_table_div').show(800);
        }else if (this.value == 'by_industry') {
            customer_list_table.clear().draw(false);
            $('#shordcodesdiv').show(800);
            $('#other_number_div').hide(100);
            $('#customers_select_div').hide(800);
            $('#by_industry_div').show(800);
            $('#customer_table_div').show(800);
        }
    });


    $('#industry').change(function (e) {
        if($('#industry').val() === ''){
            return false;
        }
        else{
            getCustomersDetails('industry');
        }
    });

    $('input[type=radio][name=email_type][value=all_customers]').change(function (e) {
        customer_list_table.clear().draw(false);
        getCustomersDetails('all');
    });

    $('#customers_select').change(function (e) {
        if($('#customers_select').val() === ''){
            return false;
        }
        else{
            getCustomersDetails('customer_list');
        }
    });

    function getCustomersDetails($filtering_method){
        var customer_id = $('#customers_select').val();
        var params = {
            customers_filtering_method: $filtering_method,
            customers_list: $('#customers_select').val(),
            industry_id: $('#industry').val()
        };
        //e.preventDefault();
        $.ajax({
            url: BASE + 'emails/get_customers_by_type',
            type: 'POST',
            dataType: 'JSON',
            data: $.param(params),
            success: function (response) {
                if (response.status == 'error')
                {
                } else
                {
                    var column_data = response.data;
                    customer_list_table.rows.add(column_data).draw(false);
                    if ($("input:radio[name='email_type']:checked").val() === 'customers_list'){
                        $('#customers_select').find('[value=' + customer_id + ']').remove();
                        $('#customers_select').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh');
                    }

                          if ($("input:radio[name='email_type']:checked").val() === 'by_industry'){
                        $('#industry').find('[value=' + $('#industry').val() + ']').remove();
                        $('#industry').selectpicker('val', '');
                        $('.selectpicker').selectpicker('refresh');
                    }
                }
            },
            error: function (errors) {

            }
        });
       // e.preventDefault();
        return false;

    }

    $(document).on('click', '#delete_customer', function (e) {
        var data = customer_list_table.row($(this).parents('tr')).data();
        var parent = $(this).parents('tr');
        customer_list_table
                .row(parent)
                .remove()
                .draw();

        if ($("input:radio[name='email_type']:checked").val() === 'customers_list'){
            $("#customers_select").append($('<option>', {value: data.id, text: data.fname + ' ' + data.lname}));
            $('#customers_select').selectpicker('val', '');
            $('.selectpicker').selectpicker('refresh');
        }

    });
});
</script>
@endsection
