@extends('layouts.master')

@section('title', 'Create Chat  ')


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
                <a href="{{ route('chats') }}">  @lang('page.chat')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">{{ ($chat['id'] != "") ? "Edit" : "Create" }}</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->

        <div class="page-content-col">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-user"></i>
                        <span class="caption-subject bold uppercase"> {{ ($chat['id'] != "") ? "Edit Chat" : "Create Chat" }} </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="ajax-form" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="action" value="{{ route('chats_update') }}">
                                <input type="hidden" name="method" value="POST">
                                <input type="hidden" name="chat" value="{{$chat['id']}}">
                                <div class="form-body">
                                    <!-- Name-->
                                    <div class="form-group has-error-name">
                                        <label class="col-md-3 control-label">Message Like <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="message_like" value="{{ isset($chat['message_like']) ? $chat['message_like'] : old('message_like')}}" placeholder="Message Like" class="form-control" required="">
                                        </div>
                                    </div>

                                    <!-- Reply with-->
                                    <div class="form-group has-error-name">
                                        <label class="col-md-3 control-label">Reply With <span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="reply_with" value="{{ isset($chat['reply_with']) ? $chat['reply_with'] : old('reply_with')}}" placeholder="Reply with" class="form-control" required="">
                                        </div>
                                    </div>

                                    <!-- FB page name -->
                                    <div class="form-group has-error-plan">
                                        <label class="col-md-3 control-label"> FB Page's Token <span class="required" aria-required="true">  </span></label>
                                        <div class="col-md-4">
                                            <select class="form-control c-square c-theme" name="setting_id" id = "setting_id" >
                                                @foreach($settings as $setting)
                                                <option value="{{ $setting->id }}" >
                                                    {{ $setting->fb_app_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4 col-md-offset-3">
                                            <button type="submit" class="btn green">{{ ($chat['id'] != "") ? __('page.update') : __('page.create') }} @lang('page.chat') </button>
                                            <a href="{{ route('chats') }}" class="btn btn-default">  @lang('page.cancel')</a>
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
