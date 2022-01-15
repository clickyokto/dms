@extends('layouts.app')

@section('include_css')
    <!-- Fine Upload -->
    <link href="{{asset('/plugins/fineuploader/fine-uploader-gallery.min.css')}}" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.2/dragula.min.css" rel="stylesheet"/>
@endsection

@section('content')
    <!-- Dashboard wrapper starts -->
    <div class="dashboard-wrapper">

        <!-- Top bar starts -->
        <div class="top-bar clearfix">
            <div class="row gutter">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="page-title">
                        <h4>{{ __('xgeneral::settings.headings.setting')}}</h4>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="right-stats">
                        <a href="javascript:void(0)" class="btn btn-danger" id="company_save_btn">{{ __('common.buttons.btn_save')}}</a>
                    </ul>

                </div>
            </div>
        </div>
        <!-- Top bar ends -->

        <!-- Main container starts -->
        <div class="main-container">
            <form id="company_settings_form" action="{{url('/').'/settings'}}" method="POST">
            {!! csrf_field() !!}
                <input type="hidden" name="ref_id" id="ref_id" value="">

                <!-- Row starts -->
                <div class="row gutter">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                        <div class="card" id="payment-details-card">
                            <div class="card-header">
                                <h4>{{ __('xgeneral::settings.headings.logo')}}</h4>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="media_type" id="media_type" value="{{getConfigArrayValueByKey('MEDIA_TYPES','company_logo')}}">

                                @include('xmedia::add_multiple_images_template')


                            </div>
                        </div>


                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                        <div class="card" id="company-details-card">
                            <div class="card-header">
                                <h4>{{ __('xgeneral::settings.headings.company_details')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ formText(__('xgeneral::settings.labels.company_name'), 'company_name', getConfigArrayValueByKey('COMPANY_DETAILS','company_name') ?? '' , array( 'class' => 'form-control' , 'id' => 'company_name'))}}


                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.mobile'), 'mobile', getConfigArrayValueByKey('COMPANY_DETAILS','mobile') ?? '' , array( 'class' => 'form-control' , 'id' => 'mobile'))}}

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.telephone'), 'telephone', getConfigArrayValueByKey('COMPANY_DETAILS','telephone') ?? '' , array( 'class' => 'form-control' , 'id' => 'telephone'))}}

                                    </div>


                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.email'), 'email', getConfigArrayValueByKey('COMPANY_DETAILS','email') ?? '' , array( 'class' => 'form-control' , 'id' => 'email'))}}

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.website'), 'website', getConfigArrayValueByKey('COMPANY_DETAILS','website') ?? '' , array( 'class' => 'form-control' , 'id' => 'website'))}}

                                    </div>

                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.street1'), 'street1', getConfigArrayValueByKey('COMPANY_DETAILS','street1') ?? '' , array( 'class' => 'form-control' , 'id' => 'street1'))}}

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.street2'), 'street2', getConfigArrayValueByKey('COMPANY_DETAILS','street2') ?? '' , array( 'class' => 'form-control' , 'id' => 'street2'))}}

                                    </div>

                                </div>
                                <div class="row gutter">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.city'), 'city', getConfigArrayValueByKey('COMPANY_DETAILS','city') ?? '' , array( 'class' => 'form-control' , 'id' => 'city'))}}

                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        {{ formText(__('xgeneral::settings.labels.country'), 'country', getConfigArrayValueByKey('COMPANY_DETAILS','country') ?? '' , array( 'class' => 'form-control' , 'id' => 'country'))}}


                                    </div>

                                </div>


                            </div>
                        </div>
                    </div>


                </div>
                <!-- Row ends -->

            </form>



        </div>
        <!-- Main container ends -->

    </div>
    <!-- Dashboard wrapper ends -->
@endsection

@section('include_js')
    <script src="{{asset('/plugins/fineuploader/fine-uploader.min.js')}}"></script>
    <script src="{{ asset('/pramix/js/media_js.js') }}"></script>


@endsection


@section('custom_script')

    @include('xmedia::qq_template')


    <script>
        $(document).ready(function () {
            $("#company_save_btn").click(function (e) {

                var company_details = $('#company_settings_form').serialize();


                var params = {
                    company_details: company_details,
                };
                e.preventDefault();
                $.ajax({
                    url: BASE + 'settings/store',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $.param(params),
                    success: function (response) {
                        if (response.status == 'error')
                        {
                           notification(response);
                        }else
                        {
                            notification(response);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {

                        notificationError(xhr, ajaxOptions, thrownError);
                    }
                });
                e.preventDefault();
                return false;
            });
        });
    </script>
@endsection