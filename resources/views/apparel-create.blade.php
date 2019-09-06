@extends('layouts.master')

@section('title', 'Create apparel  ')


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
                <a href="{{ route('apparels') }}">  @lang('page.apparels')</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span class="active">{{ ($apparel['id'] != "") ? "Edit" : "Create" }}</span>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->

        <div class="page-content-col">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-user"></i>
                        <span class="caption-subject bold uppercase"> {{ ($apparel['id'] != "") ? "Edit apparel" : "Create apparel" }} </span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="ajax-form" class="form-horizontal" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="action" value="{{ route('apparels_update') }}">
                                <input type="hidden" name="method" value="POST">
                                <input type="hidden" name="apparel" value="{{$apparel['id']}}">
                                <div class="form-body">
                                    <!-- Name-->
                                    <div class="form-group has-error-name">
                                        <label class="col-md-3 control-label">Apparel Model<span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="model" value="{{ isset($apparel['model']) ? $apparel['model'] : old('model')}}" placeholder="Model Name" class="form-control" required="">
                                        </div>
                                    </div>

                                    <!-- Name Bangla-->
                                    <div class="form-group has-error-name_bangla">
                                        <label class="col-md-3 control-label">Quantity<span class="required" aria-required="true"> * </span></label>
                                        <div class="col-md-4">
                                            <input type="text" name="quantity" value="{{ isset($apparel['quantity']) ? $apparel['quantity'] : old('quantity')}}" placeholder="Current Inventory Quantity" class="form-control" required="">
                                        </div>
                                    </div>



                                    @if(isset($apparel['image_link']))

                                    <div class="form-group" >
                                            <label class="col-md-3 control-label">Current apparel Picture <span class="required" aria-required="true"> </span></label>
                                            <div class="col-md-4">
                                                <img src="{{ env('APP_URL') . $apparel['image_link'] }} " alt="" style="height: 200px;">
                                            </div>
                                        </div>
                                    @endif

                                    <!--Picture Name-->
                                    <div class="form-group has-error-picture">
                                        <label class="col-md-3 control-label"> Change Picture <span class="required" aria-required="true"> </span></label>
                                        <div class="col-md-4">
                                            <input type="file" name="picture" value="{{ isset($apperal_picture) ? $apperal_picture : "" }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-4 col-md-offset-3">
                                            <button type="submit" class="btn green">{{ ($apparel['id'] != "") ? __('page.update') : __('page.create') }} @lang('page.apparel') </button>
                                            <a href="{{ route('apparels') }}" class="btn btn-default">  @lang('page.cancel')</a>
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
