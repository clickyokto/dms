@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')

@endsection

@section('content')

    <!-- Page Header Start -->
    <div class="page-header">
        <input type="hidden" id="quotation_id" name="quotation_id">


        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb-wrapper">
                    <h2 class="product-title">Shop</h2>

                </div>
            </div>
        </div>

    </div>
    <!-- Page Header End -->
    <div class="main-container section-padding">
        <div class="row">
            <div class="col-sm-8">



                <!-- Page Header End -->
                <div class="main-container section-padding">
                    <div class="container">

                        <div class="card card-primary" id="product-details-panel">
                            <div class="card-body">
                                <div class="row gutter">

                                    <div class="col-sm-5 col-xs-12">
                                        <div class="form-group no-margin">


                                            @php
                                                $categories = \Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', 0)->get();
                                            @endphp
                                            <div class="form-group">

                                                <select  class="form-control select2" name="product_catagory"
                                                        id="shop_search_item_category" >
                                                    <option selected="selected" value="" >Product Category</option>
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

                                    <div class="col-sm-7 col-xs-12">
                                        <div class="form-group no-margin" id="shop_search_item_product_code">

<input id="shop_search_product" type="text" class="form-control" placeholder="Stock ID / Product Code">
                                        </div>
                                    </div>



                                </div>


                            </div>
                        </div>


                    </div>
                </div>
<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <table id="product_list_table" class="table table-striped table-bordered no-margin" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>{{ __('xproduct::product.labels.category')}}</th>

                    <th>{{ __('xproduct::product.labels.product_name_code')}}</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>



                </tbody>
            </table>
        </div>
    </div>
