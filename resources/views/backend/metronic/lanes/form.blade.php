<!-- Removing Autocomplete -->
<input style="opacity: 0; position: absolute;">
<input type="password" style="opacity: 0; position: absolute;">


<div class="form-body">
    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Title <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::text('title', null, ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title']) }}
            @if($errors->has('title')) <span class="help-block"> {{ $errors->first('title') }} </span> @endif
        </div>
    </div>
    {{--<div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">--}}
        {{--<label class="control-label col-md-3">Slug <span class="required" aria-required="true">*</span></label>--}}
        {{--<div class="col-md-9">--}}
            {{--{{ Form::text('slug', null, ['id' => 'slug', 'class' => 'form-control', 'placeholder' => 'Slug', 'readonly' => true]) }}--}}
            {{--@if($errors->has('slug')) <span class="help-block"> {{ $errors->first('slug') }} </span> @endif--}}
        {{--</div>--}}
    {{--</div>--}}
    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Description </label>
        <div class="col-md-9">
            {{ Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control']) }}
            @if($errors->has('description')) <span class="help-block"> {{ $errors->first('description') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('site_id_from') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Site From <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('site_id_from', $sites, null, ['id' => 'site_id_from', 'class' => 'form-control select2']) }}
            @if($errors->has('site_id_from')) <span class="help-block"> {{ $errors->first('site_id_from') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('site_id_to') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Site To <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('site_id_to', $sites, null, ['id' => 'site_id_to', 'class' => 'form-control select2']) }}
            @if($errors->has('site_id_to')) <span class="help-block"> {{ $errors->first('site_id_to') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('lane_transporter_list.0') ? 'has-error' : '' }}">
        <label for="multiple" class="control-label col-md-3">Transporters <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('lane_transporter_list[]', $transporters, null, ['id' => 'transporters', 'class' => 'form-control select2-multiple', 'multiple' => true]) }}
            @if($errors->has('lane_transporter_list')) <span class="help-block"> {{ $errors->first('lane_transporter_list.0') }} </span> @endif
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Status <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('active', [1 => 'Active', 0 => 'Inactive'], null, ['id' => 'active', 'class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group last {{ $errors->has('sort') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Sort <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::number('sort', isset($page->sort) ? $page->sort : 0, ['id' => 'sort', 'class' => 'form-control', 'min' => '0']) }}
            <span class="help-block"> Please enter sort value, if you would like to set order priority. </span>
            @if($errors->has('sort')) <span class="help-block"> {{ $errors->first('sort') }} </span> @endif
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Save</button>
            {{ link_to_route('lane-manage', 'Cancel', [], ['class' => 'btn default']) }}
        </div>
    </div>
</div>
