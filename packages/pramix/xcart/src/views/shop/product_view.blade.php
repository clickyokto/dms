
    <div class="page-header">
        <input type="hidden" id="quotation_id" name="quotation_id">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="breadcrumb-wrapper">
                        <h2 class="product-title">Product - {{$product->item_code}}</h2>
                        <h4 class="product-title">Price - {{\App\Http\Helper::formatPrice($product->price)}}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
    <div class="main-container section-padding">
        <div class="container">
            <div class="row" >
                <?php  $media = \Pramix\XMedia\Models\MediaModel::getMainImageByRefID($product->id, getConfigArrayValueByKey('MEDIA_TYPES', 'products_media'));

                ?>
                    @if ($media != '')
                <div class="col-sm-4">


                        <img class="img-fluid lazy" src="{{$media}}">



                    <div class="row">

                        @if(isset($media_array))
                            @foreach($media_array as $media)
                                <div class="col-sm-3">


                                    <a data-fancybox="gallery"
                                       href="{{url('uploads/' . $media->folder_name . '/' . $media->file_name)}}">
                                        <img class="img-fluid lazy"
                                             src="{{url('uploads/' . $media->folder_name . '/medium/' . $media->file_name)}}">
                                    </a>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
                    @endif
                <div class="col-sm-8">
                    <h6 class="product-title">Category - {{$product->category->category_name}}</h6>
                    <h6 class="product-title">ITEM NAME/CODE - {{$product->item_code}}</h6>
                    <h6 class="product-title">TYPE - {{$product->type}}</h6>
                    <h6 class="product-title">MANUFACTURE - {{\Pramix\XProduct\Models\ManufactureModel::find($product->manufacture_id)->manufacture_name ?? ''}}</h6>
                    <h6 class="product-title">Quantity On Hand - {{$product->qty_on_hand}}</h6>

<hr>
                    <div class="row">
                        <div class="col-sm-2">
                            <form action="{{url('/').'/cart'}}" method="POST" id="qty_add_to_cart">
                            <input type="hidden" class="cart_product_id" name="product_id" id="product_id" value="{{ $product->id ?? '' }}">
                            {{ formText('Quantity', 'qty', '1', array( 'class' => 'cart_product_qty form-control validate[required]' , 'id' => 'qty'))}}
                            </form>
                        </div>

                    </div>
                </div>
                    @if($product->description != '')
                <div class="col-sm-12">
                    <h6 class="product-title">Description</h6>
                    {{$product->description}}
                </div>
                        @endif
            </div>
        </div>
    </div>
