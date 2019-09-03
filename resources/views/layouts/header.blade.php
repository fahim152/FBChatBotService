<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="logo" class="logo-default"/>
            </a>
            @if(Auth::check())
            <div class="menu-toggler sidebar-toggler">
                <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
            </div>
            @endif
        </div>
        <!-- END LOGO -->
        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
        <!-- END RESPONSIVE MENU TOGGLER -->
        <!-- BEGIN PAGE TOP -->
        <div class="page-top">
            <!-- BEGIN TOP NAVIGATION MENU -->
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-extended dropdown-tasks dropdown-dark">
                        <a href="javascript:;" id="changeLanguage" data-id="@lang('page.lang_alt_code')" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                            <span class="badge badge-primary"> @lang('page.lang_alt') </span>
                        </a>
                    </li>
                    @if(Auth::check())
                    <li class="dropdown dropdown-user dropdown-dark">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="true">
                            <span class="username username-hide-on-mobile"> {{Auth::user()->name}} </span>
                            <!-- DOC: Do not remove below empty space(&nbsp;) as its purposely used -->
                            <img alt="" class="img-circle" src="{{ (isset(Auth::user()->picture) && file_exists('storage/images/' . Auth::user()->picture)) ? (asset('storage/images/' . Auth::user()->picture)) : ('/img/avatar.png') }}">
                            <span class="fa fa-angle-down" style="color: #aeb2ae;"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="{{ route('profile') }}">
                                    <i class="icon-user"></i> @lang('page.profile')
                                </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icon-key"></i> @lang('page.log_out')
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>

                    @else
                    <li class="separator hide"> </li>
                    <li class="dropdown dropdown-extended dropdown-inbox dropdown-dark">
                        <a href="/login" class="dropdown-toggle">
                            <span class="font-grey-salsa"> Login </span>
                        </a>
                    </li>
                    <li class="separator hide"> </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- END PAGE TOP -->
    </div>

    <!-- END HEADER INNER -->
</div>
<!-- END HEADER -->
