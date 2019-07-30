@component('mail::message')
# Password for New Account

Your password is {{ $password }}. 
<br>
Please change it when you log in.

@component('mail::button', ['url' => 'fuelstapp.dev'])
Log in
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
