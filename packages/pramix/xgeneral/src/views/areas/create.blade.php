@extends('layouts.app')



@section('content')
    <!-- Top bar starts -->
    <div class="top-bar clearfix">
        <div class="row gutter">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="page-title">
                    <h4>Areas</h4>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <ul class="right-stats">

                    @if(isset($area->id))
                        <a href="javascript:void(0)" class="btn btn-warning"
                           id="area_update_btn">Update</a>


                    @else
                        <a href="javascript:void(0)" class="btn btn-success"
                           id="area_save_btn">Save</a>

                        <a href="javascript:void(0)" class="btn btn-success"
                           id="area_save_and_new_btn">Save And New</a>
                    @endif



                </ul>
            </div>
        </div>
    </div>
    <!-- Top bar ends -->

    <!-- Main container starts -->
    <div class="main-container">

        <!-- Row starts -->
        <div class="row gutter">
            <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">


                <div class="row gutter">
                    <div class="col-12">
                    <div class="" id="permission-details-card">
                        <div class="card custom-shadow">



                            <div class="card-body">
                                <form id="area_form" role="form" method="POST" action=""
                                      class="form-horizontal">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="area_id" id="area_id" value="{{ $area->id ?? '' }}">



                                        {{ formText('Area Code', 'code', $area->code ?? '', array( 'class' => 'form-control ' , 'id' => 'area_code'))}}


                                        {{ formText('Area Name', 'area_name', $area->name ?? '', array( 'class' => 'form-control validate[required]' , 'id' => 'area_name'))}}





                                </form>
                            </div>



                        </div>
                    </div>
                    </div>
                </div>
                <!-- Row inside row ends -->
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

                    $('#rep').append($('<option>', {
                        value: '',
                        text: 'Select Rep'
                    }));

                    $('#rep').val('{{$area->rep_id ?? ""}}').trigger('change');




                    $("#area_save_btn, #area_save_and_new_btn ,#area_update_btn").click(function (e) {
                        var btn = $(this).attr("id");
                        var valid = $("#area_form").validationEngine('validate');
                        if (valid != true) {
                            return false;
                        }
                        var params = {
                            area_details: $('#area_form').serialize(),
                        };
                        var method = '';
                        var url = '';
                        if ($('#area_id').val() != '') {
                            method = 'PUT';
                            url = BASE + 'areas/' + $('#area_id').val();
                        } else {
                            url = BASE + 'areas';
                            method = 'POST';
                        }

                        e.preventDefault();
                        $.ajax({
                            url: url,
                            type: method,
                            dataType: 'JSON',
                            data: $.param(params),
                            success: function (response) {
                                if (response.status == 'error') {
                                    notification(response);
                                } else {
                                    if (btn == 'area_save_and_new_btn') {
                                        setTimeout(
                                            function () {
                                                window.location.href = BASE + 'areas/create';
                                            }, 1000);
                                    }
                                    if (btn == 'area_update_btn' || btn == 'area_save_btn') {
                                        setTimeout(
                                            function () {
                                                window.location.href = BASE + 'areas';
                                            }, 1000);
                                    }



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
