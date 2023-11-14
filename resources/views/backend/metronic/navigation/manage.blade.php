@extends( admin_layout('master') )


@section('title')
    Manage Navigation
@stop


@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/jquery-nestable/jquery.nestable.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
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

                        <div class="mt-element-step">
                            <div class="row step-line">
                                <div class="col-md-3 mt-step-col first done">
                                    <div class="mt-step-number bg-dark font-grey">1</div>
                                    <div class="mt-step-title uppercase font-grey-cascade">Pages</div>
                                    <div class="mt-step-content font-grey-cascade">Manage pages from pages section.</div>
                                </div>
                                <div class="col-md-3 mt-step-col done">
                                    <div class="mt-step-number bg-dark font-grey">2</div>
                                    <div class="mt-step-title uppercase font-grey-cascade">Links</div>
                                    <div class="mt-step-content font-grey-cascade">Manage links, create your custom or url
                                        links if needed.
                                    </div>
                                </div>
                                <div class="col-md-3 mt-step-col active">
                                    <div class="mt-step-number bg-dark font-grey">3</div>
                                    <div class="mt-step-title uppercase font-grey-cascade">List</div>
                                    <div class="mt-step-content font-grey-cascade">Manage list add your pages or created
                                        links.
                                    </div>
                                </div>
                                <div class="col-md-3 mt-step-col last">
                                    <div class="mt-step-number bg-dark font-grey">4</div>
                                    <div class="mt-step-title uppercase font-grey-cascade">Locations</div>
                                    <div class="mt-step-content font-grey-cascade">Add elements from "List" to your
                                        Navigation's locations.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <div class="portlet light bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-file-text"></i>
                                            <span class="caption-subject font-purple sbold uppercase">1) Pages</span>
                                        </div>
                                        <div class="actions">
                                            <a href="{!! URL::to(admin_url('page/create')) !!}"
                                               class="btn btn-transparent blue btn-circle btn-sm">New Page
                                            </a>

                                            <div class="btn-group btn-group-devided" data-toggle="buttons">
                                                <label class="btn btn-transparent green btn-circle btn-sm" id="add_page_to_list">
                                                    ADD TO LIST
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="portlet-body">
                                        <table class="table table-hover table-condensed kjq_parent_child">
                                            <tr>
                                                <th width="50px">
                                                    {!! myCheckbox('parent-pages', 'check-all-pages kjq_parent', null, true) !!}
                                                </th>
                                                <th>Page Title</th>
                                            </tr>
                                            @foreach($pages as $page)
                                                <tr>
                                                    <td>
                                                        {!!  myCheckbox('page_'.$page->id, 'pages kjq_child', null, false, ['data-title' => $page->title, 'data-slug' => $page->slug, 'data-id' => $page->id]) !!}
                                                    </td>
                                                    <td>{{ $page->title }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                            </div><!-- .col-md-6 -->


                            <div class="col-md-6" id="module-links">

                            </div><!-- .col-md-6 -->

                        </div><!-- pages & links -->

                        <div class="row">
                            <div class="col-md-12" id="module-lists">

                            </div>
                        </div><!-- list -->

                        <div class="row">
                            <div class="col-md-12" id="module-locations">

                            </div>
                        </div><!-- locations -->

                    </div>

                </div><!-- .col-md-12 -->
            </div><!-- .row -->





        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop


@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/plugins/jquery-nestable/jquery.nestable.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_page_level_scripts')
    <script src="{{ admin_asset('assets/pages/scripts/ui-nestable.js') }}" type="text/javascript"></script>
    {{--<script src="{{ admin_asset('assets/pages/scripts/ui-sweetalert.min.js') }}" type="text/javascript"></script>--}}
    <script src="{{ admin_asset('assets/pages/scripts/ui-confirmations.min.js') }}" type="text/javascript"></script>
@stop


@section('foot_resources')

@stop
