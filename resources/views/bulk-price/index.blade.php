@extends('layouts.app')
@section('content')
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>Product Bulk Price Edit</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                </div>
            </div>
        </div>
        <!-- Top bar ends -->
        <div class="main-container">
            <!-- Main content -->
            <div class="row gutter">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Product Price Edit</h4>
                        </div><!--end card-header-->
                        <div class="card-body">
                            <div class="table-responsive-sm">
                                <table class="table table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col">Stock ID</th>
                                        <th scope="col">Item Code</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Current Price</th>
                                        <th scope="col">New Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <form action="{{route('bulk-update')}}" method="post">
                                        @csrf
                                        @php
                                            $count = 0;
                                        @endphp
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{$product->stock_id}}</td>
                                                <td>{{$product->item_code}}</td>
                                                <td>{{$product->description}}</td>
                                                <td>{{number_format($product->price, 2)}}</td>
                                                <td>
                                                    <input type="text" name="product[{{$count}}][price]"
                                                           class="form-control" value="{{$product->price}}">
                                                    <input type="hidden" name="product[{{$count}}][id]"
                                                           class="form-control" value="{{$product->id}}">
                                                </td>

                                            </tr>
                                            @php
                                                $count = $count+1 ;
                                            @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan="4"></td>
                                            <td>
                                                <button class="btn btn-primary">UPDATE</button>
                                            </td>
                                        </tr>
                                    </form>
                                    </tbody>
                                </table><!--end /table-->
                            </div><!--end /tableresponsive-->
                        </div><!--end card-body-->
                    </div>
                </div>
            </div>
        </div>
@endsection
