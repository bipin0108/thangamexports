@component('mail::message')
# Welcome to join with us


{{ $user->first_name }} {{ $user->last_name }}, thank you.


Your Email :  {{ $user->email }}
Password : {{ $user->password }}
 

Thanks,<br>
{{ config('app.name') }}
@endcomponent
