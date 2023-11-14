@extends( admin_layout('master') )

@section('title')
    Manage {{ $module->father->title }}
@stop

@section('head_page_level_plugins')

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
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('role-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Manage</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> {{ $module->father->title }}
                <small>manage</small>
            </h1>
            <!-- END PAGE TITLE-->

            <!-- END PAGE HEADER-->


            <div class="row">
                <div class="col-md-12">

                    @foreach ( ['danger', 'warning', 'success', 'info'] as $msg )
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            </p>
                        @endif
                    @endforeach

                    <div class="portlet light portlet-fit">
                        <div class="portlet-body">

                            <div class="note note-success">
                                <h4 class="block">SELECTED ROLE IS: {{ strtoupper($role->title) }}</h4>
                                <p>You are going to assign some permissions to the system modules for the selected "{{ $role->title }}" role, please be carefull and go back if you are not sure what you are doing.</p>
                            </div>

                            <div class="table-container">
                                {{ Form::open(['url' => admin_url('role/permissions')]) }}

                                <div class="row" style="margin-bottom: 6px;">
                                    <div class="col-md-8"></div>
                                    <div class="col-md-4">
                                        <div class="table-group-actions pull-right">
                                            <div class="page-toolbar">
                                                <button type="submit" class="btn btn-primary yellow">
                                                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-actions-wrapper">
                                    <span> </span>
                                </div>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                    <tr role="row" class="heading">
                                        <th width=""> Module Name</th>
                                        <th width="">{!! module_parent_checkbox('jq_parent_view', 'View') !!}</th>
                                        <th width="">{!! module_parent_checkbox('jq_parent_add', 'Create') !!}</th>
                                        <th width="">{!! module_parent_checkbox('jq_parent_edit', 'Edit') !!}</th>
                                        <th width="">{!! module_parent_checkbox('jq_parent_delete', 'Delete') !!}</th>
                                        <th width="">{!! module_parent_checkbox('jq_parent_download', 'Download') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($modules)
                                        @foreach($modules as $module)
                                            @if($module->parent == 0)
                                                <tr>
                                                    <td>{{ $module->title }}</td>
                                                    <td>
                                                        @if($module->type == 1 || $module->type == 0)
                                                            <?php
                                                            if ($module->type == 1) {
                                                                $class = 'jq_view';
                                                            } else {
                                                                $class = 'jq_module';
                                                            }
                                                            ?>
                                                            <div class="md-checkbox has-warning">
                                                                {{ Form::checkbox('permissions['.$module->id.']', $module->id, in_array($module->id, $selected_permissions) ? true : null, ['class' => 'md-check '.$class, 'data-id' => $module->id, 'id' => $module->id]) }}
                                                                <label for="{{ $module->id }}">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> All
                                                                </label>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @foreach($modules as $row)
                                                    @if($row->parent == $module->id)
                                                        <tr>
                                                            <td>&nbsp; &nbsp; &nbsp; &nbsp; {{ $row->title }}</td>
                                                            <td>@if($row->type == 1) {!! module_child_checkbox($row, $selected_permissions, $module, 'jq_view child_of_') !!} @endif</td>
                                                            <td>@if($row->type == 2) {!! module_child_checkbox($row, $selected_permissions, $module, 'jq_add child_of_') !!} @endif</td>
                                                            <td>@if($row->type == 3) {!! module_child_checkbox($row, $selected_permissions, $module, 'jq_edit child_of_') !!} @endif</td>
                                                            <td>@if($row->type == 4) {!! module_child_checkbox($row, $selected_permissions, $module, 'jq_delete child_of_') !!} @endif</td>
                                                            <td>@if($row->type == 5) {!! module_child_checkbox($row, $selected_permissions, $module, 'jq_download child_of_') !!} @endif</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>

                                {{ Form::hidden('role_id', Request::segment(4)) }}
                                {{ Form::close() }}

                            </div><!-- .table-container -->

                        </div><!-- .portlet-body -->
                    </div><!-- .portlet light portlet-fit -->

                </div><!-- .col-md-12 -->
            </div><!-- .row -->



        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop

@section('foot_page_level_plugins')

@stop

@section('foot_page_level_scripts')

@stop

@section('foot_resources')
    <script>
        $(function() {
            //region Parents checkbox actions
            $('.jq_parent_view').on('click', function() {
                $('.jq_view').not(this).prop('checked', this.checked);
                $('.jq_module').prop('checked', this.checked);
            });

            $('.jq_parent_add').on('click', function() {
                $('.jq_add').prop('checked', this.checked);
            });

            $('.jq_parent_edit').on('click', function() {
                $('.jq_edit').prop('checked', this.checked);
            });

            $('.jq_parent_delete').on('click', function() {
                $('.jq_delete').prop('checked', this.checked);
            });

            $('.jq_parent_download').on('click', function() {
                $('.jq_download').prop('checked', this.checked);
            });
            //endregion


            //region If all children has been checked - parent should be checked.
            $('.jq_view').on('click', function () {
                if ($('.jq_view:checked').length == $('.jq_view').length) {
                    $('.jq_parent_view').prop('checked', true);
                } else {
                    $('.jq_parent_view').prop('checked', false);
                }
            });

            $('.jq_add').on('click', function () {
                if ($('.jq_add:checked').length == $('.jq_add').length) {
                    $('.jq_parent_add').prop('checked', true);
                } else {
                    $('.jq_parent_add').prop('checked', false);
                }
            });

            $('.jq_edit').on('click', function () {
                if ($('.jq_edit:checked').length == $('.jq_edit').length) {
                    $('.jq_parent_edit').prop('checked', true);
                } else {
                    $('.jq_parent_edit').prop('checked', false);
                }
            });

            $('.jq_delete').on('click', function () {
                if ($('.jq_delete:checked').length == $('.jq_delete').length) {
                    $('.jq_parent_delete').prop('checked', true);
                } else {
                    $('.jq_parent_delete').prop('checked', false);
                }
            });

            $('.jq_download').on('click', function () {
                if ($('.jq_download:checked').length == $('.jq_download').length) {
                    $('.jq_parent_download').prop('checked', true);
                } else {
                    $('.jq_parent_download').prop('checked', false);
                }
            });
            //endregion

            $('.jq_module').on('click', function() {
                var id = $(this).data('id');
                $('.child_of_'+id).prop('checked', this.checked);
            });
        });
    </script>
@stop
