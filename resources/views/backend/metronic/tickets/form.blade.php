<!-- Removing Autocomplete -->
<input style="opacity: 0; position: absolute;">
<input type="password" style="opacity: 0; position: absolute;">


<div class="form-body">
    <h3 class="form-section">Basic Info</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('vehicle_type_id') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Vehicle Type <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    @php
                        $attributes = [];
                        $attributes['id'] = 'vehicle_type_id';
                        $attributes['class'] = 'form-control select2';
                    @endphp
                    @if($pageMode == 'Edit' && $ticket->draft == 0)
                        @php
                            $attributes['disabled'] = 'disabled';
                        @endphp
                    @endif
                    {{ Form::select('vehicle_type_id', $vehicle_types, null, $attributes) }}
                    @if($errors->has('vehicle_type_id')) <span class="help-block"> {{ $errors->first('vehicle_type_id') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('vehicle_required_at') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Vehicle Required At <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    <div class="input-group date form_datetime bs-datetime" data-date="{{ date('Y-m-d').'T'.date('H:i:s').'Z' }}" data-date-format="yyyy-mm-dd H:i:s">
                        {{ Form::text('vehicle_required_at', null, ['id' => 'vehicle_required_at', 'class' => 'form-control', 'size' => 16, 'readonly' => 'readonly']) }}
                        <span class="input-group-addon">
                            <button class="btn default date-reset" type="button"><i class="fa fa-times"></i></button>
                        </span>
                        <span class="input-group-addon">
                            <button class="btn default date-set" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                    @if($errors->has('vehicle_required_at')) <span class="help-block"> {{ $errors->first('vehicle_required_at') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
    </div>
    <!--/row-->
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('site_id_from') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Site From: <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    @php
                        $attributes = [];
                        $attributes['id'] = 'site_id_from';
                        $attributes['class'] = 'form-control select2';
                    @endphp
                    @if($pageMode == 'Edit' && $ticket->draft == 0)
                        @php
                            $attributes['disabled'] = 'disabled';
                        @endphp
                    @endif
                    {{ Form::select('site_id_from', $sites, null, $attributes) }}
                    @if($errors->has('site_id_from')) <span class="help-block"> {{ $errors->first('site_id_from') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->

        <div class="col-md-6">
            <div class="form-group {{ $errors->has('site_id_to') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Site To: <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    @php
                        $attributes = [];
                        $attributes['id'] = 'site_id_to';
                        $attributes['class'] = 'form-control select2';
                    @endphp
                    @if($pageMode == 'Edit' && $ticket->draft == 0)
                        @php
                            $attributes['disabled'] = 'disabled';
                        @endphp
                    @endif
                    {{ Form::select('site_id_to', $sites, null, $attributes) }}
                    @if($errors->has('site_id_to')) <span class="help-block"> {{ $errors->first('site_id_to') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
    </div>
    <!--/row-->

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('ticket_drop_off_site') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Drop Off Sites:</label>
                <div class="col-md-9">
                    @php
                        $attributes = [];
                        $attributes['id'] = 'ticket_drop_off_site';
                        $attributes['class'] = 'form-control select2-multiple';
                        $attributes['multiple'] = true;
                    @endphp
                    @if($pageMode == 'Edit' && $ticket->draft == 0)
                        @php
                            $attributes['disabled'] = 'disabled';
                        @endphp
                    @endif
                    {{--{{ Form::select('site_id_drop_off', $sites, null, $attributes) }}--}}
                    {{ Form::select('ticket_drop_off_site_list[]', $drop_off_sites, null, $attributes) }}
                    @if($errors->has('ticket_drop_off_site')) <span class="help-block"> {{ $errors->first('ticket_drop_off_site') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
    </div>



    <h3 class="form-section">Material</h3>
    <!--/row-->

    @if($pageMode == 'Edit' && $ticket->draft == 1)
        @foreach($ticket->details as $key => $detail)

            <div class="jq_material_section">
                <div class="jq_material_box">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('material_id.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">Material Code <span class="required" aria-required="true">*</span></label>
                                <div class="col-md-9">
                                    {{ Form::select('material_id[]', $materials, $detail->material_id, ['id' => 'material_id', 'class' => 'form-control select2 jq_material_id field_material_id']) }}
                                    @if($errors->has('material_id.0')) <span class="help-block"> {{ $errors->first('material_id.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('material_type.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">Material Type <span class="required" aria-required="true">*</span></label>
                                <div class="col-md-9">
                                    {{--{{ Form::select('material_type[]', ['' => 'Select', 'RM' => 'RM', 'PM' => 'PM'], $detail->material_type, ['id' => 'material_type', 'class' => 'form-control field_material_type']) }}--}}
                                    {{ Form::text('material_type[]', $detail->material_type, ['id' => 'material_type', 'class' => 'form-control field_material_type jq_material_type', 'readonly' => 'readonly']) }}
                                    @if($errors->has('material_type.0')) <span class="help-block"> {{ $errors->first('material_type.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">Material Description</label>
                                <div class="col-md-9">
                                    {{ Form::text('description', $detail->material, ['id' => 'description', 'class' => 'form-control jq_description', 'readonly' => 'readonly']) }}
                                    @if($errors->has('description')) <span class="help-block"> {{ $errors->first('description') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('unit_id.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">Unit <span class="required" aria-required="true">*</span></label>
                                <div class="col-md-9">
                                    {{ Form::select('unit_id[]', $units, $detail->unit_id, ['id' => 'unit_id', 'class' => 'form-control select2 field_unit_id']) }}
                                    @if($errors->has('unit_id.0')) <span class="help-block"> {{ $errors->first('unit_id.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('quantity.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">Quantity <span class="required" aria-required="true">*</span></label>
                                <div class="col-md-9">
                                    {{ Form::number('quantity[]', $detail->quantity, ['id' => 'quantity', 'class' => 'form-control field_quantity', 'min' => '1']) }}
                                    @if($errors->has('quantity.0')) <span class="help-block"> {{ $errors->first('quantity.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('weight.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">Weight (KG) <span class="required" aria-required="true">*</span></label>
                                <div class="col-md-9">
                                    {{ Form::number('weight[]', $detail->weight, ['id' => 'weight', 'class' => 'form-control field_weight', 'min' => '1']) }}
                                    @if($errors->has('weight.0')) <span class="help-block"> {{ $errors->first('weight.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('po_number.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">PO Number <span class="required" aria-required="true">*</span></label>
                                <div class="col-md-9">
                                    {{ Form::text('po_number[]', $detail->po_number, ['id' => 'po_number', 'class' => 'form-control field_po_number']) }}
                                    @if($errors->has('po_number.0')) <span class="help-block"> {{ $errors->first('po_number.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                        <div class="col-md-6">
                            <div class="form-group {{ $errors->has('ibd_number.0') ? 'has-error' : '' }}">
                                <label class="control-label col-md-3">IBD Number </label>
                                <div class="col-md-9">
                                    {{ Form::text('ibd_number[]',  $detail->ibd_number, ['id' => 'ibd_number', 'class' => 'form-control field_ibd_number']) }}
                                    @if($errors->has('ibd_number.0')) <span class="help-block"> {{ $errors->first('ibd_number.0') }} </span> @endif
                                </div>
                            </div>
                        </div>
                        <!--/span-->
                    </div>
                    <!--/row-->

                </div><!-- .jq_material_box -->
            </div><!-- .jq_material_section -->

            <hr>

        @endforeach
    @else
        <div class="jq_material_section">
            <div class="jq_material_box">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('material_id.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">Material Code <span class="required" aria-required="true">*</span></label>
                            <div class="col-md-9">
                                {{ Form::select('material_id[]', $materials, null, ['id' => 'material_id', 'class' => 'form-control select2 jq_material_id field_material_id']) }}
                                @if($errors->has('material_id.0')) <span class="help-block"> {{ $errors->first('material_id.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('material_type.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">Material Type <span class="required" aria-required="true">*</span></label>
                            <div class="col-md-9">
                                {{--{{ Form::select('material_type[]', ['' => 'Select', 'RM' => 'RM', 'PM' => 'PM'], null, ['id' => 'material_type', 'class' => 'form-control field_material_type', 'readonly' => 'readonly']) }}--}}
                                {{ Form::text('material_type[]', null, ['id' => 'material_type', 'class' => 'form-control field_material_type jq_material_type', 'readonly' => 'readonly']) }}
                                @if($errors->has('material_type.0')) <span class="help-block"> {{ $errors->first('material_type.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>
                <!--/row-->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">Material Description</label>
                            <div class="col-md-9">
                                {{ Form::text('description', null, ['id' => 'description', 'class' => 'form-control jq_description', 'readonly' => 'readonly']) }}
                                @if($errors->has('description')) <span class="help-block"> {{ $errors->first('description') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('unit_id.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">Unit <span class="required" aria-required="true">*</span></label>
                            <div class="col-md-9">
                                {{ Form::select('unit_id[]', $units, null, ['id' => 'unit_id', 'class' => 'form-control select2 field_unit_id']) }}
                                @if($errors->has('unit_id.0')) <span class="help-block"> {{ $errors->first('unit_id.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>
                <!--/row-->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('quantity.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">Quantity <span class="required" aria-required="true">*</span></label>
                            <div class="col-md-9">
                                {{ Form::number('quantity[]', null, ['id' => 'quantity', 'class' => 'form-control field_quantity', 'min' => '1']) }}
                                @if($errors->has('quantity.0')) <span class="help-block"> {{ $errors->first('quantity.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('weight.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">Weight (KG) <span class="required" aria-required="true">*</span></label>
                            <div class="col-md-9">
                                {{ Form::number('weight[]', null, ['id' => 'weight', 'class' => 'form-control field_weight', 'min' => '1']) }}
                                @if($errors->has('weight.0')) <span class="help-block"> {{ $errors->first('weight.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>
                <!--/row-->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('po_number.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">PO Number <span class="required" aria-required="true">*</span></label>
                            <div class="col-md-9">
                                {{ Form::text('po_number[]', null, ['id' => 'po_number', 'class' => 'form-control field_po_number']) }}
                                @if($errors->has('po_number.0')) <span class="help-block"> {{ $errors->first('po_number.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('ibd_number.0') ? 'has-error' : '' }}">
                            <label class="control-label col-md-3">IBD Number </label>
                            <div class="col-md-9">
                                {{ Form::text('ibd_number[]', null, ['id' => 'ibd_number', 'class' => 'form-control field_ibd_number']) }}
                                @if($errors->has('ibd_number.0')) <span class="help-block"> {{ $errors->first('ibd_number.0') }} </span> @endif
                            </div>
                        </div>
                    </div>
                    <!--/span-->
                </div>
                <!--/row-->

            </div><!-- .jq_material_box -->
        </div><!-- .jq_material_section -->

        <hr>

        <a href="javascript:;" data-repeater-create="" class="col-md-offset-2 btn btn-info jq-repeater-add">
            <i class="fa fa-plus"></i> Add More Material
        </a>
    @endif

    {{--<h3 class="form-section">Files</h3>--}}

    {{--<div class="row">--}}
        {{--<div class="col-md-6">--}}
            {{--<div class="form-group {{ $errors->has('my_file') ? 'has-error' : '' }}">--}}
                {{--<label class="control-label col-md-3">Remarks </label>--}}
                {{--<div class="col-md-9">--}}
                    {{--{{ Form::file('my_file', null, ['id' => 'my_file', 'class' => 'form-control']) }}--}}
                    {{--@if($errors->has('my_file')) <span class="help-block"> {{ $errors->first('my_file') }} </span> @endif--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<!--/span-->--}}
    {{--</div>--}}
    {{--<!--/row-->--}}

    <h3 class="form-section">Other</h3>
    <!--/row-->

    {{--<div class="row">--}}

        {{--<div class="col-md-6">--}}
            {{--<div class="form-group {{ $errors->has('delivery_challan_number') ? 'has-error' : '' }}">--}}
                {{--<label class="control-label col-md-3">Delivery Challan # </label>--}}
                {{--<div class="col-md-9">--}}
                    {{--@php--}}
                        {{--$attributes = [];--}}
                        {{--$attributes['id'] = 'delivery_challan_number';--}}
                        {{--$attributes['class'] = 'form-control';--}}
                    {{--@endphp--}}
                    {{--@if($pageMode == 'Edit')--}}
                        {{--@php--}}
                            {{--$attributes['disabled'] = 'disabled';--}}
                        {{--@endphp--}}
                    {{--@endif--}}
                    {{--{{ Form::text('delivery_challan_number', null, $attributes) }}--}}
                    {{--@if($errors->has('delivery_challan_number')) <span class="help-block"> {{ $errors->first('delivery_challan_number') }} </span> @endif--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<!--/span-->--}}
    {{--</div>--}}
    {{--<!--/row-->--}}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Remarks </label>
                <div class="col-md-9">
                    {{ Form::textarea('remarks', null, ['id' => 'remarks', 'class' => 'form-control', 'rows' => 2]) }}
                    @if($errors->has('remarks')) <span class="help-block"> {{ $errors->first('remarks') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
    </div>
    <!--/row-->

</div>

<div class="form-actions">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-offset-3 col-md-9">
                    @if($pageMode == 'Edit')
                        {{ Form::hidden('update', 'yes', ['id' => 'update']) }}
                        {{ Form::hidden('ticket_id', $ticket->id, ['id' => 'ticket_id']) }}
                    @endif
                    <button type="button" name="save" id="save" class="btn yellow"><i class="fa fa-check"></i> Save</button>
                    <button type="button" name="submit" id="submit" class="btn green"><i class="fa fa-check"></i> Submit</button>
                    {{ link_to_route('ticket-manage', 'Cancel', [], ['class' => 'btn default']) }}
                </div>
            </div>
        </div>
        <div class="col-md-6"> </div>
    </div>
</div>