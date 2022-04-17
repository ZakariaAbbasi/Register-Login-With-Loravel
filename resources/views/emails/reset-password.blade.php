@component('mail::message')
# Reset Password


@component('mail::button', ['url' => $link])
Reset your Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
