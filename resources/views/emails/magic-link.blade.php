@component('mail::message')
# Login With Magic Link

 Hello Dear {{ $name }}
@component('mail::button', ['url' => $link])
login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
