<?php $nav = isset($nav) ? $nav : ""; ?>
<?php $subnav = isset($subnav) ? $subnav : "";?>

<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        @if(Auth::check() && isset(Auth::user()->menus))
            <ul class='page-sidebar-menu' data-keep-expanded='false' data-auto-scroll='true' data-slide-speed='200'>
                @foreach (Auth::user()->menus as $item)
                    @if ($item['visible'])
                        <?php $class = $nav == $item['tag'] ? 'active open' : ''; ?>
                        <?php $url = isset($item['url']) ? url($item['url']) : "javascript:;"; ?>
                        <?php $has_subitem = isset($item['items']) ? true : false; ?>
                        @if ($item['tag'] != 'heading')
                        <li class='nav-item {{$class}}'>
                            <a href={{$url}} class='nav-link nav-toggle'>
                                <?=$item['icon']?>
                                <span class='title'>@lang('page.'.$item['lang_tag'])</span>
                                @if($has_subitem)
                                    <span class="arrow <?= ($nav == $item['tag']) ? 'open': '' ?>"></span>
                                @endif
                            </a>
                            @if ($has_subitem)
                            <ul class='sub-menu'>
                                <?php $subitems = $item['items']; ?>
                                @foreach ($subitems as $subitem)
                                    @if ($subitem['visible'])
                                        <?php $subclass = ($nav == $item['tag'] and $subnav == $subitem['tag']) ? 'active open' : ''; ?>
                                        <?php $url = url($subitem['url']); ?>
                                        <li class='nav-item {{$subclass}}'>
                                            <a href={{$url}} class='nav-link'>
                                                <span class='title'>@lang('page.'.$subitem['lang_tag']) </span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @else
                        <li class="heading">
                            <h3 class="uppercase">@lang('page.'.$item['lang_tag'])</h3>
                        </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        @endif
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
