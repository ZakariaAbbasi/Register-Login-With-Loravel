@component('mail::message')
# Verify your email

Dear {{ $name }}

@component('mail::button', ['url' => $link])
Verify your email
@endcomponent

@endcomponent


