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
                        <a href="{{ route('pop-manage') }}">{{ $module->father->title }}</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <a href="{{ route('pop-manage') }}">Manage</a>
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
                    <!-- Begin: life time stats -->
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


                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-info"></i>Details 
                                                </div>
                                                <div class="actions">
                                                    <a href="javascript:history.back()" class="btn btn-default btn-sm">
                                                        <i class="fa fa-backward"></i> Go Back
                                                    </a>
                                                    {{--<a href="{{ route('pop-edit.pop', $induction->id) }}" class="btn btn-default btn-sm">--}}
                                                        {{--<i class="fa fa-pencil"></i> Edit --}}
                                                    {{--</a>--}}
                                                </div>
                                            </div>
                                            <div class="portlet-body">

                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Region:</div>
                                                    <div class="col-md-9 value"> {{ $induction->distribution->region }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Distributor Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->distributor }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Distributor Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->distribution->distributor_name }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> TM Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->user->name }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> DSR Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->dsr_code }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> DSR Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->dsr_name }} ({{ $induction->dsr_code }})</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> PJP Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pjp_code }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Working Date:</div>
                                                    <div class="col-md-9 value"> {{ $induction->doc_date ? date('M d, Y h:i:s A', strtotime($induction->doc_date)) : '' }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Pop Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_code }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Pop Section Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_section_code }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Pop Section Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_section_name }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Pop Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_name }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Channel Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_channel_code_new ? $induction->pop_channel_code_new : 'N/A' }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Channel Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_channel_name_new ? $induction->pop_channel_name_new : 'N/A' }}</div>
                                                </div>


                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Area Type Code:</div>
                                                    <div class="col-md-9 value"> {{ $induction->area_type_code_new ? $induction->area_type_code_new : 'N/A' }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Area Type Name:</div>
                                                    <div class="col-md-9 value"> {{ $induction->area_type_name_new ? $induction->area_type_name_new : 'N/A' }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> POP Address:</div>
                                                    <div class="col-md-9 value"> {{ $induction->pop_address ? $induction->pop_address : 'N/A' }}</div>
                                                </div>

                                                {{--<div class="row static-info">--}}
                                                    {{--<div class="col-md-3 name"> Pop Permanently Closed:</div>--}}
                                                    {{--<div class="col-md-9 value">--}}
                                                        {{--<span class="label label-success"> {{ $induction->pop_closed_permanently ? 'Yes' : 'No' }} </span>--}}
                                                        {{--@if($induction->pop_closed_permanently)--}}
                                                            {{--<span class="label label-sm label-success">Yes</span>--}}
                                                        {{--@else--}}
                                                            {{--<span class="label label-sm label-danger">No</span>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}

                                                {{--<div class="row static-info">--}}
                                                    {{--<div class="col-md-3 name"> Pop Temporarily Closed:</div>--}}
                                                    {{--<div class="col-md-9 value">--}}
                                                        {{--<span class="label label-success"> {{ $induction->pop_closed_temporarily ? 'Yes' : 'No' }} </span>--}}
                                                        {{--@if($induction->pop_closed_temporarily)--}}
                                                            {{--<span class="label label-sm label-success">Yes</span>--}}
                                                        {{--@else--}}
                                                            {{--<span class="label label-sm label-danger">No</span>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}

                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Created At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($induction->created_at)) }} </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-3 name"> Updated At: </div>
                                                    <div class="col-md-9 value"> {{ date('M d, Y h:i:s A', strtotime($induction->updated_at)) }} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row -->

                                <div class="row">

                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet yellow-crusta box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Retailer Information
                                                </div>
                                                <div class="actions hide">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Name:</div>
                                                    <div class="col-md-7 value"> {{ $induction->retailer_name }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Contact Number:</div>
                                                    <div class="col-md-7 value"> {{ $induction->retailer_contact  }}</div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> NIC:</div>
                                                    <div class="col-md-7 value"> {{ $induction->retailer_nic  }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet red-sunglo box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Verification Status
                                                </div>
                                                <div class="actions">
                                                    @if(!$induction->pop_closed_temporarily)
                                                        @if(PermissionHelper::isAllowed('induction/reject'))
                                                            <a href="{{ \URL::to(admin_url('induction/reject/' . $induction->id)) }}"
                                                            class="btn btn-default btn-sm">
                                                            <i class="fa fa-times"></i> Reject
                                                            </a>
                                                        @endif
                                                        @if($induction->pop_closed_permanently)
                                                            @if(PermissionHelper::isAllowed('induction/approve'))
                                                                <a href="{{ \URL::to(admin_url('induction/approve/' . $induction->id)) }}" class="btn btn-success btn-sm">
                                                                    <i class="fa fa-check"></i> Approve
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Verification Status by TM:</div>
                                                    <div class="col-md-7 value">
                                                        @if($induction->verification_status_tm == 1)
                                                            Verified at {{ $induction->verification_updated_at_tm->format('Md, Y h:ia') }}
                                                        @elseif($induction->verification_status_tm == 2)
                                                            Rejected
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> TM Comments:</div>
                                                    <div class="col-md-7 value"> {{ $induction->comments_tm ? $induction->comments_tm : 'N/A' }}</div>
                                                </div>

                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> Verification Status by SMOLLAN:</div>
                                                    <div class="col-md-7 value">
                                                        @if($induction->verification_status_smollan == 1)
                                                            Verified at {{ $induction->verification_updated_at_tm->format('Md, Y h:ia') }}
                                                        @elseif($induction->verification_status_smollan == 2)
                                                            Rejected
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> SMOLLAN Comments:</div>
                                                    <div class="col-md-7 value"> {{ $induction->comments_smollan ? $induction->comments_smollan : 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row -->

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet box blue">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i>Pop Images
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <table width="100%">
                                                    <tr>
                                                        <th>Signboard:</th>
                                                        <th>Counter:</th>
                                                    </tr>
                                                    <tr>
                                                        <td>

                                                            @if($induction->photo_signboard)
                                                                <a href="#signboard" data-toggle="modal">
                                                                    <img src="{{ url('uploads/images/'. $induction->photo_signboard) }}"
                                                                         alt="" class="img-thumbnail"
                                                                         style="width: 200px;">
                                                                </a>
                                                            @else
                                                                <img src="https://dummyimage.com/200x153/adadad/fff&text=N/A"
                                                                     alt="" class="img-thumbnail"
                                                                     style="width: 200px;">
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($induction->photo_counter)
                                                                <a href="#counter" data-toggle="modal">
                                                                    <img src="{{ url('uploads/images/'. $induction->photo_counter) }}"
                                                                         alt="" class="img-thumbnail"
                                                                         style="width: 200px;">
                                                                </a>
                                                            @else
                                                                <img src="https://dummyimage.com/200x153/adadad/fff&text=N/A"
                                                                     alt="" class="img-thumbnail"
                                                                     style="width: 200px;">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row -->

                                <div class="row">
                                    <div class="col-md-12 col-sm-12">

                                        <div class="portlet grey-cascade box" id="location-portlet"
                                             data-latitude="{{ $induction->latitude }}"
                                             data-longitude="{{ $induction->longitude }}">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-map"></i>Location
                                                </div>
                                                <div class="actions hide">
                                                    <a href="javascript:;" class="btn btn-default btn-sm">
                                                        <i class="fa fa-pencil"></i> Edit </a>
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <div id="gmap_marker" class="gmaps"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .row -->


                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>


    <div id="signboard" class="modal fade" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Signboard Photo</h4>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <img alt="" src="{{ url('uploads/images/'. $induction->photo_signboard) }}"></div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="counter" class="modal fade" tabindex="-1" data-replace="true">
        <div class="modal-dialog modal-full">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Counter Photo</h4>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <img alt="" src="{{ url('uploads/images/'. $induction->photo_counter) }}"></div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot_page_level_plugins')
    {{--<script src="{{ admin_asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>--}}
    {{--<script src="{{ admin_asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>--}}

    <script src="http://maps.google.com/maps/api/js?key=AIzaSyBhQb2Z7yrraVvPk9z6Jy5lEj6snIAULaE" type="text/javascript"></script>
    <script src="{{ admin_asset('assets/global/plugins/gmaps/gmaps.min.js') }}" type="text/javascript"></script>
@stop

@section('foot_page_level_scripts')
    {{--<script src="{{ admin_asset('assets/pages/scripts/ui-modals.min.js') }}" type="text/javascript"></script>--}}
@stop

@section('foot_resources')
    <script>
        $(function () {
            $('#title').on('keyup', function () {
                var title = $(this).val();
                $('#slug').val(main.convertToSlug(title));
            });

            var latitude = $('#location-portlet').data('latitude');
            var longitude = $('#location-portlet').data('longitude');

            // Unilever Head Office lat/long
//            latitude = '24.8533008';
//            longitude = '67.0321879';

            console.log(latitude, longitude);

            map = new GMaps({
                div: '#gmap_marker',
                lat: latitude,
                lng: longitude,
                enableNewStyle: true
            });

            map.addMarker({
                lat: latitude,
                lng: longitude,
                title: 'Lima',
//                click: function(e) {
//                    alert('You clicked in this marker');
//                },
                infoWindow: {
                    content: '<p>{{ $induction->pop_name }}</p>'
                }
            });
        });
    </script>
@stop