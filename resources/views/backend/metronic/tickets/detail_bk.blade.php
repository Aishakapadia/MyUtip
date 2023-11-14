@extends( admin_layout('master') )

@section('title')
    Manage {{ $module->father->title }}
@stop

@section('head_page_level_plugins')
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ admin_asset('assets/global/plugins/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
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
                        <a href="{{ route('ticket-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('ticket-manage') }}">Manage</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Details</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->

            <!-- BEGIN PAGE TITLE-->
            <h1 class="page-title"> {{ $module->father->title }}
                <small>information</small>
            </h1>
            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->

            <div class="row">
                <div class="col-md-12">

                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="{{ $module->father->icon }} font-green"></i>
                                <span class="caption-subject font-green sbold uppercase"> {{ $module->father->title }} Details </span>
                            </div>
                            <div class="actions">

                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="tabbable-line">

                                @foreach ( ['danger', 'warning', 'success', 'info'] as $msg )
                                    @if(Session::has('alert-' . $msg))
                                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                    @endif
                                @endforeach

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-info"></i>Ticket Information
                                                </div>
                                                <div class="actions">
                                                    <a href="{{ route('ticket-manage') }}" class="btn btn-default btn-sm">
                                                        <i class="fa fa-backward"></i> Go Back
                                                    </a>
                                                    {{--<a href="{{ route('ticket-edit.ticket', $ticket->id) }}" class="btn btn-default btn-sm">--}}
                                                    {{--<i class="fa fa-pencil"></i> Edit--}}
                                                    {{--</a>--}}
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Ticket Number: </div>
                                                    <div class="col-md-9 value"> {{ $ticket->ticket_number }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Vehicle Type: </div>
                                                    <div class="col-md-9 value"> {{ $ticket->relationVehicleType->title }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Vehicle Required At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i A', strtotime($ticket->vehicle_required_at)) }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> From Site: </div>
                                                    <div class="col-md-9 value"> {{ $ticket->relationFromSite->title }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> To Site: </div>
                                                    <div class="col-md-9 value"> {{ $ticket->relationToSite->title }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Drop Off Sites: </div>
                                                    <div class="col-md-9 value">
                                                        @if($ticket->relationDropOffSites)
                                                            <ul>
                                                                @foreach($ticket->relationDropOffSites as $site)
                                                                    <li >{{ $site->title }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Ticket Current Status: </div>
                                                    <div class="col-md-9 value"> <span class="label label-success">{{ $ticket->relationActiveStatus()->visible }}</span> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Review: </div>
                                                    <div class="col-md-9 value"> {!! $ticket->remarks !!} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Delivery Challan Number: </div>
                                                    <div class="col-md-9 value"> {!! $ticket->delivery_challan_number ? $ticket->delivery_challan_number : '<span class="help-block">NA</span>' !!} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Created At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($ticket->created_at)) }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Updated At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($ticket->updated_at)) }} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row --><!-- TICKET INFORMATION -->

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-info"></i>Description
                                                </div>
                                                <div class="actions">

                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr class="uppercase">
                                                            <th>Material Type</th>
                                                            <th>Material Code</th>
                                                            <th>Material Name</th>
                                                            <th>Quantity</th>
                                                            <th>Unit</th>
                                                            <th>Weight (KG)</th>
                                                            <th>PO Number</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if($ticket->details)
                                                            <?php $totalWeight = 0; ?>
                                                            @foreach($ticket->details as $detail)
                                                                <tr>
                                                                    <td>{{ $detail->material_type }}</td>
                                                                    <td>{{ $detail->material_code }}</td>
                                                                    <td>{{ $detail->material }}</td>
                                                                    <td>{{ $detail->quantity }}</td>
                                                                    <td>{{ $detail->unit }}</td>
                                                                    <td>{{ $detail->weight }}</td>
                                                                    <td>{{ $detail->po_number }}</td>
                                                                </tr>
                                                                <?php $totalWeight += $detail->weight ?>
                                                            @endforeach
                                                            <tr>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td>&nbsp;</td>
                                                                <td><strong>{{ $totalWeight }} KG</strong></td>
                                                                <td>&nbsp;</td>
                                                            </tr>
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row --><!-- DESCRIPTION -->

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-info"></i>Transporter Information
                                                </div>
                                                <div class="actions">

                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-scrollable table-scrollable-borderless">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr class="uppercase">
                                                            <th>Transporter</th>
                                                            <th>Vehicle Number</th>
                                                            <th>Driver Contact #</th>
                                                            <th>ETA</th>
                                                            <th>Status</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @if($ticket->relationTransporters()->count())
                                                            @foreach($transporterInformation as $transporter)
                                                                <tr style="background: {{ \App\TransporterStatus::find($transporter->pivot->transporter_status_id)->color_code }};">
                                                                    <td>{{ $transporter->title ? $transporter->title : 'NA' }}</td>
                                                                    <td>{{ $transporter->pivot->vehicle_number ? $transporter->pivot->vehicle_number : 'NA' }}</td>
                                                                    <td>{{ $transporter->pivot->driver_contact ? $transporter->pivot->driver_contact : 'NA' }}</td>
                                                                    <td>{{ isset($transporter->pivot->eta) ? date(\App\Setting::getStandardDateTimeFormat(), strtotime($transporter->pivot->eta)) : 'NA' }}</td>
                                                                    <td>{{ \App\TransporterStatus::find($transporter->pivot->transporter_status_id)->title }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row --><!-- TRANSPORTERS INFORMATION -->

                                @if($ticket->relationActiveStatus()->id  < \Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM'))
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="portlet blue-hoki box">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class="fa fa-info"></i>Update
                                                    </div>
                                                    <div class="actions">

                                                    </div>
                                                </div>
                                                <div class="portlet-body">

                                                    <div class="portlet-body form">

                                                        {!! Form::model($ticket, ['method' => 'PUT', 'route' => ['ticket-status.ticket', $ticket->id], 'class' => 'form-horizontal form-bordered']) !!}

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                                                                    <label class="control-label col-md-3">Status <span class="required" aria-required="true">*</span></label>
                                                                    <div class="col-md-9">
                                                                        {{ Form::select('status_id', $statuses, null, ['id' => 'status_id', 'class' => 'form-control']) }}
                                                                        @if($errors->has('status_id')) <span class="help-block"> {{ $errors->first('status_id') }} </span> @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                            </div>
                                                            <!--/span-->
                                                        </div>
                                                        <!--/row-->

                                                        {{--@if(PermissionHelper::isTransporter() && $ticket->relationActiveStatus()->id < 7)--}}
                                                        @if(PermissionHelper::isTransporter())
                                                            @if($ticket->relationActiveStatus()->id == \Config::get('constants.APPROVE_BY_ADMIN') || $ticket->relationActiveStatus()->id == \Config::get('constants.CANCELLED_BY_SUPPLIER'))
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group {{ $errors->has('vehicle_number') ? 'has-error' : '' }}">
                                                                            <label class="control-label col-md-3">Vehicle Number <span class="required" aria-required="true">*</span></label>
                                                                            <div class="col-md-9">
                                                                                {{ Form::text('vehicle_number', null, ['id' => 'vehicle_number', 'class' => 'form-control']) }}
                                                                                @if($errors->has('vehicle_number')) <span class="help-block"> {{ $errors->first('vehicle_number') }} </span> @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--/span-->
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <!--/span-->
                                                                </div>
                                                                <!--/row-->

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group {{ $errors->has('driver_contact') ? 'has-error' : '' }}">
                                                                            <label class="control-label col-md-3">Driver Contact Number <span class="required" aria-required="true">*</span></label>
                                                                            <div class="col-md-9">
                                                                                {{ Form::text('driver_contact', null, ['id' => 'driver_contact', 'class' => 'form-control']) }}
                                                                                @if($errors->has('driver_contact')) <span class="help-block"> {{ $errors->first('driver_contact') }} </span> @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--/span-->
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <!--/span-->
                                                                </div>
                                                                <!--/row-->

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group {{ $errors->has('eta') ? 'has-error' : '' }}">
                                                                            <label class="control-label col-md-3">ETA <span class="required" aria-required="true">*</span></label>
                                                                            <div class="col-md-9">

                                                                                <div class="input-group date form_datetime bs-datetime" data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}" data-date-format="yyyy-mm-dd H:i:s">
                                                                                    {{--<input name="eta" class="form-control" size="16" type="text" value="" readonly>--}}
                                                                                    {{ Form::text('eta', null, ['id' => 'eta', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                                                                                    <span class="input-group-addon">
                                                                        <button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
                                                                        </span>
                                                                                    <span class="input-group-addon">
                                                                            <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                                                                        </span>
                                                                                </div>
                                                                                @if($errors->has('eta')) <span class="help-block"> {{ $errors->first('eta') }} </span> @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--/span-->
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <!--/span-->
                                                                </div>
                                                                <!--/row-->
                                                            @endif
                                                        @endif

                                                        @if(PermissionHelper::isAdmin() && $ticket->relationActiveStatus()->id == \Config::get('constants.ACCEPT_BY_TRANSPORTER'))
                                                            @if($transporters)
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group {{ $errors->has('transporter_id') ? 'has-error' : '' }}">
                                                                            <label class="control-label col-md-3">Transporters <span class="required" aria-required="true">*</span></label>
                                                                            <div class="col-md-9">
                                                                                {{ Form::select('transporter_id', $transporters, null, ['id' => 'transporter_id', 'class' => 'form-control']) }}
                                                                                @if($errors->has('transporter_id')) <span class="help-block"> {{ $errors->first('transporter_id') }} </span> @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--/span-->
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <!--/span-->
                                                                </div>
                                                                <!--/row-->
                                                            @endif
                                                        @endif

                                                        @if(PermissionHelper::isSupplier() && $ticket->relationActiveStatus()->id == \Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER'))
                                                            @if(!$ticket->delivery_challan_number)
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group {{ $errors->has('delivery_challan_number') ? 'has-error' : '' }}">
                                                                            <label class="control-label col-md-3">DC Number <span class="required" aria-required="true">*</span></label>
                                                                            <div class="col-md-9">
                                                                                {{ Form::text('delivery_challan_number', null, ['id' => 'delivery_challan_number', 'class' => 'form-control']) }}
                                                                                @if($errors->has('delivery_challan_number')) <span class="help-block"> {{ $errors->first('delivery_challan_number') }} </span> @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!--/span-->
                                                                    <div class="col-md-6">
                                                                    </div>
                                                                    <!--/span-->
                                                                </div>
                                                                <!--/row-->
                                                            @endif
                                                        @endif

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                                                                    <label class="control-label col-md-3">Comments </label>
                                                                    <div class="col-md-9">
                                                                        {{ Form::textarea('comments', null, ['id' => 'comments', 'class' => 'form-control', 'rows' => 2]) }}
                                                                        @if($errors->has('comments')) <span class="help-block"> {{ $errors->first('comments') }} </span> @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                            </div>
                                                            <!--/span-->
                                                        </div>
                                                        <!--/row-->

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label class="control-label col-md-3"></label>
                                                                    <div class="col-md-9">
                                                                        {{ Form::hidden('current_status', $ticket->relationActiveStatus()->id) }}
                                                                        <button type="submit" class="btn green">SAVE</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--/span-->
                                                            <div class="col-md-6">
                                                            </div>
                                                            <!--/span-->
                                                        </div>
                                                        <!--/row-->
                                                        {!! Form::close() !!}

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- UPDATE TICKET STATUS FORM -->
                                @endif

                            </div><!-- .tabbable-line -->
                        </div><!-- .portlet-body -->
                    </div>

                </div>
            </div>

            <div class="portlet light portlet-fit bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-clock font-green"></i>
                        <span class="caption-subject bold font-green uppercase">Ticket History</span>
                        <span class="caption-helper">Request's statuses</span>
                    </div>
                    <div class="actions hide">
                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                            <label class="btn red btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" class="toggle" id="option1">Settings</label>
                            <label class="btn  red btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Tools</label>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    @if($ticket->relationStatusesWithDetail->count() > 0)
                    <div class="mt-timeline-2">

                        <div class="mt-timeline-line border-grey-steel"></div>
                        <ul class="mt-container">

                            @foreach($ticket->relationStatusesWithDetail as $status)
                                {{--{{ dump($status) }}--}}
                                <li class="mt-item">
                                    <div class="mt-timeline-icon bg-blue bg-font-blue border-grey-steel">
                                        <i class="{{ $status->icon }}"></i>
                                    </div>
                                    <div class="mt-timeline-content">
                                        <div class="mt-content-container bg-white border-grey-steel">
                                            <div class="mt-title">
                                                <h3 class="mt-content-title">{{ $status->title }}</h3>
                                            </div>
                                            <div class="mt-author">
                                                <div class="mt-avatar">
                                                    <img src="{{ admin_asset('assets/pages/media/users/avatar80_3.jpg') }}" />
                                                </div>
                                                <div class="mt-author-name">
                                                    <a href="javascript:;" class="font-blue-madison">{{ $status->name }} ({{ $status->role }})</a>
                                                </div>
                                                {{--<div class="mt-author-notes font-grey-mint">{{ \Carbon\Carbon::parse($status->created_at)->format('M d, Y - h:m A')}}</div>--}}
                                                <div class="mt-author-notes font-grey-mint">{{ date(\App\Setting::getStandardDateTimeFormat(), strtotime($status->created_at)) }}</div>
                                            </div>
                                            <div class="mt-content border-grey-steel">
                                                <p>{{ $status->comments ? $status->comments : 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                    @endif
                </div>
            </div>

        </div>
        <!-- END CONTENT BODY -->
    </div>
@stop

@section('foot_page_level_plugins')
    <script src="{{ admin_asset('assets/global/plugins/moment.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/clockface/js/clockface.js') }}" type="text/javascript"></script>
@stop

@section('foot_page_level_scripts')
    {{--<script src="{{ admin_asset('assets/pages/scripts/components-date-time-pickers.js') }}" type="text/javascript"></script>--}}
@stop

@section('foot_resources')
    <script>
        $(".form_datetime").datetimepicker({
            autoclose: true,
            isRTL: App.isRTL(),
            format: "dd MM yyyy hh:ii",
            fontAwesome: true,
            pickerPosition: (App.isRTL() ? "bottom-right" : "bottom-left"),
            minuteStep: 15
        });
    </script>
@stop