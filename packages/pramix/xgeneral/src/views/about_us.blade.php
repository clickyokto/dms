@extends('layouts.app')

@section('include_css')


@endsection

@section('content')
    <!-- Page Header Start -->
    <div class="page-header" style="background: url({{ asset('images/slide/slide3.jpg') }});">
        <div class="container">
            <h5 class="page-title">About us</h5>
        </div>

    </div>



    <!-- Ads Details Start -->
    <div class="section-padding">
        <div class="container">


            <h3>Company Overview</h3>


            <p>                Freelancer.com is the world's largest freelancing and crowdsourcing marketplace by number of users and projects. We connect over 33,038,712 employers and freelancers globally from over 247 countries, regions and territories. Through our marketplace, employers can hire freelancers to do work in areas such as software development, writing, data entry and design right through to engineering, the sciences, sales and marketing, accounting and legal services.

                Freelancer Limited is trading on the Australian Securities Exchange under the ticker ASX:FLN</p>


        </div>


    </div>

    <!-- Ads Details End -->


@endsection


@section('custom_scripts')
    <script>
        $(document).ready(function () {



        });

    </script>









@endsection

@section('include_js')



@endsection