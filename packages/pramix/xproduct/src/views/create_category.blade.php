@extends('layouts.app')


@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        @if(isset($category->id))
                            <h4>Edit Category</h4>
                        @else
                            <h4>Add Category</h4>
                        @endif
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats" id="save_button_group">
                        <button class="btn btn-primary"
                                id="category-save-btn">{{ __('xproduct::product.buttons.save')}}</button>
                        <button class="btn btn-primary"
                                id="category-save-and-new">{{ __('xproduct::product.buttons.save_and_new')}}</button>
                        <button class="btn btn-primary"
                                id="category-update-btn">{{ __('xproduct::product.buttons.update')}}</button>

                    </ul>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="row gutter">


                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{url('/').'/category'}}" method="POST" id="category_form">
                                @csrf
                                <input type="hidden" name="category_id" id="category_id" value="{{$category->id ?? ''}}">
                                <div class="form-group no-margin">
                                    <label for="category_name">Code</label>
                                    <input value="{{$category->category_code ?? ''}}" type="text" class="form-control"
                                           id="category_code" name="category_code" required>
                                </div>
                                <div class="form-group no-margin">
                                    <label for="category_name">{{ __('xproduct::product.labels.category_name')}}</label>
                                    <input value="{{$category->category_name ?? ''}}" type="text" class="form-control"
                                           id="category_name" name="category_name" required>
                                </div>

                                {{formDropdown('Parent Category', 'parent_category',\Pramix\XProduct\Models\ProductCategoriesModel::where('parent_id', 0)->pluck('category_name', 'id'),isset($category->parent_id) ? $category->parent_id : 0, array('class' => 'form-control select2', 'id' => 'parent_category'))}}

                                <div class="form-group no-margin">
                                    <label for="category_description">{{ __('xproduct::product.labels.category_description')}}</label>
                                    <textarea name="category_description" id="category_description"
                                              class="form-control">{{$category->description ?? ''}}</textarea>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script>
        $(document).ready(function () {

            window.onbeforeunload = function() {
                return "Are you sure you want to leave?";
            };

            var select_parent = new Option('Parent Category', 0, true, true);
            $('#parent_category').append(select_parent);

            $('#parent_category').val({{$category->parent_id ?? 0}});

                    @if(!isset($category->parent_id))


            $('#category-update-btn').hide();

            @else
            $("#category-save-btn").hide();
            $("#category-save-and-new").hide();
            @endif


            $("#category-save-btn ,#category-update-btn, #category-save-and-new").click(function (e) {

                var btn = $(this).attr("id");



                var valid = $("#category_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }

                var params = {
                    category: $('#category_form').serialize()
                };
                var method = '';
                var url = '';
                if ($('#category_id').val() != '') {
                    method = 'PUT';
                    url = BASE + 'category/' + $('#category_id').val();
                } else {
                    url = BASE + 'category';
                    method = 'POST';
                }
                disable_save_button_group.run();
                e.preventDefault();
                $.ajax({
                    url: url,
                    type: method,
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        notification(response);

                        if (response.status == 'success') {

                            $('#category_id').val(response.category.id);
                            if (btn == 'category-save-and-new') {

                                setTimeout(
                                    function () {
                                        window.location.href = BASE + 'category/create';
                                    }, 1000);
                            }
                           else if (btn == 'category-update-btn' || btn == 'category-save-btn') {
                                setTimeout(
                                    function () {
                                        window.location.href = BASE + 'category';
                                    }, 1000);

                            }

                        } else {
                            enable_save_button_group.run();
                            notification(response);

                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        enable_save_button_group.run();
                        notificationError(xhr, ajaxOptions, thrownError);
                    }
                });
                e.preventDefault();
                return false;
            });

        });
    </script>
@endsection
