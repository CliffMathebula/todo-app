@component('mail::message')
# Hey {{ $name }}

{{ $message }}

@component('mail::button', ['url' => config('app.url')])
See All Tasks
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
