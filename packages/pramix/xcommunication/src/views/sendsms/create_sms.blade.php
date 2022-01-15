@extends('layouts.app')



@section('include_css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
          rel="stylesheet" media="screen"/>
    <link href="{{ asset('plugins/bootstrap_tagsinput_master/bootstrap-tagsinput.css') }}" rel="stylesheet"
          type="text/css"/>

@endsection

@section('content')


    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>Send SMS</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                <ul class="right-stats" id="mini-nav-right">


                    <li>
                        @if(isset($template_details->id))
                            <a href="javascript:void(0)" class="btn btn-primary"
                               id="template_update_btn">{{ __('xcommunication::common.buttons.btn_update')}}</a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-success"
                               id="sms_send_btn">{{ __('xcommunication::templates.buttons.send')}}</a>
                        @endif
                    </li>


                </ul>
            </div>
        </div>
    </div>


    <div class="main-container">
        <div class="row gutter">
            <div class="col-md-8 col-md-offset-2">


                <div class="card" id="create_sms_panel">

                    <div class="card-body">
                        <form action="{{url('/templates')}}" method="POST" id="create_sms_send_form">
                            {!! csrf_field() !!}
                            <input type="hidden" name="sms_id" id="sms_id" value="{{ $template_details->id ?? '' }}">

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">

                                        <div class="radio">
                                            <input class="validate[required]" type="radio" name="sms_type"
                                                   value="send_to_all">
                                            <label for="male">Send to all customers</label>
                                        </div>


                                        <div class="radio">
                                            <input class="validate[required]" type="radio" name="sms_type"
                                                   value="select_candidates" id="select_candidates_type">
                                            <label for="male">Select Customers</label>
                                        </div>
                                        <div id="customers_select_div">
                                            {{ Form::select('candidates_select', $candidates, '',array('class'=> 'form-control selectpicker', 'id' => 'candidates_select','data-live-search'=>'true')) }}
                                        </div>
                                        <div class="radio">
                                            <input class="validate[required]" type="radio" name="sms_type"
                                                   value="send_to_other_number_type">
                                            <label for="male">Send to other numbers</label>
                                        </div>
                                        <div id="other_number_div">
                                            <input type="text" value="" name="other_numbers" data-role="tagsinput">
                                        </div>



                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h4>Available SMS : {{$no_of_sms ?? ''}}</h4>

                                    <div class="btn-group" data-toggle="buttons" id="job_found_status_radion">

                                        <label class="btn job_status btn-default active">
                                            <input type="radio" name="send_time_type" value="send_now" id="option1"
                                                   autocomplete="off" checked>
                                            Send now
                                        </label>
                                        <label class="btn job_status btn-default ">
                                            <input type="radio" name="send_time_type" value="send_later" id="option2"
                                                   autocomplete="off"
                                            > Send later
                                        </label>


                                    </div>
                                    <hr>
                                    <div id="send_later_panel">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Select time to send sms"
                                                   id="send_time" name="send_time" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>





                            <div class="table-responsive" id="customer_table_div">
                                <table id="customerListTable" class="table table-striped table-bordered no-margin"
                                       cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Customer Code</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Mobile no.1</th>
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
                                <p id="shortcodes">{{$shortcodes ?? ''}}</p>
                            </div>
                            <div class="row gutter">
                                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="description">Message</label>
                                        <textarea maxlength="160" class="form-control input-lg validate[required]"
                                                  rows="3" name="message"
                                                  id="sms_message">{{ $template_details->content ?? '' }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <div id="word_count" class="pull-right">
                                            <strong>{{ __('xcommunication::templates.labels.message_character_count')}}:
                                                <span id="character_count">0</span> / 160</strong>
                                        </div>

                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>

                </div>

                <!-- Row starts -->
            </div>
        </div>
    </div>



@endsection

@section('include_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="{{ asset('plugins/bootstrap_tagsinput_master/bootstrap-tagsinput.min.js') }}"
            type="text/javascript"></script>

@endsection


@section('custom_script')


    <script>


        $(document).ready(function () {

            $('#send_later_panel').hide();

            $("#candidates_select").append($('<option>', {
                value: '',
                text: 'Select candidate'
            }));
            // $('#candidates_select').selectpicker('val', '');
            //
            //
            // $('.selectpicker').selectpicker('refresh');

            $('#send_time').datetimepicker({
                // inline: true,

                timepicker: true,
            });


            $('#customers_select_div').hide();
            $('#other_number_div').hide();
            $('#by_industry_div').hide();

            var candidates_list_table = $('#customerListTable').DataTable({
                processing: true,
                serverSide: false,
                'iDisplayLength': 15,
                'order': [[1, "desc"]],

                "lengthChange": false,

                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'job_found', name: 'job_found'},
                    {data: 'candidate_code', name: 'candidate_code'},
                    {data: 'family_name', name: 'family_name'},
                    {data: 'nic_no', name: 'nic_no'},
                    {data: 'passport_no', name: 'passport_no'},
                    {data: 'mobile_no1', name: 'mobile_no1'},
                    {data: 'action', name: 'action'},
                ]
            });

            $("#sms_send_btn").click(function (e) {
                var valid = $("#create_sms_send_form").validationEngine('validate');
                var sendTime;
                if (valid != true) {
                    return false;
                }
                var btn = $(this);
                  btn.button('loading');

                if ($('input[type=radio][name=send_time_type]:checked').val() == 'send_now') {
                    sendTime = 'now';
                }
                else {
                    sendTime = $('#send_time').val();
                }
                var params = {
                    campain_details: $('#create_sms_send_form').serialize(),
                    sendTime: sendTime,
                    sendCustomers: $('#candidates_select').val(),
                    data_table_content: candidates_list_table.column(0).data().toArray(),
                };
                var url;
                var method;


                url = BASE + 'sendsms',
                    method = 'POST';


                e.preventDefault();
                $.ajax({
                    url: url,
                    type: method,
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'error') {
                            notification(response);
                            btn.button('reset');
                        } else {
                            notification(response);
                            view_url = 'sendsms';
                            setTimeout(
                                function () {
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


            $('#shordcodesdiv').hide();


            $('input[type=radio][name=send_time_type]').change(function () {

                if (this.value == 'send_now') {

                    $('#send_later_panel').hide('slow');
                } else
                    $('#send_later_panel').show('slow');
            });


            $('input[type=radio][name=sms_type]').change(function () {

                if (this.value == 'select_candidates') {
                    candidates_list_table.clear().draw(false);
                    $('#shordcodesdiv').show(800);
                    $('#customers_select_div').show(800);
                    $('#other_number_div').hide(800);
                    $('#customer_table_div').show(800);
                } else if (this.value == 'send_to_other_number_type') {
                    candidates_list_table.clear().draw(false);
                    $('#shordcodesdiv').hide(800);
                    $('#other_number_div').show(800);
                    $('#customers_select_div').hide(800);
                    $('#customer_table_div').hide(800);
                } else if (this.value == 'send_to_all') {
                    $('#shordcodesdiv').show(800);
                    $('#other_number_div').hide(100);
                    $('#customers_select_div').hide(800);
                    $('#customer_table_div').show(800);

                    candidates_list_table.clear().draw(false);
                    getCandidatesDetails('send_to_all');

                } else if (this.value == 'send_to_pending_candidates') {
                    $('#shordcodesdiv').show(800);
                    $('#other_number_div').hide(100);
                    $('#customers_select_div').hide(800);
                    $('#customer_table_div').show(800);

                    candidates_list_table.clear().draw(false);
                    getCandidatesDetails('send_to_pending_candidates');

                } else if (this.value == 'send_to_job_found_candidates') {
                    $('#shordcodesdiv').show(800);
                    $('#other_number_div').hide(100);
                    $('#customers_select_div').hide(800);
                    $('#customer_table_div').show(800);

                    candidates_list_table.clear().draw(false);
                    getCandidatesDetails('send_to_job_found_candidates');

                } else if (this.value == 'send_to_selected_list') {
                    $('#shordcodesdiv').show(800);
                    $('#other_number_div').hide(100);
                    $('#customers_select_div').hide(800);
                    $('#customer_table_div').show(800);

                    candidates_list_table.clear().draw(false);
                    getCandidatesDetails('send_to_selected_candidates');

                }
            });



            $('#candidates_select').change(function (e) {
                if ($('#candidates_select').val() === '') {
                    return false;
                }
                else {

                    getCandidatesDetails('selected_candidates_list');
                    $('#candidates_select').find('[value=' + $('#candidates_select').val() + ']').remove();
                    $('#candidates_select').selectpicker('val', '');
                    $('#candidates_select').selectpicker('refresh');

                }
            });

            function getCandidatesDetails(filtering_method) {
                var candidate_id = $('#candidates_select').val();
                var params = {
                    candidates_filtering_method: filtering_method,
                    selected_candidate_id: $('#candidates_select').val()

                };
                //e.preventDefault();
                $.ajax({
                    url: BASE + 'sms/get_customers_list',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'error') {
                        } else {
                            var column_data = response.data;
                            candidates_list_table.rows.add(column_data).draw(false);
                            if ($("input:radio[name='sms_type']:checked").val() === 'customers_list') {
                                $('#customers_select').find('[value=' + customer_id + ']').remove();
                                $('#customers_select').selectpicker('val', '');
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
                var data = candidates_list_table.row($(this).parents('tr')).data();
                var parent = $(this).parents('tr');
                candidates_list_table
                    .row(parent)
                    .remove()
                    .draw();

                if ($("input:radio[name='sms_type']:checked").val() === 'customers_list') {
                    $("#customers_select").append($('<option>', {value: data.id, text: data.fname + ' ' + data.lname}));
                    $('#customers_select').selectpicker('val', '');
                    $('.selectpicker').selectpicker('refresh');
                }

            });


            @if($send_type!=NULL && $send_type == 'selected_candidates')

                $("#send_to_selected_list_type").attr('checked', true).trigger('change');
            @endif
            @if($send_candidate!=NULL)

            $("#select_candidates_type").attr('checked', true).trigger('change');

            $('#candidates_select').selectpicker('val', "{{$send_candidate ?? ''}}");


            $('.candidates_select').selectpicker('refresh');
            $('#candidates_select').trigger('change');
            @endif

        });
    </script>
@endsection
