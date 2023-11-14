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
    <div class="form-group">
        <label class="control-label col-md-3">Gender</label>
        <div class="col-md-9">
            <select class="form-control">
                <option value="">Male</option>
                <option value="">Female</option>
            </select>
            <span class="help-block"> Select your gender. </span>
        </div>
    </div>
    <div class="form-group {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Date of Birth</label>
        <div class="col-md-9">
            {{ Form::text('date_of_birth', null, ['id' => 'date_of_birth', 'class' => 'form-control', 'placeholder' => 'dd/mm/yyyy']) }}
            @if($errors->has('date_of_birth')) <span class="help-block"> {{ $errors->first('date_of_birth') }} </span> @endif
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Membership</label>
        <div class="col-md-9">
            <div class="radio-list">
                <label>
                    <input type="radio" name="optionsRadios2" value="option1" /> Free </label>
                <label>
                    <input type="radio" name="optionsRadios2" value="option2" checked/> Professional </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Street</label>
        <div class="col-md-9">
            <input type="text" class="form-control"> </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">City</label>
        <div class="col-md-9">
            <input type="text" class="form-control"> </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">State</label>
        <div class="col-md-9">
            <input type="text" class="form-control"> </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-3">Post Code</label>
        <div class="col-md-9">
            <input type="text" class="form-control"> </div>
    </div>
    <div class="form-group last">
        <label class="control-label col-md-3">Country</label>
        <div class="col-md-9">
            <select class="form-control"> </select>
        </div>
    </div>
    <div class="form-group last">
        <label class="control-label col-md-3">Status <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::select('active', [1 => 'Active', 0 => 'Inactive'], null, ['id' => 'active', 'class' => 'form-control']) }}
        </div>
    </div>
    <div class="form-group {{ $errors->has('sort') ? 'has-error' : '' }}">
        <label class="control-label col-md-3">Sort <span class="required" aria-required="true">*</span></label>
        <div class="col-md-9">
            {{ Form::number('sort', 0, ['id' => 'sort', 'class' => 'form-control', 'min' => '0']) }}
            <span class="help-block"> Please enter sort value, if you would like to set order priority. </span>
            @if($errors->has('sort')) <span class="help-block"> {{ $errors->first('sort') }} </span> @endif
        </div>
    </div>
</div>
<div class="form-actions">
    <div class="row">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn green"><i class="fa fa-check"></i> Save</button>
            {{ link_to_route('role-manage', 'Cancel', [], ['class' => 'btn default']) }}
        </div>
    </div>
</div>
