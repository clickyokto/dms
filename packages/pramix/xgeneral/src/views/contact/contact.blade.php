@extends('layouts.app')

@section('include_css')


@endsection

@section('content')

    <!-- Page Header Start -->
    <div class="page-header" style="background: url({{ asset('images/slide/slide3.jpg') }});">
        <div class="container">
            <h5 class="page-title">Contact us</h5>
        </div>

    </div>
    <div class="col-md-12">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.9315286091587!2d79.91984271426772!3d6.89879282060178!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae250aad8ad373f%3A0x6c78a83bd5d4072a!2sSBS.COM+Office+Automation+(Pvt)+Ltd.!5e0!3m2!1sen!2s!4v1556420728412!5m2!1sen!2s" width="100%" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>

    </div>

    <div class="section-padding">
        <div class="container">


                <div class="row">
                    <div class="col-md-6">
                        <h4><strong>Top Quotations</strong></h4>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Address : </strong> #974/7, Pannipitiya Road, Battaramulla. Sri Lanka.<br></li>
                            <li class="list-group-item"><strong>Phone : </strong> (+94) 711 111 111<br></li>
                            <li class="list-group-item"><strong>Email : </strong> info@topquotation.lk</li>
                        </ul>




                    </div>
                    <div class="col-md-6">

                        <h6><strong>We're ready and waiting for your questions</strong></h6>

                        @if(isset($status))

                            @if($status == 'success')
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{$message}}
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> {{$message}}
                                </div>
                            @endif
                        @endif
                        <form action="{{url('/contact')}}" method="POST" class="form-horizontal mt30" id="job_form">
                            {{ csrf_field() }}


                            <div class="form-group">
                                <label class="control-label col-sm-2" for="email">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="pwd">Email Address</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="pwd">Subject</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="subject" name="subject">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2" for="pwd">Message</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" rows="3" id="message" name="message"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-blue">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>



        </div>
    </div>




@endsection

@section('include_js')
@endsection

@section('custom_scripts')

    <script>
        $(document).ready(function () {


            }
        );
    </script>
@endsection
