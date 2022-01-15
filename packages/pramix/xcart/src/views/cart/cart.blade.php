@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))

@section('include_css')

@endsection

@section('content')

    <!-- Page Header Start -->
    <div class="page-header">
<input type="hidden" id="quotation_id" name="quotation_id">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-wrapper">
                        <h2 class="product-title">Cart</h2>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
    <div class="main-container section-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div id="personal_info_section">
                        <div class="panel panel-default">


                            <div class="panel-body">

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $item)

                                        <tr id="{{$item->id}}">

                                             <td>{!!$item->name!!}</td>
                                            <td>{!!$item->quantity!!}</td>
                                            <td>{{$item->price,'LKR',TRUE}}</td>
                                            <td><button class="btn btn-xs btn-danger remove_item_from_cart" data-id="{{$item->id}}"><i class="fa fa-remove"></i></button></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Sub Total</th>
                                        <th>{{\App\Http\Helper::formatPrice(Cart::getSubTotal())}}</th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
                                        <th>{{\App\Http\Helper::formatPrice(Cart::getTotal())}}</th>
                                    </tr>


                                    </tbody>
                                </table>


                            </div>


                        </div>

                    </div>

                </div>
                <div class="col-sm-3">
                    <a href="{{url('/shop')}}" class="btn btn-primary btn-block" id="continue_shopping_btn">Continue Shopping</a><br>


                    <button class="btn btn-primary btn-block" id="save_quotation" @if(Cart::isEmpty()) disabled @endif>Save Quotation</button>


                </div>

            </div>
        </div>
    </div>

@endsection




@section('include_js')



@endsection
@section('custom_script')
    <script>
        $(document).ready(function () {

            $(".remove_item_from_cart").click(function (e) {

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
                            location.reload();
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


            $("#cart_continue_btn").click(function (e) {

                var valid = $("#cart_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                var button = $(this);

                button.button('loading');

                e.preventDefault();

                var mobile_iso = $("#cart_form #phone").intlTelInput("getSelectedCountryData");

                var params = {
                    mobile: $('#cart_form #phone').intlTelInput("getNumber"),
                    cart_details: $('#cart_form').serialize(),
                    mobile_country: mobile_iso['iso2'],

                };

                $.ajax({
                    url: BASE + 'cart',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'success') {
                            notification(response);

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'country',
                                name: 'country',
                                value: $('#customer_country option:selected').text()
                            }).appendTo('form');

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'address',
                                name: 'address',
                                value: $('#addressline1').val() + ' ' + $('#addressline2').val()
                            }).appendTo('form');

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'order_id',
                                name: 'order_id',
                                value: response.order_id
                            }).appendTo('form');

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'items',
                                name: 'items',
                                value: response.order_no
                            }).appendTo('form');

                            $('<input>').attr({
                                type: 'hidden',
                                id: 'amount',
                                name: 'amount',
                                value: response.amount
                            }).appendTo('form');

                            $(('#cart_form')).submit()

                        } else {
                            notification(response);
                            button.button('reset');
                        }
                    },
                    error: function (errors) {
                        notification(response);
                    }
                });
                e.preventDefault();
                return false;


            });

        });


    </script>
@endsection
