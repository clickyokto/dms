@extends((( Request::ajax()) ? 'layouts.model' : 'layouts.app' ))


@section('include_css')
@endsection

@section('content')
    <div class="dashboard-wrapper">
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">

                        <h4>{{ __('xfinance::finance.headings.new_income')}}</h4>


                    </div>
                </div>

            </div>
        </div>

        <div class="main-container">
            <div class="row gutter">
                <form action="{{url('/finance')}}" method="POST" id="data_import_form">
                    @csrf

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="radio">
                                <label><input type="radio" name="import_type" checked>Customers</label>
                            </div>
                            <div class="radio">
                                <label><input type="radio" name="import_type">Product Categories</label>
                            </div>
                            <div class="radio disabled">
                                <label><input type="radio" name="import_type">Products</label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group ">
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary active">
                                        <input type="radio" name="export_type" value="without_data" checked=""> Export Excel
                                        Format
                                    </label>
                                    <label class="btn btn-primary">
                                        <input type="radio" name="export_type" value="with_data"> Export Excel Format
                                        with Data
                                    </label>

                                </div>
                            </div>
                            <div class="form-group ">
                                <button id="download_excel" class="btn btn-primary">Download</button>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
@endsection

@section('include_js')


@endsection


@section('custom_script')
    <script>
        $(document).ready(function () {
            $("#data_import_form").validationEngine();







            $("#download_excel").click(function (e) {

                var valid = $("#data_import_form").validationEngine('validate');
                if (valid != true) {
                    return false;
                }
                var btn = $(this).attr("id");

                var params = {

                    export_details: $('#data_import_form').serialize(),

                };

                      var  url = BASE + 'data_import/download_excel';
                      var  method = 'POST';


                e.preventDefault();
                $.ajax({
                    url: url,
                    type: method,
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'success') {

                            notification(response);

                        } else {
                            notification(response);
                        }
                    },
                    error: function (error) {
                        notification(error);
                    }
                });
                e.preventDefault();
                return false;
            });


        });
    </script>

@endsection
