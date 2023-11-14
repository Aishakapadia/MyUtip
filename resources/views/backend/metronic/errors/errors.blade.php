@if( count( $errors ) > 0 )
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>

        <ul class="" id="form-errors" style=" margin-left: 10px;">
            {{--{{ $errors->first('slug', '<li>:message</li>') }}--}}
            {{--{{ $errors->first('payerName', '<li>:message</li>') }}--}}
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif