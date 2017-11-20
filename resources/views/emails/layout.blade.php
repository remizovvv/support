@component('mail::message')

@if ($greeting)
@if ($greetingText)
# {{ $greetingText }}
@elseif ($username)
# @lang('support::email.greeting', ['name' => $username])
@endif
@endif

@foreach($content as $row)
@if ($row['type'] == 'line')
{{ $row['text'] }}
@elseif ($row['type'] == 'action')
@component('mail::button', ['url' => $row['url']])
{{ $row['text'] }}
@endcomponent
@endif
@endforeach

@lang('support::email.regards'),<br>
[@lang('support::app.name')]({{ config('app.url') }})

@foreach($actions as $row)
@component('mail::subcopy')
@lang('support::email.button_trouble', ['button' => $row['text']]): [{{ $row['url'] }}]({{ $row['url'] }})
@endcomponent
@endforeach

@endcomponent