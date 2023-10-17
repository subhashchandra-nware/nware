@extends('layouts.app')
@section('pageTitle', 'Register :: EzBook')

@section('content')
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-3 wizard d-flex flex-column flex-lg-row flex-column-fluid">
      <!--begin::Aside-->
      <div class="login-aside d-flex flex-column flex-row-auto"  style="background: #181C32;">
        <!--begin::Aside Top-->
        <div class="d-flex flex-column-auto flex-column pt-lg-10 pt-15">
          <!--begin::Aside header-->
          <a href="#" class="login-logo text-centerleft ml-5 mb-200">
          <img src="{{ asset('media/logos/logo-1.png') }}" class="max-h-50px" alt="" />
          </a>
          <!--end::Aside header-->
          <!--begin::Aside Title-->
          <h2 class="font-weight-bolder mb-0 text-left font-size-h2 text-white line-height-xl ml-6">Why do we have multiple sites ?</h2>
          <p class="font-weight-bold text-left font-size-h3 text-white line-height-xl ml-6">some of the reasons why you might see multiple sites </p>
          <ul class="text-white font-16">
            <li>You work for multiple companies</li>
            <li>Different people of your company signed up for different Kibog<br/>Products or services </li>
          </ul>
          <!--end::Aside Title-->
        </div>
        <!--end::Aside Top-->
      </div>
      <!--begin::Aside-->
      <!--begin::Content-->
      <div class="login-content flex-row-fluid d-flex flex-column p-10 bg-white">
        <!--begin::Wrapper-->
        <div class="d-flex flex-row-fluid flex-center">
          <!--begin::Signin-->
          <div class="selec-site">
            <!--begin::Form-->
            <!--begin::Title-->
            <div class="pb-5 pb-lg-15">
              <h3 class="font-weight-bolder text-dark font-size-h2 font-size-h1-lg">Please select site you want to Open</h3>
              <div class="text-muted font-weight-bold font-size-h4">Sites Linked to your email address</div>
            </div>
            @foreach ($siteNames as $site)
            <a href="{{url('/open-site')}}/{{$site}}" class="site" style="text-transform:uppercase;"><img src="{{ asset('media/site1.png') }}"> {{ $site}}</a>
            @endforeach
            <!--end::Form-->
          </div>
          <!--end::Signin-->
        </div>
        <!--end::Wrapper-->
        <!--begin::Content footer-->
        <div class="d-flex  justify-content-center align-items-end py-7 py-lg-0 footer">
          <a href="#" target="_blank" class="nav-link pr-3 pl-0">Legal</a>
          <a href="#" target="_blank" class="nav-link px-3">Privacy</a>
          <a href="#" target="_blank" class="nav-link pl-3 pr-0">Terms</a>
        </div>
        <!--end::Content footer-->
      </div>
      <!--end::Content-->
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

