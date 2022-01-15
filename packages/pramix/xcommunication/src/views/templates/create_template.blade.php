@extends('layouts.app')

@if($type == 'email')
@elseif($type == 'sms')
@endif

@section('include_css')

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
        <small>{{ __('xcommunication::templates.headings.create_email_template')}}</small>
        @elseif($type == 'sms')
        <small>{{ __('xcommunication::templates.headings.create_sms_template')}} </small>
        @endif
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{url('')}}"><i class="pe-7s-home"></i> Home</a></li>
            @if($type == 'email')
            <li class="active">{ __('xcommunication::templates.headings.create_email_template')}}</li>
            @elseif($type == 'sms')
            <li class="active">{{__('xcommunication::templates.headings.create_sms_template')}}</li>
            @endif
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
                    <div class="btn-group">
                        <a class="btn btn-primary" href="@if($type=='sms'){{URL::to('/templates/sms')}} @elseif($type=='email') {{URL::to('/templates/email')}} @endif">  {!!__('xcommunication::templates.buttons.templates_list')!!}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{url('/templates')}}" method="POST" id="create_template_form">
                        {!! csrf_field() !!}
                        <input type="hidden" name="template_id" id="template_id" value="{{ $template_details->id or '' }}">
                        <input type="hidden" name="template_type" id="template_type" value="{{ $type or '' }}">
                        <div class="card" id="customer-basic-details-card">

                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="group_name">{{ __('xcommunication::templates.labels.template_name')}}</label>
                                            <input type="text" class="form-control validate[required]" id="template_name" name="template_name" value="{{ $template_details->template_name or '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="description">{{ __('xcommunication::templates.labels.template_description')}}</label>
                                            <textarea class="form-control input-lg" name="description">{{ $template_details->description or '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @if($type == 'sms')
                                <div class="form-group">
                                    <label>{{ __('xcommunication::templates.labels.shortcodes')}}</label>
                                    <p id="shortcodes">{{$shortcodes or ''}}</p>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="description">{{ __('xcommunication::templates.labels.template_message')}}</label>
                                            <textarea maxlength="160" class="form-control input-lg" name="message" id="sms_message">{{ $template_details->content or '' }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <div id="word_count" class="pull-right">
                                                <strong>{{ __('xcommunication::templates.labels.message_character_count')}} : <span id="character_count">0</span></strong>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div></div>
                        @if($type == 'email')
                        <div class="form-group">
                            <label>{{ __('xcommunication::templates.labels.shortcodes')}}</label>
                            <p id="shortcodes">{{$shortcodes or ''}}</p>
                        </div>
                        <?php
                        if (isset($template_details->id)) {
                            $editor_url = url('/template/email_editor/' . $template_details->id);
                        } else
                            $editor_url = url('/template/email_editor');
                        ?>


                        <iframe src="{{$editor_url }}" width="100%" height="850px" id="emailbuilder">
                            <p>Your browser does not support iframes.</p>
                        </iframe>
                        @endif


                        @if(isset($template_details->id))
                        <a href="javascript:void(0)" class="btn btn-danger" id="template_update_btn">{{ __('xcommunication::common.buttons.btn_update')}}</a>
                        @else
                        <a href="javascript:void(0)" class="btn btn-success" id="template_save_btn">{{ __('xcommunication::common.buttons.btn_save')}}</a>
                        @endif
                    </form>
                </div>

            </div>

            <!-- Row starts -->
        </div></div>
</section>
<!-- Main container ends -->


<!-- Dashboard wrapper ends -->

@endsection

@section('include_js')

@endsection


@section('custom_scripts')


<script>




    $(document).ready(function () {


 @if(isset($template_details->id))
  $("#template_name").attr("readonly", "true");
  @endif

        window._emailBuilder;


        /*
         function trainClick(){
         //
         }*/


        $("#template_save_btn, #template_update_btn").click(function (e) {
            //console.log($('#emailbuilder')[0].contentDocument);

            var valid = $("#create_template_form").validationEngine('validate');
            if (valid != true) {
                return false;
            }
            var btn = $(this).attr("id");
            var method = '';
            var url = '';
            var message;
            var type = $('#template_type').val();
            var editor_content = '';

            $('#template_save_btn, #template_update_btn').addClass('disabled');

            if (type == 'email')
            {
                var content = _emailBuilder;
                message = content.getContentHtml();
                editor_content = $("iframe").contents().find('.bal-content-wrapper').html();


            } else if (type == 'sms')
            {
                message = $('#sms_message').val();
            }



            var params = {
                template_details: $('#create_template_form').serialize(),
                message: message,
                type: type,
                editor_content: editor_content
            };

            if ($('#template_id').val() != '')
            {
                url = BASE + 'templates/' + $('#template_id').val();
                method = 'PUT';
            } else {
                url = BASE + 'templates';
                method = 'POST';
            }

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
                        $('#template_save_btn, #template_update_btn').removeClass('disabled');
                    } else
                    {
                        $('#template_id').val(response.id);
                        notification(response);
                        var view_url;
                        if (btn == 'template_save_btn' || btn == 'template_update_btn')
                        {
                            view_url = 'templates/' + type;
                        } else if (btn == 'btn_save_and_new')
                        {
                            view_url = 'templates/create/' + type;
                        }
                        setTimeout(
                                function ()
                                {
                                    window.location.href = BASE + view_url;
                                }, 1000
                                );
                    }
                },
                error: function (errors) {
                    var msg = '';
                    if (errors && errors.responseText) { //ajax error, errors = xhr object
                        msg = errors.responseText;
                    } else { //validation error (client-side or server-side)
                        $.each(errors, function (k, v) {
                            msg += k + ": " + v + "<br>";
                        });
                    }
                    $('#msg').removeClass('alert-success').addClass('alert-error').html(msg).show();
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


    });
</script>
@endsection
