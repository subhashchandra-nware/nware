@extends('layouts.app')
@section('pageTitle', 'Verify Your Email :: EzBook')

@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root bg-white">
    <!--begin::Login-->
    <div class="login login-3  d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="container">
            <div class="row justify-content-center mt-lg-155 verify text-center">
                <h2 class="verify-head">Verify Your Email</h2>
                <h3 class="flex verify-text">We have sent an email to <strong>{{ $user['EmailAddress'] }}</strong><br/></h3>
<a href="{{ route('login.setpassword', ['token'=>$token])}}" id="kt_login_singin_form_submit_button" class="btn btn-primary font-weight-bolder font-size-h6 px-8 py-4 my-3 mr-3">Skip For Now</a>
<p>Did’t receive an email?<a href="#">Resend</a></p>

<div class=" d-flex flex-row-fluid bgi-no-repeat bgi-position-x-center " style="background-position-y: calc(100% + 0rem); background-image: url(assets/media/verify-bg.png); background-size: contain; width: 100%; z-index: 99; min-height: 500px; max-height: 700px; height: 100%;"></div>
            </div>

        </div>
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
@endsection

@pushOnce('styles')
<link href="{{ asset('css/login.css') }}" rel="stylesheet" type="text/css" />
@endpushOnce

@pushOnce('scripts')
<script src="{{ asset('js/pages/custom/login/login-3.js') }}"></script>
@endpushOnce





