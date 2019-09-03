@extends('layouts.master')

@section('title', __('page.dashboard'))

@section('style')
@endsection

@section('content')
<div class="page-content-wrapper">
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>@lang('page.dashboard')
                    <small></small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <span class="active">@lang('page.dashboard')</span>
            </li>
        </ul>

        @if(isset($message) and !empty($message))
        <div class="custom-alerts alert {{ $messageType }} fade in">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <i class="fa-lg fa fa-success"></i> {{ $message }}
        </div>
        @endif

        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
        <!-- END FOREIGN PERMISSION CONTENT -->
        <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- END CONTENT BODY -->
</div>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/gstatic.com-charts-loader.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/charts.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/form.js')}}"></script>
@endsection
