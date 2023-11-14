<div class="portlet light bordered">

    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-location-arrow"></i>
            <span class="caption-subject font-purple sbold uppercase">4) Locations</span>
        </div>
        <div class="actions"></div>
    </div>

    <div class="portlet-body form">
        <div class="tabbable-custom nav-justified">
            @if($menus)
            <ul class="nav nav-tabs nav-justified">
                @foreach($menus as $key => $menu)
                    <?php
                    $active = '';
                    if ($location_id) {
                        $active = $menu->id == $location_id ? 'active' : '';
                    } else {
                        $active = $key == 0 ? 'active' : '';
                    }
                    ?>
                    <li class="{{ $active }}">
                        <a href="#tab_{{ ++$key }}" data-menu-id="{{ $menu->id }}" data-toggle="tab"> {{ $menu->title }} </a>
                    </li>
                @endforeach
            </ul>
            @endif

            @if($menu)
            <div class="tab-content">
                @foreach($menus as $key => $menu)
                    <?php
                    $active = '';
                    if ($location_id) {
                        $active = $menu->id == $location_id ? 'active' : '';
                    } else {
                        $active = $key == 0 ? 'active' : '';
                    }
                    ?>
                    <div class="tab-pane {{ $active }}" data-menu-id="{{ $menu->id }}" id="tab_{{ ++$key }}">
                        <div class="portlet-body">
                            <div class="dd sortable_navigation" id="nestable_list_{{ $menu->id }}">{!! generateMovableList($menu) !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>