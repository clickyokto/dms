@extends('layouts.app')



@section('content')
<!-- Dashboard wrapper starts -->
<div class="dashboard-wrapper">

    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>{{ __('xproduct::product.headings.product_categories')}}</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                    <ul class="right-stats" id="mini-nav-right">
                        <a href="{{url('category/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                            Add Category</a>
                    </ul>

            </div>

        </div>
    </div>
    <!-- Top bar ends -->



    <!-- Main container starts -->
    <div class="main-container">

        <!-- Row starts -->
        <div class="row gutter">


            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <div class="card">

                    <div class="card-body">
                        <div class="" id="category_list">
                            @foreach($categories as $category)
                                <p class="mt-5"><strong>{{$category->category_name}}</strong> <span class="text-right"> <a class="" href="{{url('category/'.$category->id.'/edit')}}" id="edit_grn" data-original-title="" title=""><i class="fa fa-pencil"></i></a></span></p>
                            @php $childs = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', $category->id)->get(); @endphp
@if(count($childs) > 0)
                                <ul class="list-group">
                                    @foreach($childs as $child)
                                    <li class="list-group-item">{{$child->category_name ?? ''}} <a class="" href="{{url('category/'.$child->id.'/edit')}}" id="edit_grn" data-original-title="" title=""><i class="fa fa-pencil"></i></a></li>

                                        @endforeach
                                </ul>


@endif
                            @endforeach
                        </div>



                    </div>
                </div>
            </div>
        </div>
        <!-- Row ends -->
    </div>
    <!-- Main container ends -->
</div>
<!-- Dashboard wrapper ends -->
@endsection


@section('custom_script')
<script>
$(document).ready(function () {





});
</script>
@endsection
