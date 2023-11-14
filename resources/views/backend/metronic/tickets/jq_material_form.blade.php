<div class="jq_material_box">
    <div class="row jq_close">
        <a href="javascript:;" data-repeater-delete="" class="btn btn-danger jq_remove_material_section"
           style="float:right; margin-right: 30px; margin-top: 15px;">
            <i class="fa fa-close"></i>
        </a>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('material_id') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Material Code <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    {{ Form::select('material_id[]', $materials, null, ['id' => 'material_id', 'class' => 'form-control select2 jq_material_id field_material_id']) }}
                    @if($errors->has('material_id')) <span
                            class="help-block"> {{ $errors->first('material_id') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('material_type') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Material Type <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    {{--{{ Form::select('material_type[]', ['' => 'Select', 'RM' => 'RM', 'PM' => 'PM'], null, ['id' => 'material_type', 'class' => 'form-control field_material_type']) }}--}}
                    {{ Form::text('material_type[]', null, ['id' => 'material_type', 'class' => 'form-control field_material_type jq_material_type', 'readonly' => 'readonly']) }}
                    @if($errors->has('material_type')) <span
                            class="help-block"> {{ $errors->first('material_type') }} </span> @endif
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
                    @if($errors->has('description')) <span
                            class="help-block"> {{ $errors->first('description') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('unit_id') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Unit <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    {{ Form::select('unit_id[]', $units, null, ['id' => 'unit_id', 'class' => 'form-control select2 field_unit_id']) }}
                    @if($errors->has('unit_id')) <span
                            class="help-block"> {{ $errors->first('unit_id') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
    </div>
    <!--/row-->

    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Quantity <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    {{ Form::number('quantity[]', null, ['id' => 'quantity', 'class' => 'form-control field_quantity', 'min' => '1']) }}
                    @if($errors->has('quantity')) <span
                            class="help-block"> {{ $errors->first('quantity') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('weight') ? 'has-error' : '' }}">
                <label class="control-label col-md-3">Weight (KG) <span class="required" aria-required="true">*</span></label>
                <div class="col-md-9">
                    {{ Form::number('weight[]', null, ['id' => 'weight', 'class' => 'form-control field_weight', 'min' => '1']) }}
                    @if($errors->has('weight')) <span class="help-block"> {{ $errors->first('weight') }} </span> @endif
                </div>
            </div>
        </div>
        <!--/span-->

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
</div>

<script src="{{ admin_asset('assets/custom/scripts/main.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $(".select2").select2();
    });

    $('.jq_material_id').on('change', function () {
        var el = $(this);
        var id = el.val();
        main.ajax(main.adminUrl('material/detail'), 'GET', {id: id}, function (data) {
            el.parents('.jq_material_box').find('.jq_description').val(data.title);
            el.parents('.jq_material_box').find('.jq_material_type').val(data.type);
        });
    });

    $('.jq_remove_material_section').on('click', function (e) {
        e.preventDefault();

        $(this).parents('.jq_material_box').fadeOut();
    });
</script>