@extends('layouts.app')


@section('content')
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>User Permissions</h4>
                </div>
            </div>
        </div>
    </div>
    <!-- Top bar ends -->



<!-- Main content -->
<div class="main-container">
    <!-- Row starts -->
    <div class="row gutter">
        <div class="col-sm-12">
            <div class="card card-bd lobidrag">
                  <div class="card-header">
                    <div class="btn-group">
                        @can('ADD_PERMISSION')
                        {{ Html::link('permissions/create', __('xuser::permission.buttons.new_permission'),array('class="btn btn-success"'))}}
                        @endcan
                    </div>
                </div>
                 <div class="card-body">
                <div id="collapseOne" class="">
                    <ul class="list-group no-margin">

                        @foreach($permissions as $permission)
                        <li class="list-group-item">
                            <a href="{{url('/permissions/'.$permission->id.'/edit')}}"><strong>{{ $permission->display_name }} </strong></a><span class="permission_name">{{$permission->name}}</span>
                            @if(count($permission->childs)!=0)
                            @include('xuser::permission.managechild',['childs' => $permission->childs])
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                 </div>
            </div>


        </div>
    </div>
    <!-- Row ends -->

</div> <!-- /.content -->
@endsection