</div>

                {{--<?php--}}
                {{--$numOfCols = 3;--}}
                {{--$rowCount = 0;--}}
                {{--$bootstrapColWidth = 12 / $numOfCols;--}}
                {{--?>--}}

                {{--<div class="row">--}}
                    {{--@foreach ($products as $value)--}}

                        {{--<div class="col-md-{{$bootstrapColWidth}} col-sm-4 col-xs-6 text-center">--}}
                            {{--<div class="card">--}}
                                {{--<div class="card-body"><a href="javascript:void(0)" class="shop_product_view"--}}
                                                          {{--data-id='{{$value->id}}'>--}}
                                        {{--<img src="{{\Pramix\XMedia\Models\MediaModel::getMainImageByRefID($value->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'), TRUE)}}"--}}
                                             {{--class="img-fluid folder_icon">--}}
                                        {{--<h5><span><strong>{{ $value->stock_id }}</strong></span></h5>--}}
                                        {{--<h5><span>{{ $value->item_code }}</span></h5>--}}
                                    {{--</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}


                        {{--</div>--}}
                        {{--<?php--}}
                        {{--$rowCount++;--}}
                        {{--if ($rowCount % $numOfCols == 0) echo '</div><div class="row">';--}}
                        {{--?>--}}
                    {{--@endforeach--}}
                {{--</div>--}}
                {{--{{ $products->appends(request()->input())->links() }}--}}


                {{--{!! $products->render() !!}--}}

            </div>
            <div class="col-sm-4">

                <div class="main-container section-padding">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">


                                <div class="card-body">

                                    <table id="cart_table" width="100%" class="table">
                                        <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>


                                    </table>
                                    <table class="table text-right">

                                        <tr>

                                            <th>Total</th>
                                            <th><strong><span
                                                            id="cart_total">{{\App\Http\Helper::formatPrice(Cart::getTotal())}}</span></strong>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                            </div>


                        </div>
                        <div class="col-sm-6">

                            <button class="btn btn-primary btn-block" id="create_invoice">Create Invoice</button>


                        </div>
                        <div class="col-sm-6">


                            <button class="btn btn-danger btn-block" id="clear_cart">Clear Cart</button>

                        </div>
                    </div>
                </div>

            </div>
            <!-- Page Header End -->
        </div>
    </div>


    </div>

@endsection




@section('include_js')



@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {


            var product_list_table  =$('#product_list_table').DataTable({
                processing: true,
                serverSide: false,
                order: [[0, "desc"]],
                'iDisplayLength': 30,


                ajax: BASE + 'get_cart_products',
                columns: [
                    {data: 'id', name: 'id', 'bVisible': false},
                    {data: 'image', name: 'image'},
                    {data: 'category_name', name: 'category_name', 'bVisible': false},
                    {data: 'item_code', name: 'item_code'},
                    {data: 'qty_on_hand', name: 'qty_on_hand'},
                    {data: 'price', name: 'price' ,className: 'dt-body-right'},
                    {data: 'action', name: 'action'},
                ]
            });


            $('#shop_search_item_category').on('change', function () {
                if ($('#shop_search_item_category').val() != '') {
                    product_list_table.column(2)
                        .search($('#shop_search_item_category option:selected').text())
                        .draw();
                } else {
                    product_list_table.column(2)
                        .search('')
                        .draw();
                }
            });


            $('#shop_search_product').on('keyup', function () {

                product_list_table.column(3)
                    .search(this.value)
                    .draw();
            });

            $("#create_invoice").click(function (e) {
                e.preventDefault();
                var params = {};

                $.ajax({
                    url: BASE + 'create_invoice_from_cart',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'error') {
                            notification(response);
                        } else {


                            notification(response);
                            window.location.href = BASE + 'invoice/' + response.invoice_details.id + '/edit';


                        }
                    },
                    error: function (errors) {

                    }
                });
                e.preventDefault();
                return false;
            });


            $('#product_list_table tbody').on('click', '.shop_product_view', function (e) {


                var data = product_list_table.row($(this).parents('tr')).data();



                window.product_model = $.confirm({
                    title: '',
                    draggable: true,
                    boxWidth: '80%',
                    useBootstrap: false,
                    closeIcon: false,
                    buttons: {
                        add_to_cart: {
                            text: 'Add to cart', // text for button
                            btnClass: 'btn-blue', // class for the button
                            action: function (heyThereButton) {

                                var params = {
                                    product_id: this.$content.find('.cart_product_id').val(),
                                    qty: this.$content.find('.cart_product_qty').val(),
                                };
                                $.ajax({
                                    url: BASE + 'add_to_cart',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        if (response.status == 'success') {
                                            $('#cart_total').text(response.cart_total);

                                            notification(response);

                                            product_model.close();
                                            cart_table.ajax.reload();


                                        } else {
                                            notification(response);

                                        }
                                        enable_save_button_group.run();
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        enable_save_button_group.run();
                                        notificationError(xhr, ajaxOptions, thrownError);
                                    }
                                });

                                return false;
                            }
                        },
                        close: function () {

                        }
                    },
                    content: 'url:' + BASE + 'shop/' + data['id'],
                    onContentReady: function () {
                    },
                    columnClass: 'medium',
                });
            });

            $("#clear_cart").click(function (e) {
                var clear_cart_confirm = $.confirm({
                    title: "Clear Cart",
                    type: 'red',
                    buttons: {
                        delete: {
                            text: 'Clear',
                            btnClass: 'btn-red',
                            action: function () {

                                e.preventDefault();
                                var params = {};

                                $.ajax({
                                    url: BASE + 'clear_cart',
                                    type: 'POST',
                                    dataType: 'JSON',
                                    data: $.param(params),
                                    success: function (response) {
                                        if (response.status == 'error') {
                                            notification(response);
                                        } else {

                                            clear_cart_confirm.close();

                                            notification(response);

                                            cart_table.ajax.reload();
                                            $('#cart_total').text(response.cart_total);


                                        }
                                    },
                                    error: function (errors) {

                                    }
                                });
                                e.preventDefault();
                                return false;
                            }
                        },
                        close: function () {
                        }
                    }
                });
            });


            window.cart_table = $('#cart_table').DataTable({
                processing: true,
                serverSide: true,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": false,
                "searching": false,
                order: [[0, "desc"]],
                'iDisplayLength': 10,
                ajax: '{!! route('get.get_added_product_list') !!}',
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'price', name: 'price'},
                    {data: 'action', name: 'action'},
                ],
                "columnDefs": [
                    {"width": "10%", "targets": 1, className: 'dt-body-right'},
                    {"width": "20%", "targets": 2, className: 'dt-body-right'},
                    {"width": "10%", "targets": 3},
                ]
            });


            $('#cart_table tbody').on('click', '.remove_item_from_cart', function (e) {
                e.preventDefault();

                var item_id = $(this).data('id');

                var params = {
                    item_id: item_id
                };

                $.ajax({
                    url: BASE + 'cart/item_remove_from_cart',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'success') {
                            notification(response);
                            cart_table.ajax.reload();
                        } else {
                            notification(response);
                        }
                    },
                    error: function (errors) {
                        notification(errors, 'error');
                    }
                });
                e.preventDefault();
                return false;
            });



            function getData(page, search) {


                if (typeof page === "undefined") {
                    var url = window.location.href;
                    page = '';
                } else {
                    var url = '?page=' + page;
                }

                if ($('#search_project').val() != '') {
                    url = '?search=' + $('#search_project').val();
                    page = '';
                }
                if ($('#agency_search').val() != '' && typeof $('#agency_search').val() !== "undefined") {
                    url = '?agency=' + $('#agency_search').val();
                    page = '';
                }

                $.ajax(
                    {
                        url: url,
                        type: "get",
                        datatype: "html"
                    }).done(function (data) {
                    $("#dashboard_projects_container").empty().html(data);

                    //  location.hash = url;


                }).fail(function (jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
            }


            $('#shop_search_item_product_code').keyup(function (e) {
                getData()
            });
            $('#shop_search_item_category').change(function (e) {
                getData()
            });


        });


    </script>
@endsection
