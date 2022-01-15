@extends('layouts.app')

@section('include_css')

    <link href="{{ asset('plugins/jquery_tree/ui.fancytree.min.css')}}" rel="stylesheet" media="screen"/>
    <link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>



@endsection

@section('content')
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>New User Role</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Top bar ends -->



    <!-- Main content -->
    <div class="main-container">
        <!-- Main container starts -->
        <div class="row gutter">
            <!-- Form controls -->
            <div class="col-sm-12">
                <div class="card card-bd lobidrag">
                    <div class="card-header">
                        <div class="btn-group">
                            <a class="btn btn-primary"
                               href="{{url('roles')}}">  {{__('xuser::user_role.buttons.roles_list')}}</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{url('/user_role')}}" method="POST" id="create_role_form" class="col-sm-6">
                            {!! csrf_field() !!}
                            <input type="hidden" name="role_id" id="role_id" value="{{ $role->id ?? '' }}">

                            <!-- Row starts -->

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                <div class="card" id="role-details-card">
                                    <input type="hidden" name="permissions" id="permissions" value="">
                                    <div class="card-header">
                                        <h4>{{ __('xuser::user_role.headings.role_details')}}</h4>
                                    </div>
                                    <div class="card-body">


                                        <div class="row gutter">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="customer">{{ __('xuser::user_role.labels.role_name')}}</label>
                                                    <input type="text" class="form-control validate[required]"
                                                           id="role_name" name="role_name"
                                                           value="{{ $role->name ?? '' }}"></div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="supplier">{{ __('xuser::user_role.labels.role_display_name')}}</label>
                                                    <input type="text" class="form-control validate[required]"
                                                           id="display_name" name="display_name"
                                                           value="{{ $role->display_name ?? '' }}"></div>
                                            </div>

                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <label for="description">{{ __('xuser::user_role.labels.description')}}</label>
                                                    <textarea class="form-control"
                                                              name="description">{{ $role->description ?? '' }}</textarea>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="card" id="role-details-card">
                                            <div class="card-body">

                                                <p>
                                                    <a href="#" id="btnSelectAll3">Select all</a> -
                                                    <a href="#" id="btnDeselectAll3">Deselect all</a>
                                                </p>
                                                <div id="tree3"></div>

                                            </div>
                                        </div>

                                        @if(isset($role->id))
                                            <button type="button" class="btn btn-primary"
                                                    id="role-update-btn">{{__('common.buttons.btn_update')}}</button>

                                        @else
                                            <button type="button" class="btn btn-primary"
                                                    id="role-save-btn">{{__('common.buttons.btn_save')}}</button>
                                            <button type="button" class="btn btn-default"
                                                    id="role_save_and_new">{{__('common.buttons.btn_save_and_new')}}</button>
                                            <button type="reset" class="btn btn-default"
                                                    id="btn_reset">{{__('common.buttons.btn_reset')}}</button>
                                        @endif


                                    </div>
                                </div>
                            </div>
                            <!-- Row ends -->

                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- Main container ends -->



@endsection

@section('include_js')
    <!--<script src="{{ asset('jquery_tree/jquery-ui.min.js')}}"></script>-->
    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script src="{{ asset('plugins/jquery_tree/jquery.fancytree.min.js')}}"></script>
@endsection


@section('custom_script')
    <script type="text/javascript">
        $(function () {


            var permissionArr = new Array();
            $("#btnDeselectAll3").click(function () {
                $("#tree3").fancytree("getTree").visit(function (node) {
                    node.setSelected(false);
                });
                return false;
            });
            $("#btnSelectAll3").click(function () {
                $("#tree3").fancytree("getTree").visit(function (node) {
                    node.setSelected(true);
                });
                return false;
            });
            $("#tree3").fancytree({
                checkbox: true,
                selectMode: 2,
                expanded: true,
                source: [{!!$permission!!}],
                init: function (event, data) {
                    data.tree.getRootNode().visit(function (node) {
                        if (node.data.preselected) node.setSelected(true);
                    })
                },
//        lazyLoad: function (event, ctx) {
//            ctx.result = {url: "ajax-sub2.json", debugDelay: 1000};
//        },
//        loadChildren: function (event, ctx) {
//            ctx.node.fixSelection3AfterClick();
//        },
                select: function (event, data) {
                    // Get a list of all selected nodes, and convert to a key array:

                    var selKeys = $.map(data.tree.getSelectedNodes(), function (node) {

                        return node.key;
                    });
                    $('#permissions').val(selKeys);
//            $("#echoSelection3").text(selKeys.join(", "));
//
//            // Get a list of all selected TOP nodes
//            var selRootNodes = data.tree.getSelectedNodes(true);
//            // ... and convert to a key array:
//            var selRootKeys = $.map(selRootNodes, function (node) {
//                return node.key;
//            });
//
//            $("#echoSelectionRootKeys3").text(selRootKeys.join(", "));
//            // $("#echoSelectionRoots3").text(selRootNodes.join(", "));
                },
                // The following options are only required, if we have more than one tree on one page:
//        cookieId: "fancytree-Cb3",
//        idPrefix: "fancytree-Cb3-"
            });

        });</script>

    <script>
        $(document).ready(function () {

            var selKeys2 = $.map($("#tree3").fancytree('getTree').getSelectedNodes(), function (node) {

                return node.key;
            });

            $('#permissions').val(selKeys2);

            $("#create_role_form").validationEngine();
            $("#role-save-btn, #role-update-btn").click(function (e) {

                var valid = $("#create_role_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                var params = {
                    role_details: $('#role-details-card :input').serialize(),
                };
                var method = '';
                var url = '';
                if ($('#role_id').val() != '') {
                    method = 'PUT';
                    url = BASE + 'roles/' + $('#role_id').val();
                } else {
                    url = BASE + 'roles';
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
                        }
                        else {
                            notification(response);
                        }

                    },
                });
                e.preventDefault();
                return false;
            });
        });
    </script>
@endsection
