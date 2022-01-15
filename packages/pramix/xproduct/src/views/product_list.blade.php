@extends('layouts.app')



@section('include_css')



@endsection

@section('content')
<!-- Dashboard wrapper starts -->
<div class="dashboard-wrapper">

    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>{{ __('xproduct::product.headings.product_list')}}</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                @can('ADD_PRODUCTS')
                <ul class="right-stats" id="mini-nav-right">
                     <a href="{{url('product/create')}}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i>
                        {{__('xproduct::product.buttons.new_product')}}</a>
                </ul>
                @endcan
            </div>
        </div>
    </div>
    <!-- Top bar ends -->

    <!-- Main container starts -->
    <div class="main-container">
        <!-- Row starts -->
        <div class="row gutter">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-inline data_list_filters">
                            @php
                                $categories = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', 0)->get();
                            @endphp
                            <div class="form-group" id="aod_reason_filter_div">

                                <select class="form-control select2" name="product_category" id="product_category">
                                    @foreach($categories as $category)
                                        <optgroup label="{{$category->category_name}}">

                                            @php $childs = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', $category->id)->get(); @endphp
                                            @if(count($childs) > 0)
                                                <ul class="list-group">
                                                    @foreach($childs as $child)
                                                        <option value="{{$child->id ?? ''}}">{{$child->category_name ?? ''}}</option>
                                                    @endforeach
                                                </ul>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="product_list_table" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Stock ID</th>
                                <th>{{ __('xproduct::product.labels.product_name_code')}}</th>
                                <th>{{ __('xproduct::product.labels.product_type')}}</th>
                                <th>{{ __('xproduct::product.labels.category')}}</th>
                                <th>{{ __('xproduct::product.labels.quantity_on_hand')}}</th>
                                <th>{{ __('xproduct::product.labels.normal_price')}}</th>
                                <th>{{ __('xproduct::product.labels.discount')}}</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>



                        </tbody>
                    </table>
                </div>


            </div>
        </div>
        <!-- Row ends -->

    </div>
    <!-- Main container ends -->

</div>
<!-- Dashboard wrapper ends -->
@endsection

@section('include_js')

@endsection

@section('custom_script')
<script>
$(document).ready(function () {
    var product_list_table  =$('#product_list_table').DataTable({
        processing: true,
        serverSide: true,
        order: [[0, "desc"]],
        'iDisplayLength': 10,
        ajax: '{!! route('get.all_products') !!}',
        columns: [
            {data: 'id', name: 'id', 'bVisible': false},
            {data: 'image', name: 'image'},
            {data: 'stock_id', name: 'stock_id'},
            {data: 'item_code', name: 'item_code'},
            {data: 'type', name: 'type'},
            {data: 'category_name', name: 'category_name'},
            {data: 'qty_on_hand', name: 'qty_on_hand'},
            {data: 'price', name: 'price' ,className: 'dt-body-right'},
            {data: 'discount', name: 'discount'},
            {data: 'action', name: 'action'},
        ]
    });

    $('#product_category').on('change', function () {
        var data = $('#product_category').select2('data')
        product_list_table.column(5)
            .search(data[0].text)
            .draw();
    });

    var selectedcategory = new Option('Select Product Category', '', true, true);
    $('#product_category').append(selectedcategory).trigger('change select2');

});
</script>
@endsection
