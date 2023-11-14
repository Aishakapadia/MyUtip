@extends( admin_layout('master') )


@section('title')
    Navigation Settings
@stop


@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/jquery-nestable/jquery.nestable.css') }}" rel="stylesheet" type="text/css" />
@stop


@section('head_resources')

@stop


@section('content')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <!-- BEGIN PAGE HEADER-->

            <!-- BEGIN PAGE BAR -->
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="index.html">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>UI Features</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> Nestable List
                <small>Drag & drop hierarchical list with mouse and touch compatibility</small>
            </h1>
            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->
            <div class="note note-success">
                <span class="label label-danger">NOTE!</span>
                <span class="bold">Nestable List Plugin </span>
                supported in Firefox, Chrome, Opera, Safari, Internet Explorer 10 and Internet Explorer 9 only. Internet Explorer 8 not supported. For more info please check out
                <a href="http://dbushell.github.com/Nestable/" target="_blank">
                    the official documentation
                </a>.
            </div>
            <div class="portlet light bordered">
                <div class="portlet-body ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="margin-bottom-10" id="nestable_list_menu">
                                <button type="button" class="btn green btn-outline sbold uppercase" data-action="expand-all">Expand All</button>
                                <button type="button" class="btn red btn-outline sbold uppercase" data-action="collapse-all">Collapse All</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           {{--  <div class="row">
                <div class="col-md-6">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-bubble font-purple"></i>
                                <span class="caption-subject font-purple sbold uppercase">Nestable List 3</span>
                            </div>
                            <div class="actions">
                                <div class="btn-group btn-group-devided" data-toggle="buttons">
                                    <label class="btn btn-transparent grey-salsa btn-circle btn-sm active">
                                        <input type="radio" name="options" class="toggle" id="option1">New</label>
                                    <label class="btn btn-transparent grey-salsa btn-circle btn-sm">
                                        <input type="radio" name="options" class="toggle" id="option2">Returning</label>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="dd" id="nestable_list_3">
                                <ol class="dd-list">
                                    <li class="dd-item dd3-item" data-id="13">
                                        <div class="dd-handle dd3-handle"> </div>
                                        <div class="dd3-content"> Item 13 </div>
                                    </li>
                                    <li class="dd-item dd3-item" data-id="14">
                                        <div class="dd-handle dd3-handle"> </div>
                                        <div class="dd3-content"> Item 14 </div>
                                    </li>
                                    <li class="dd-item dd3-item" data-id="15">
                                        <div class="dd-handle dd3-handle"> </div>
                                        <div class="dd3-content"> Item 15 </div>
                                        <ol class="dd-list">
                                            <li class="dd-item dd3-item" data-id="16">
                                                <div class="dd-handle dd3-handle"> </div>
                                                <div class="dd3-content"> Item 16 </div>
                                            </li>
                                            <li class="dd-item dd3-item" data-id="17">
                                                <div class="dd-handle dd3-handle"> </div>
                                                <div class="dd3-content"> Item 17 </div>
                                            </li>
                                            <li class="dd-item dd3-item" data-id="18">
                                                <div class="dd-handle dd3-handle"> </div>
                                                <div class="dd3-content"> Item 18 </div>
                                            </li>
                                        </ol>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- .row --> --}}

            <div class="row">

                <div class="col-md-12">
                    <div class="portlet-body">

                        <div class="tabbable-custom nav-justified">

                            <ul class="nav nav-tabs nav-justified">
                                @foreach($menus as $key => $menu)
                                    <li class="{{ $key == 0 ? 'active' : '' }}">
                                        <a href="#tab_{{ ++$key }}" data-menu-id="{{ $menu->id }}"
                                           data-toggle="tab"> {{ $menu->title }} </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                @foreach($menus as $key => $menu)
                                    <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" data-menu-id="{{ $menu->id }}"
                                         id="tab_{{ ++$key }}">
                                        <div class="portlet-body">
                                            <div class="dd sortable_navigation" id="nestable_list_{{ $menu->id }}">
                                                @include(admin_view('navigation.navigation'))
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div><!-- .tab-content -->

                        </div>


                        <div class="row">

                            <div class="col-md-6">
                                <div class="portlet light bordered">

                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-file-text"></i>
                                            <span class="caption-subject font-purple sbold uppercase">Pages</span>
                                        </div>
                                        <div class="actions">
                                            <a href="{{ route('page-create') }}" class="btn btn-transparent blue btn-circle btn-sm">New Page</a>
                                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                <label class="btn btn-transparent green btn-circle btn-sm" id="addPageToNav">
                                                    Add to Menu
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet-body">
                                        <table class="table">
                                            <tr>
                                                <th width="50px">
                                                    <div class="md-checkbox has-error">
                                                        <input type="checkbox" class="group-checkable" id="parent">
                                                        <label for="parent">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span>
                                                        </label>
                                                    </div>
                                                </th>
                                                <th>Page Title</th>
                                            </tr>
                                            @foreach($pages as $page)
                                                <tr>
                                                    <td>
                                                        <div class="md-checkbox">
                                                            <input type="checkbox" id="checkbox{{$page->id}}" class="page-nav-links md-check" id="{{ $page->id }}"
                                                                   data-title="{{ $page->title }}"
                                                                   data-slug="{{ $page->slug }}"
                                                            >
                                                            <label for="checkbox{{$page->id}}">
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> </label>
                                                        </div>
                                                    </td>
                                                    <td>{{ $page->title }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .col-md-6 -->

                            <div class="col-md-6">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-link"></i>
                                            <span class="caption-subject font-purple sbold uppercase">Custom Links</span>
                                        </div>
                                        <div class="actions">
                                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                <label class="btn btn-transparent green btn-circle btn-sm" id="addUrlToNav">
                                                    Add to Menu
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="portlet-body form">

                                        <form action="" role="form" class="form-horizontal">
                                            <div class="form-body">

                                                <div class="form-group">
                                                    <label for="" class="col-md-2 control-label">
                                                        Url
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" name="nav-url" id="nav-url"
                                                               placeholder="Enter Url">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="" class="col-md-2 control-label">
                                                        Label
                                                    </label>
                                                    <div class="col-md-10">
                                                        <input type="text" class="form-control" name="nav-title"
                                                               id="nav-title" placeholder="Enter Label">
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><!-- .col-md-6 -->

                        </div><!-- .row -->

                    </div><!-- .portlet-body -->
                </div><!-- .col-md-12 -->

            </div><!-- .row -->

        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop


@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/plugins/jquery-nestable/jquery.nestable.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    <script src="{{ admin_asset('assets/pages/scripts/ui-nestable.js') }}" type="text/javascript"></script>
@stop


@section('foot_resources')

@stop
