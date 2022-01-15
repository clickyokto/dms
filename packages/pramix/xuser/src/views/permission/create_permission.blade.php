@extends('layouts.app')

@section('content')

    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>Create New Permission</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Top bar ends -->

    <!-- Main container starts -->
    <div class="main-container">
        <div class="row gutter">
            <div class="col-sm-12">
                <div class="card card-bd lobidrag" id="permission-details-card">
                    <div class="card-header">
                        <div class="btn-group">
                            <a class="btn btn-primary" href="{{url('/permissions')}}"> <i
                                    class="fa fa-list"></i> {{__('xuser::permission.headings.permissions_list')}}
                            </a>
                        </div>
                    </div>
                    <div class="card-body ">

                        <form action="{{url('/permission')}}" method="POST" id="permissions_form" class="col-sm-6">
                            {!! csrf_field() !!}
                            <input type="hidden" name="permission_id" id="permission_id"
                                   value="{{ $permission->id ?? '' }}">


                            <div class="form-group">
                                <label for="customer">{{ __('xuser::permission.labels.permission_name')}}</label>
                                <input type="text" class="form-control validate[required]" id="permission_name"
                                       name="permission_name" value="{{ $permission->name ?? '' }}">
                            </div>


                            <div class="form-group">
                                <label
                                    for="supplier">{{ __('xuser::permission.labels.permission_display_name')}}</label>
                                <input type="text" class="form-control validate[required]" id="display_name"
                                       name="display_name" value="{{ $permission->display_name ?? '' }}"></div>

                            <div class="form-group">
                                <label for="description">{{ __('xuser::permission.labels.description')}}</label>
                                <textarea class="form-control"
                                          name="description">{{ $permission->description ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="description">{{ __('xuser::permission.labels.parent_permission')}}</label>
                                {{ Form::select('parent_permission', Pramix\XUser\Models\Permission::pluck('display_name', 'id'),isset($permission->parent_id) ? $permission->parent_id : 0,array('class'=> 'form-control select2', 'id' => 'parent_permission')) }}

                            </div>

                            <div>
                                @if(isset($permission->id))
                                    <button type="button" class="btn btn-primary"
                                            id="permission-update-btn">{{__('common.buttons.btn_update')}}</button>
                                @else
                                    <button type="button" class="btn btn-primary"
                                            id="permission-save-btn">{{__('common.buttons.btn_save')}}</button>
                                    <button type="button" class="btn btn-default"
                                            id="permission-save-and-new">{{__('common.buttons.btn_save_and_new')}}</button>
                                    <button type="reset" class="btn btn-default"
                                            id="btn_reset">{{__('common.buttons.btn_reset')}}</button>
                                @endif
                            </div>


                        </form>
                    </div>
                </div>


            </div>
        </div>

    </div>
    <!-- Main container ends -->


@endsection




@section('custom_script')

    <script>
        $(document).ready(function () {

                $("#parent_permission").append(new Option("{{ __('xuser::permission.labels.top_permission')}}", 0));
                $("#permissions_form").validationEngine();
                @if (!isset($permission) || $permission -> parent_id == 0)
                $('#parent_permission option[value=0]').attr('selected', 'selected');
                @endif


                $('#permission_name').keyup(function (e) {
                    var name = $('#permission_name').val().replace(/_/g, ' ').toLowerCase();


                    $('#display_name').val(name.charAt(0).toUpperCase() + name.substr(1).toLowerCase());
                    $(this).val(function(_, val) {
                        return val.toUpperCase();
                    });

                });


                $("#permission-save-btn ,#permission-update-btn, #permission-save-and-new").click(function (e) {

                    var valid = $("#permissions_form").validationEngine('validate');
                    if (valid != true) {
                        return false;
                    }

                    var btn = $(this).attr("id");
                    var params = {
                        permission_details: $('#permission-details-card :input').serialize(),
                    };
                    e.preventDefault();
                    var method = '';
                    var url = '';
                    if ($('#permission_id').val() != '') {
                        method = 'PUT';
                        url = BASE + 'permissions/' + $('#permission_id').val();
                    } else {
                        url = BASE + 'permissions';
                        method = 'POST';
                    }

                    $.ajax({
                        url: url,
                        type: method,
                        dataType: 'JSON',
                        data: $.param(params),
                        success: function (response) {
                            if (response.status == 'success') {
                                notification(response);
                                if (btn == 'permission-save-and-new') {
                                    setTimeout(
                                        function () {
                                            window.location.href = BASE + 'permissions/create';
                                        }, 1000);
                                }
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
            }
        );
    </script>
@endsection
