<strong>Hi, Administrator.</strong>

<p>Contact us inquiry</p>

<hr>

<table>
    <tr>
        <th>From:</th>
        <td>{!! $request->name !!}</td>
    </tr>
    @if($request->contact_number)
        <tr>
            <th>Contact Number:</th>
            <td>{!! $request->contact_number !!}</td>
        </tr>
    @endif
    <tr>
        <th>Email:</th>
        <td>{!! $request->email !!}</td>
    </tr>
    <tr>
        <th>Message:</th>
        <td>{!! $request->message !!}</td>
    </tr>
</table>

<hr>

<p>Thanks,</p>
<p>{!! $settings['site_name'] !!} Team</p>
