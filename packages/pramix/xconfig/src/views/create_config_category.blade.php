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
                            <h4>Update Config Category</h4>
                        @else
                            <h4>Create Config Category</h4>
                        @endif

                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats">
                        <button href="javascript:void(0)" class="btn btn-danger"
                           id="config_category_save_btn">{{ __('xcustomer::customer.buttons.save')}}</button>
                        <button href="javascript:void(0)" class="btn btn-primary"
                           id="config_category_save_and_new">{{ __('xcustomer::customer.buttons.save_and_new')}}</button>
                        <button href="javascript:void(0)" class="btn btn-warning"
                           id="config_category_update_btn">{{ __('xcustomer::customer.buttons.update')}}</button>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Top bar ends -->


        <!-- Main container starts -->
        <div class="main-container">


            <!-- Row starts -->
            <div class="row gutter">

                <form action="{{url('/').'/config_categories'}}" method="POST" id="create_config_category_form">
                    @csrf
                    <input type="hidden" id="config_category_id" name="config_category_id" value="{{$config->id ?? ''}}">

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card" id="customer-details-card">
                            <div class="card-header">
                                <h4>Config Details</h4>
                            </div>
                            <div class="card-body">


                                <div class="row gutter">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="mobile">Config Category Name</label>
                                            <input type="text" class="form-control validate[required]" id="config_categpry_name"
                                                   name="config_category_name"
                                                   value="{{ $config_category->name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label
                                                    for="telephone">Description</label>
                                            <textarea name="description" id="description" class="form-control">{{$config_category->description ?? ''}}</textarea>

                                        </div>
                                    </div>
                                </div>



                                <div class="row gutter">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label for="Status">Status</label>
                                            <br/>
                                            <label class="custom-control custom-radio radio-inline">
                                                <input type="radio" name="status_radio"  @if(!isset($config_category)) checked @endif @if(isset($config_category) && $config_category->status==1) checked @endif  value="1">
                                                <label class="custom-control-label" for="customRadio">Active</label>
                                            </label>
                                            <label class="custom-control custom-radio radio-inline">
                                                <input type="radio" name="status_radio"  @if(isset($config_category) && $config_category->status==0) checked @endif value="0">
                                                <label class="custom-control-label" for="customRadio">Disable</label>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- Main container ends -->
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




            if ($('#config_category_id').val() != '')
            {
                $('#config_category_save_btn').hide();
                $('#config_category_save_and_new').hide();
            }



            else {
                $('#config_category_update_btn').hide();
            }


            $("#config_category_save_btn ,#config_category_update_btn , #config_category_save_and_new").click(function (e) {
                var valid = $("#create_config_category_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                $('#config_category_save_btn ,#config_category_update_btn , #config_category_save_and_new').prop('disabled', true);

                var btn = $(this).attr("id");

                var config_category_details = $('#create_config_category_form').serialize();


                var params = {
                    config_category_details: config_category_details,
                };
                var method = '';
                var url = '';

                if ($('#config_category_id').val() != '') {
                    method = 'PUT';
                    url = BASE + 'config_categories/' + $('#config_category_id').val();
                } else {
                    url = BASE + 'config_categories';
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

                            $('#config_category_id').val(response.category_id);

                            if (btn == 'config_category_save_and_new') {
                                notification(response);
                                setTimeout(
                                    function () {
                                        window.location.href = BASE + 'config_category_id/create';
                                    }, 1000);
                            }
                            if (btn == 'config_category_save_btn') {

                                notification(response);

                                $('#config_category_save_btn ,#config_category_update_btn , #config_category_save_and_new').prop('disabled', false);

                            }
                            if (btn == 'config_category_update_btn') {
                                notification(response);
                                $('#config_category_save_btn ,#config_category_update_btn , #config_category_save_and_new').prop('disabled', false);

                            }
                        } else {
                            notification(response);
                            $('#config_category_save_btn ,#config_category_update_btn , #config_category_save_and_new').prop('disabled', false);


                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }
                });
                e.preventDefault();
                return false;
            });



        });
    </script>
@endsection
