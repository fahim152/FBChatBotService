@extends('layouts.master')

@section('title', 'Create Setting')


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

            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->

        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('settings') }}">  @lang('page.settings')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">{{ ($setting['id'] != "") ? "Edit" : "Create" }}</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->

        <div class="page-content-col">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-user"></i>
                        <span class="caption-subject bold uppercase"> {{ ($setting['id'] != "") ? "Edit Category" : "Create Category" }} </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="ajax-form" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="action" value="{{ route('settings_update') }}">
                                <input type="hidden" name="method" value="POST">
                                <input type="hidden" name="setting" value="{{$setting['id']}}">
                                <div class="form-body">
                                    <!-- Name-->
                                    <div class="form-group has-error-name">
                                        <label class="col-md-3 control-label">Facebook App Name <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="name" value="{{ isset($setting['fb_app_name']) ? $setting['fb_app_name'] : old('fb_app_name')}}" placeholder="App Name" class="form-control" required="">
                                        </div>
                                    </div>

                                       <!-- Token-->
                                       <div class="form-group has-error-name">
                                        <label class="col-md-3 control-label"> Token <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="token" value="{{ isset($setting['token']) ? $setting['token'] : old('token')}}" placeholder="Token" class="form-control" required="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4 col-md-offset-3">
                                            <button type="submit" class="btn green">{{ ($setting['id'] != "") ? __('page.update') : __('page.create') }} @lang('page.setting') </button>
                                            <a href="{{ route('settings') }}" class="btn btn-default">  @lang('page.cancel')</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- END PAGE BASE CONTENT -->
                    </div>
                </div>
            </div>
            <!-- END PAGE BASE CONTENT -->
        </div>
        <!-- END CONTENT BODY -->
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('/js/form.js') }}" type="text/javascript"></script>
@endsection
