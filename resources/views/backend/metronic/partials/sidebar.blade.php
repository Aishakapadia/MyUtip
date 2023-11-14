<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- BEGIN SIDEBAR -->
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
        <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
        <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
        <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
        <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
        <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
        <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
            data-slide-speed="200" style="padding-top: 20px">

            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper hide">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler"></div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>

            <?php $current_path = substr(Request::path(), 6);
            $x = explode('/', $current_path);
            if (count($x) > 2) {
                $current_path = $x[0] . '/' . $x[1];
            } else {
                $current_path = $current_path;
            }
            ?>

            @if($sidebar_menu)
                @foreach($sidebar_menu as $menu)
                    <li class="heading">
                        <h3 class="uppercase">{{ $menu['module_type_title'] }}</h3>
                    </li>

                    @if($menu['sub'])
                        @foreach($menu['sub'] as $sub)
                            @if($sub['visible_in_sidebar'] == 1)

                                <?php
                                //dump($sub);
                                $group_urls = [];
                                foreach ($sub['sub_sub'] as $row) {
                                    $group_urls[] = $row['sub_url'];
                                }
                                ?>

                                @if($sub['main_url'] != '')

                                    <?php //$selected = SidebarHelper::instance()->checkSidebarGroup( SidebarHelper::SB_DASHBOARD); ?>
                                    <?php $selected = $current_path == $sub['main_url'] ? true : false; ?>
                                    <li class="nav-item  {{ $selected ? 'active' : '' }}">
                                        <a href="{{ admin_url($sub['main_url']) }}" class="nav-link nav-toggle">
                                            <i class="{{ $sub['main_icon'] }}"></i>
                                            <span class="title">{{ $sub['main_title'] }} </span>
                                            @if ( $selected )
                                                <span class="arrow {{ $selected ? 'open' : '' }}"></span>
                                                @if ($selected ) <span class="selected"></span> @endif
                                            @endif
                                        </a>
                                    </li>

                                @else

                                    @if(PermissionHelper::isAllowedModuleId($sub['main_id']))
                                        <?php //$selected = SidebarHelper::instance()->checkSidebarGroup( SidebarHelper::SB_USER ); ?>
                                        <?php $selected = in_array($current_path, $group_urls) ? true : false; ?>
                                        <li class="nav-item {{ $selected ? 'active open' : '' }}">
                                            <a href="javascript:;" class="nav-link nav-toggle">
                                                <i class="{{ $sub['main_icon'] }}"></i>
                                                <span class="title">{{ $sub['main_title'] }}</span>
                                                <span class="arrow {{ $selected ? 'open' : '' }}"></span>
                                                @if ($selected ) <span class="selected"></span> @endif
                                            </a>

                                            @if($sub['sub_sub'])
                                                <ul class="sub-menu">
                                                    @foreach($sub['sub_sub'] as $sub_sub)
                                                        @if($sub_sub['visible_in_sidebar'] == 1)
                                                            <?php //$state = SidebarHelper::instance()->checkSidebarUrl( 'user') ? 'active' : ''; ?>
                                                            <?php $state = $current_path == $sub_sub['sub_url'] ? 'active' : ''; ?>
                                                            @if(PermissionHelper::isAllowed($sub_sub['sub_url']))
                                                                <li class="nav-item {{ $state }}">
                                                                    <a href="{{ admin_url($sub_sub['sub_url']) }}"
                                                                       class="nav-link ">
                                                                        <span class="title">{{ $sub_sub['sub_title'] }}</span>
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </li>
                                    @endif

                                @endif
                            @endif
                        @endforeach
                    @endif
                @endforeach
            @endif

        </ul>
        <!-- END SIDEBAR MENU -->
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>
<!-- END SIDEBAR -->