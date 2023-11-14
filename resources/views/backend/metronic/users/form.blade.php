<!-- Removing Autocomplete -->
<input style="opacity: 0; position: absolute;">
<input type="password" style="opacity: 0; position: absolute;">


<div class="form-body">
    <div class="form-group {{ $errors->has('role_id') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Role <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('role_id', $roles, null, ['id' => 'role_id', 'class' => 'form-control']) }}
            @if($errors->has('role_id')) <span class="help-block"> {{ $errors->first('role_id') }} </span> @endif
        </div>
    </div>
    <div class="form-group" id="transporter_block" style="display: none;">
        <label for="multiple" class="control-label col-md-3">Transporter <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('user_transporter_list[]', $transporters, null, ['id' => 'transporter', 'class' => 'form-control select2-multiple']) }}
        </div>
    </div>
    <div class="form-group" id="site_block" style="display: none;">
        <label for="multiple" class="control-label col-md-3">Site <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('user_site_list[]', $sites, null, ['id' => 'site', 'class' => 'form-control select2-multiple']) }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Full Name <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control', 'placeholder' => 'Full Name']) }}
            @if($errors->has('name')) <span class="help-block"> {{ $errors->first('name') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Email <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' => 'Email']) }}
            @if($errors->has('email')) <span class="help-block"> {{ $errors->first('email') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Password @if($pageMode != 'Edit') <span class="required" aria-required="true">*</span> @endif</label>
        <div class="col-md-9">
            {{ Form::password('password', ['id' => 'password', 'class' => 'form-control']) }}
            @if($errors->has('password')) <span class="help-block"> {{ $errors->first('password') }} </span> @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Contact Number </label>
        <div class="col-md-9">
            {{ Form::text('mobile', null, ['id' => 'mobile', 'class' => 'form-control', 'placeholder' => 'Contact numbers if multiples comma separated']) }}
            @if($errors->has('mobile')) <span class="help-block"> {{ $errors->first('mobile') }} </span> @endif
        </div>
    </div>
    <div class="form-group last">
        <label class="control-label col-md-3">Status <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('active', [1 => 'Active', 0 => 'Inactive'], null, ['id' => 'active', 'class' => 'form-control']) }}
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Save</button>
            {{ link_to_route('user-manage', 'Cancel', [], ['class' => 'btn default']) }}
        </div>
    </div>
</div>
