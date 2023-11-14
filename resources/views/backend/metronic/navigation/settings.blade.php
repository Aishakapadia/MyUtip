@extends( admin_layout('master') )

@section('title')
    Navigation Settings
@stop

@section('head')

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ admin_asset('assets/global/plugins/jquery-nestable/jquery.nestable.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

@stop

@section('content')

    <!-- BEGIN CONTENT BODY -->
    <div class="page-content">

        <!-- BEGIN PAGE HEADER-->
        <!-- BEGIN PAGE BAR -->
        <div class="page-bar">
            <ul class="page-breadcrumb">
                <li>
                    <a href="{{ admin_url('dashboard') }}">Home</a>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Navigation</span>
                    <i class="fa fa-circle"></i>
                </li>
                <li>
                    <span>Settings</span>
                </li>
            </ul>
        </div>
        <!-- END PAGE BAR -->

        <!-- BEGIN PAGE TITLE-->
        <h3 class="page-title">
            <i class="icon-direction"></i>
            Navigation
            <small>manage</small>
        </h3>
        <!-- END PAGE TITLE-->
        <!-- END PAGE HEADER-->


        <div class="row">
            <div class="col-md-12">

                <div class="portlet-body">
                    <div class="tabbable-custom nav-justified">
                        <ul class="nav nav-tabs nav-justified">
                            @foreach($menus as $key => $menu)
                                <li class="{{ $key == 0 ? 'active' : '' }}">
                                    <a href="#tab_{{ ++$key }}" data-menu-id="{{ $menu->id }}" data-toggle="tab"> {{ $menu->title }} </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($menus as $key => $menu)
                                <div class="tab-pane {{ $key == 0 ? 'active' : '' }}" data-menu-id="{{ $menu->id }}" id="tab_{{ ++$key }}">
                                    <div class="portlet-body">
                                        <div class="dd sortable_navigation" id="nestable_list_{{ $menu->id }}">
                                            {!! generateMovableList($menu) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row">

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

                                            <div class="form-group hide">
                                                <div class="col-md-12">
                                                    <span id="custom_links_msgs">
                                                        <div class="alert alert-success alert-dismissable">
                                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                                            <strong>Success!</strong> Indicates a successful or positive action.
                                                        </div>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-md-2 control-label">
                                                    Label <span class="required">*</span>
                                                </label>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control" name="title" id="title" placeholder="Label">
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-md-2 control-label">
                                                    Url
                                                </label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" name="url" id="url" placeholder="Url">
                                                    <span class="help-block">Leave url empty, if you want to create non clickable link or a group.</span>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="btn btn-transparent blue btn-circle btn-sm" id="add_list">
                                                        Save
                                                    </label>
                                                </div>
                                            </div>

                                            <div id="menu_lists">
                                                <table class="table">
                                                    <tr>
                                                        <th width="50px">
                                                            <input type="checkbox" class="check-all-custom-links">
                                                        </th>
                                                        <th>Label</th>
                                                        <th>Url</th>
                                                        <th>Action</th>
                                                    </tr>
                                                    @foreach($menu_list as $list)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="custom-links" id="{{ $list->id }}"
                                                                       data-title="{{ $list->title }}"
                                                                       data-slug="{{ $list->slug }}"
                                                                >
                                                            </td>
                                                            <td>{{ $list->title }}</td>
                                                            <td>{{ $list->url }}</td>
                                                            <td>
                                                                <a href="javascript:;" class="remove-list" data-id="{{ $list->id }}">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i> Remove
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- .col-md-6 -->


                        <div class="col-md-6">
                            <div class="portlet light bordered">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="fa fa-file-text"></i>
                                        <span class="caption-subject font-purple sbold uppercase">Pages</span>
                                    </div>
                                    <div class="actions">

                                        <a href="{!! URL::to(admin_url('page/create')) !!}" class="btn btn-transparent blue btn-circle btn-sm">New Page</a>

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
                                                <input type="checkbox" class="check-all-page-links">
                                            </th>
                                            <th>Page Title</th>
                                        </tr>
                                        @foreach($pages as $page)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="page-links" id="{{ $page->id }}"
                                                           data-title="{{ $page->title }}"
                                                           data-slug="{{ $page->slug }}"
                                                    >
                                                </td>
                                                <td>{{ $page->title }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div><!-- .col-md-6 -->

                    </div><!-- .row -->

                </div>

            </div>
        </div>

    </div>

@stop

@section('footer')

    <!-- BEGIN CUSTOM SCRIPTS -->
    <script src="{{ admin_asset('assets/custom/scripts/jquery.blockUI.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/custom/scripts/main.js') }}" type="text/javascript"></script>
    <!-- END CUSTOM SCRIPTS -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ admin_asset('assets/global/plugins/jquery-nestable/jquery.nestable.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    {{--<script src="{{ admin_asset('assets/pages/scripts/ui-nestable.min.js') }}" type="text/javascript"></script>--}}
    <script src="{{ admin_asset('assets/pages/scripts/ui-nestable.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->

@stop