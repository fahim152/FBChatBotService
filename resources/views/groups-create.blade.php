@extends('layouts.master')

@section('title', __('page.group') . " " . __('page.' . $lang_type))

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
                <h1> @lang('page.group') {{ __('page.' . $lang_type) }}
                    <small></small>
                </h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('dashboard') }}"> @lang('page.dashboard') </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="{{ route('groups') }}"> @lang('page.groups') </a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active"> {{ __('page.' . $lang_type) }} </span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->

        <div class="page-content-col">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-people"></i>
                        <span class="caption-subject bold uppercase"> {{ __('page.form', ['attribute' => __('page.group'), 'type' => __('page.'.$lang_type)]) }} </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="ajax-form" class="form-horizontal"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="action" value="{{ route('group_update') }}">
                            <input type="hidden" name="method" value="POST">
                            <input type="hidden" name="group" value="{{$group['id']}}">
                            <div class="form-body">
                                <!--Group Name-->
                                <div class="form-group has-error-group_name">
                                    <label class="col-md-3 control-label"> @lang('page.name', ['attribute' => __('page.group')]) <span class="required" aria-required="true"> * </span></label>
                                    <div class="col-md-4">
                                        <input type="text" name="group_name" value="{{ isset($group['name']) ? $group['name'] : old('name')}}" placeholder="Group Name" class="form-control" required="">
                                    </div>
                                </div>
                                <!--Group Description-->
                                <div class="form-group has-error-group_description">
                                    <label class="col-md-3 control-label"> @lang('page.description', ['attribute' => __('page.group')]) <span class="required" aria-required="true"> &nbsp;&nbsp; </span></label>
                                    <div class="col-md-4">
                                        <textarea name="group_description" placeholder="Description" rows="4" class="form-control">{{ isset($group['description']) ? $group['description'] : old('description')}}</textarea>
                                    </div>
                                </div>
                                    <div class="form-group">
                                        <div class="col-md-4 col-md-offset-3">
                                            <button type="submit" class="btn green">{{ ($group['id'] != "") ? __('page.update') : __('page.create') }} @lang('page.group') </button>
                                            <a href="{{ route('groups') }}" class="btn btn-default">@lang('page.cancel')</a>
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
