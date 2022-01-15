@extends('layouts.app')



@section('content')
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>System Users</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <ul class="right-stats">

                    @if(isset($user->id))
                        <a href="javascript:void(0)" class="btn btn-warning"
                           id="user-update-btn">{{__('xuser::user.buttons.update')}}</a>


                    @else
                        <a href="javascript:void(0)" class="btn btn-success"
                           id="user-save-btn">{{__('xuser::user.buttons.save')}}</a>


                    @endif



                </ul>
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
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="btn-group">
                            <a class="btn btn-primary" href="{{url('/users')}}"> <i
                                        class="icon-arrow-bold-left"></i> {{__('xuser::user.buttons.users_list')}}</a>
                        </div>
                    </div>
                    <div class="col-md-8 col-sm-6 col-xs-12">

                    </div>
                </div>
                <br/>
                <!-- Row ends -->
                <!-- Row inside row starts -->
                <div class="row gutter">
                    <div class="col-sm-12">
                    <div class="" id="permission-details-card">
                        <div class="card custom-shadow">
                            <br/>
                            @include('xuser::user.common.create_user')

                        </div>
                    </div>
                    </div>
                </div>
                <!-- Row inside row ends -->
            </div>

        </div>
        <!-- Main container ends -->

        @endsection



        @section('custom_script')

            <script>
                $(document).ready(function () {


                    $('#role').append($('<option>', {
                        value: '',
                        text: 'Select User Role'
                    }));

                    $('#branch').append($('<option>', {
                        value: '',
                        text: 'Select Branch'
                    }));


                    if ($('#user_id').val() == '') {
                        $('#role').val('');
                        $('#branch').val('');
                        $('#managers').val('');
                    }

                    $("#user-save-btn ,#user-update-btn").click(function (e) {

                        var valid = $("#user_form").validationEngine('validate');
                        if (valid != true) {
                            return false;
                        }
                        $('#user-save-btn').prop('disabled', true);
                        var btn = $(this).attr("id");

                        var params = {
                            user_details: $('#user_form').serialize(),
                        };

                        var method = '';
                        var url = '';
                        if ($('#user_id').val() != '') {
                            method = 'PUT';
                            url = BASE + 'users/' + $('#user_id').val();
                        } else {
                            url = BASE + 'users';
                            method = 'POST';
                        }

                        e.preventDefault();
                        $.ajax({
                            url: url,
                            type: method,
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'error') {
                                    notification(response);

                                    $('#user-save-btn').prop('disabled', false);
                                } else {
                                    notification(response);
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
