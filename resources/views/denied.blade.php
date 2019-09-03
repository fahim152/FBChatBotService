@extends('layouts.master')

@section('title', 'Permission Denied')

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

        <div class="row">
            <div class="col-md-12">
                <div class="well">
                    <h1>Currently <br>You don't have permission to visit that page</h1>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- END CONTENT BODY -->
</div>
@endsection

@section('script')
<script src="{{ asset('/js/form.js') }}" type="text/javascript"></script>
@endsection
