@extends('layouts.app')

@section('content')
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">

                        @if(isset($config->id))
                            <h4>Update Config</h4>
                        @else
                            <h4>Create Config</h4>
                        @endif

                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats">
                        <button href="javascript:void(0)" class="btn btn-danger"
                           id="config_save_btn">{{ __('xcustomer::customer.buttons.save')}}</button>
                        <button href="javascript:void(0)" class="btn btn-primary"
                           id="config_save_and_new">{{ __('xcustomer::customer.buttons.save_and_new')}}</button>
                        <button href="javascript:void(0)" class="btn btn-warning"
                           id="config_update_btn">{{ __('xcustomer::customer.buttons.update')}}</button>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Top bar ends -->


        <!-- Main container starts -->
        <div class="main-container">

            <form action="{{url('/').'/configurations'}}" method="POST" id="create_config_form">
                @csrf
                <input type="hidden" id="config_id" name="config_id" value="{{$config->id ?? ''}}">
            <!-- Row starts -->
            <div class="row gutter">



                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card" id="customer-details-card">
                            <div class="card-header">
                                <h4>Config Details</h4>
                            </div>
                            <div class="card-body">


                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="mobile">Config Name</label>
                                            <input type="text" class="form-control validate[required]" id="config_name"
                                                   name="config_name"
                                                   value="{{ $config->name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                    for="telephone">Display Name</label>
                                            <input type="text" class="form-control validate[required]" id="display_name"
                                                   name="display_name" value="{{ $config->display_name ?? '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label for="mobile">Config Type</label>
                                            {{ Form::select('config_types', Config::get('xconfig.config_types'), isset($config->config_type) ? $config->config_type : '' , array('class' => 'form-control', 'id' => 'config_type')) }}

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group">
                                            <label
                                                    for="telephone">Category</label>
                                            {{ Form::select('config_category', \Pramix\XConfig\Models\ConfigCategoryModel::where('status','1')->pluck('name','id'), isset($config->category_id) ? $config->category_id :'' , array('class' => 'form-control validate[required]', 'id' => 'config_category')) }}

                                        </div>
                                    </div>
                                </div>

                                <div class="row gutter">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="Status">Status</label>
                                        <br/>
                                        <label class="custom-control custom-radio radio-inline">
                                            <input type="radio" name="status_radio"  @if(!isset($config)) checked @endif @if(isset($config) && $config->status==1) checked @endif  value="1">
                                            <label class="custom-control-label" for="customRadio">Active</label>
                                        </label>
                                        <label class="custom-control custom-radio radio-inline">
                                            <input type="radio" name="status_radio"  @if(isset($config) && $config->status==0) checked @endif value="0">
                                            <label class="custom-control-label" for="customRadio">Disable</label>
                                        </label>
                                    </div>
                                </div>
                                </div>


                            </div>
                            <!-- Main container ends -->
                        </div>
                    </div>


                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card" id="text_type_panel">
                            <div class="card-header">
                                <h4>Text Value</h4>
                            </div>
                            <div class="card-body">


                                <div class="row gutter">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="mobile">value</label>
                                            <input type="text" class="form-control " id="text_value" name="text_value"
                                                   value="{{ $config->value ?? '' }}">
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- Main container ends -->
                        </div>

                        <div class="card" id="droptown_type_panel">
                            <div class="card-header">
                                <h4>Dropdown Options</h4>
                            </div>
                            <div class="card-body">

                                <div class="input_fields_wrap">

                                    <div class="row gutter">
                                        <div class="col-xs-5">
                                            <div class="form-group">
                                                <label for="mobile">Default Value</label>
                                                <input type="text" class="form-control" id="default_value"
                                                       name="default_value"
                                                       value="{{ $config->value ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($config) && $config->options_array!=NULL && $config->options_array!='')
                                        <?php $options = json_decode($config->options_array);
                                        $i =0; ?>

                                        @foreach($options as $key=>$value)
                                            <div class="row gutter">
                                                <div class="col-sm-5">
                                                    <div class="form-group">

                                                        <input type="text" class="form-control "
                                                               name="dropdown_value[]"
                                                               value="{{$key ?? ''}}" placeholder="Value">
                                                    </div>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group">

                                                        <input type="text" class="form-control validate[required]"
                                                               name="dropdown_text[]"
                                                               value="{{$value ?? ''}}" placeholder="Drop-down Text">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">

                                                    @if($i == 0)
                                                        <button class="add_field_button btn btn-success btn-sm">Add</button>
                                                    @else
                                                        <button class="remove_field btn btn-danger btn-sm">Remove</button>
                                                    @endif


                                                </div>

                                            </div>
                                            <?php $i++; ?>
                                        @endforeach
                                    @else

                                        <div class="row gutter">
                                            <div class="col-sm-5">
                                                <div class="form-group">

                                                    <input type="text" class="form-control "
                                                           name="dropdown_value[]"
                                                           value="" placeholder="Value">
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="form-group">

                                                    <input type="text" class="form-control validate[required]"
                                                           name="dropdown_text[]"
                                                           value="" placeholder="Drop-down Text">
                                                </div>
                                            </div>
                                            <div class="col-sm-2">

                                                <button class="add_field_button btn btn-success btn-sm">Add</button>
                                            </div>

                                        </div>
                                    @endif
                                </div>


                            </div>
                            <!-- Main container ends -->
                        </div>
                    </div>



            </div>
            </form>
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

            $('#droptown_type_panel').hide();


            if ($('#config_id').val() != '')
            {
                $('#config_save_btn').hide();
                $('#config_save_and_new').hide();
            }



            else {
                $('#config_update_btn').hide();
            }


            $("#config_save_btn ,#config_update_btn , #config_save_and_new").click(function (e) {
                var valid = $("#create_config_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                $('#config_save_btn ,#config_update_btn , #config_save_and_new').prop('disabled', true);
                var btn = $(this).attr("id");

                var config_details = $('#create_config_form').serialize();


                var params = {
                    config_details: config_details,
                };
                var method = '';
                var url = '';

                if ($('#config_id').val() != '') {
                    method = 'PUT';
                    url = BASE + 'configurations/' + $('#config_id').val();
                } else {
                    url = BASE + 'configurations';
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

                            if (btn == 'config_save_and_new') {
                                notification(response);
                                setTimeout(
                                    function () {
                                        window.location.href = BASE + 'configurations/create';
                                    }, 1000);
                            }
                            if (btn == 'config_save_btn') {

                                notification(response);
                                $('#customer_id').val(response.id);
                                $('#config_save_btn ,#config_update_btn , #config_save_and_new').prop('disabled', false);

                            }
                            if (btn == 'config_update_btn') {
                                notification(response);
                                $('#config_save_btn ,#config_update_btn , #config_save_and_new').prop('disabled', false);


                            }
                        } else {
                            notification(response);
                            $('#config_save_btn ,#config_update_btn , #config_save_and_new').prop('disabled', false);


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


            $('#config_type').on('change', function () {
                if (this.value == 'TX') {

                    $('#droptown_type_panel').hide(1000);
                    $('#text_type_panel').show(1000);
                } else if (this.value == 'DD') {
                    $('#text_type_panel').hide(1000);
                    $('#droptown_type_panel').show(1000);
                }
            });

            $('#config_type').change();

        });
    </script>
@endsection
