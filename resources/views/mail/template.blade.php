{{$emailParams->detail}}
<br>
<br>
@if(isset($emailParams->is_cc) && $emailParams->is_cc)
reply to: {{$emailParams->senderEmail}}
@endif