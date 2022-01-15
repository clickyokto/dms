@extends('layouts.app')


@section('include_css')

@endsection

@section('content')
    <!-- Page Header Start -->
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>Change Password</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Top bar ends -->


    <!-- Main container starts -->
    <div class="main-container">

        <!-- Row starts -->
        <div class="row gutter">
            <br/>

            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="row page-content">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="inner-box">

                                    <div class="dashboard-wrapper">
                                        <div class="col-sm-12">
                                            <form id="settings_form" method="post" action="" class="form-horizontal">


                                                <div class="form-group">
                                                    <label for="jobpositions" class="control-label col-xs-4">Current Password</label>
                                                    <div class="col-xs-8">
                                                    <input type="password" class="form-control validate[required]"
                                                           id="current_password" placeholder="Enter Current Password"
                                                           value="" name="current_password">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-xs-4" for="jobpositions">New Password</label>
                                                    <div class="col-xs-8">
                                                    <input type="password" class="form-control validate[required]"
                                                           id="password" placeholder="Enter New Password" value=""
                                                           name="password">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="control-label col-xs-4" for="jobpositions">Confirm New Password</label>
                                                    <div class="col-xs-8">
                                                    <input type="password" class="form-control validate[required]"
                                                           id="password_confirmation" placeholder="Confirm Password"
                                                           value="" name="password_confirmation">
                                                    </div>
                                                </div>



                                                <div class="col-sm-4">
                                                    <button type="button" class="btn btn-primary" id="btn_update"
                                                            name="btn_update">Update
                                                    </button>


                                                </div>

                                            </form>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>







@endsection


@section('custom_scripts')
    <script>
        $(document).ready(function () {
            $("#settings_form").validationEngine();


            $("#btn_update").click(function (e) {
                var valid = $("#settings_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }
                var btn = $(this).attr("id");
                var params = {
                    settings_details: $('#settings_form').serialize(),
                };

                var url;
                var method;

                url = BASE + 'change_password';
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
                        } else {
                            notification(response);
                            /*
                             $('#set_code').val(response.code);
                             $('#settings_id').val(response.id);*/
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

@section('include_js')


@endsection
