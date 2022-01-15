@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('content')
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">

                        @if(isset($email->id))
                            <h4>Update Email</h4>
                        @else
                            <h4>Create Email</h4>
                        @endif

                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats">
                        <button href="javascript:void(0)" class="btn btn-danger"
                                id="email_save_btn">{{ __('xcustomer::customer.buttons.save')}}</button>
                        <button href="javascript:void(0)" class="btn btn-primary"
                                id="email_save_and_new">{{ __('xcustomer::customer.buttons.save_and_new')}}</button>
                        <button href="javascript:void(0)" class="btn btn-warning"
                                id="email_update_btn">{{ __('xcustomer::customer.buttons.update')}}</button>
                        <button href="javascript:void(0)" class="btn btn-warning"
                                id="email_send_now_btn">Send Now
                        </button>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Top bar ends -->


        <!-- Main container starts -->
        <div class="main-container">


            <!-- Row starts -->
            <div class="row gutter">

                <form action="{{url('/').'/email'}}" method="POST" id="create_email_form">
                    @csrf
                    <input type="hidden" name="isajax" id="isajax" value="{{ Request::ajax() }}">
                    <input type="hidden" id="email_id" name="email_id" value="{{$email->id ?? ''}}">
                    @if(Request::ajax()!=1)
                        <input type="hidden" id="ref_id" name="ref_id" value="{{$email->ref_id ?? ''}}">
                        <input type="hidden" id="ref_type" name="ref_type" value="{{$email->mail_type ?? ''}}">
                    @else
                        <input type="hidden" id="ref_id" name="ref_id" value="{{$email['ref_id'] ?? ''}}">
                        <input type="hidden" id="ref_type" name="ref_type" value="{{$email['mail_type'] ?? ''}}">
                    @endif

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" id="customer-details-card">
                            <div class="card-header">
                                <h4>Email Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row gutter">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <label for="mobile">To :</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            {{ formText('', 'email_address', $email->email ?? $customer_mail ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'email_address'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <label for="mobile">Subject :</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            {{ formText('', 'mail_subject', $email->subject ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'mail_subject'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <label for="mobile">Mail Body :</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            {{formTextArea('', 'mail_body', $email->mail_body ?? '', array( 'class' => 'form-control' , 'id' => 'mail_body', 'rows' => 3))}}
                                        </div>
                                    </div>
                                </div>

                                <div class="row gutter">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                        <div class="form-group">
                                            <label for="mobile">Attachment :</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="form-group">
                                            <div class="checkbox checkbox-inline">
                                                <input type="checkbox" id="add_invoice_pdf" value="add_invoice_pdf"/>
                                                <label
                                                    for="add_invoice_pdf">Add PDF</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
{{--                                <div class="row gutter">--}}
{{--                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">--}}
{{--                                        <div class="form-group">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class="checkbox checkbox-inline">--}}
{{--                                                <input type="checkbox" id="schedule_time" value="schedule_time"/>--}}
{{--                                                <label--}}
{{--                                                    for="schedule_time">Schedule time</label>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <div class="row gutter">--}}
{{--                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">--}}
{{--                                        <div class="form-group">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <input type="datetime-local">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}


                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')


@endsection

@section('include_css')

@endsection

@section('custom_script')

    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            $('#droptown_type_panel').hide();
            if ($('#email_id').val() != '') {
                $('#email_save_btn').hide();
                $('#email_save_and_new').hide();
            } else {
                $('#email_update_btn').hide();
            }

            $("#email_save_btn ,#email_update_btn , #email_save_and_new , #email_send_now_btn").click(function (e) {
                var valid = $("#create_email_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                $('#email_save_btn ,#email_update_btn , #email_save_and_new, #email_send_now_btn').prop('disabled', true);
                var btn = $(this).attr("id");

                var email_details = $('#create_email_form').serialize();
                var attachment = false;
                if ($('#add_invoice_pdf').is(':checked')) {
                    attachment = true;
                } else
                    attachment = false;

                var params = {
                    email_details: email_details,
                    attachment: attachment
                };
                var method = '';
                var url = '';


                if ($('#email_id').val() != '') {
                    method = 'PUT';
                    url = BASE + 'email_sender/' + $('#email_id').val();
                } else {
                    url = BASE + 'email_sender';
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
                            if ($('#isajax').val() == 1) {
                                mail_body_model.close()
                            }
                            notification(response);

                            // if (btn == 'email_save_and_new') {
                            //     setTimeout(
                            //         function () {
                            //             window.location.href = BASE + 'email/create';
                            //         }, 1000);
                            // }
                            // if (btn == 'email_save_btn') {
                            //     $('#customer_id').val(response.id);
                            //     $('#email_save_btn ,#email_update_btn , #email_save_and_new').prop('disabled', false);
                            // }
                            // if (btn == 'email_update_btn') {
                            //     $('#email_save_btn ,#email_update_btn , #email_save_and_new').prop('disabled', false);
                            // }
                        } else {

                            $('#email_save_btn ,#email_update_btn , #email_save_and_new').prop('disabled', false);

                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }
                });
                e.preventDefault();
                return false;
            });

            var wrapper = $(".input_fields_wrap"); //Fields wrapper

            $(".add_field_button").click(function (e) { //on add input button click

                e.preventDefault();

                $(wrapper).append('<div class="row gutter"><div class="col-sm-5"><div class="form-group"><input type="text" class="form-control" name="dropdown_value[]" value="" placeholder="Value"></div></div><div class="col-sm-5"><div class="form-group"><input type="text" class="form-control validate[required]" name="dropdown_text[]" value="" placeholder="Text"></div></div><div class="col-sm-2"><button class="remove_field btn btn-danger btn-sm">Remove</button></div></div>'); //add input box

            });
            $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text

                var option = $(this);

                var delete_confirm = $.confirm({
                    title: 'Delete Delete Option',
                    type: 'red',
                    buttons: {
                        delete: {
                            text: 'Remove',
                            keys: ['shift', 'alt'],
                            btnClass: 'btn-red',
                            action: function () {

                                e.preventDefault();
                                option.closest('.gutter').remove();
                            }
                        },
                        close: function () {
                        }
                    }
                });


                return false;

            })


            $('#email_type').on('change', function () {
                if (this.value == 'TX') {

                    $('#droptown_type_panel').hide(1000);
                    $('#text_type_panel').show(1000);
                } else if (this.value == 'DD') {
                    $('#text_type_panel').hide(1000);
                    $('#droptown_type_panel').show(1000);
                }
            });

            $('#email_type').change();

        });
    </script>
@endsection
