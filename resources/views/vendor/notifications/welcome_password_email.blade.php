@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

@isset($panel)
@component('mail::panel')
{!! strip_tags($panel) !!}
@endcomponent
@endisset

{{-- Intro Lines --}}
@foreach ($introLines as $line)
@if(is_object($line) && $line instanceof \App\Notifications\Panel)
@component('mail::panel')
{!! $line->text !!}
@endcomponent
@else
{{ $line }}
@endif

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
@if(is_object($line) && $line instanceof \App\Notifications\Panel)
@component('mail::panel')
{!! $line->text !!}
@endcomponent
@else
{{ $line }}
@endif

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang(
    "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser: [:actionURL](:actionURL)',
    [
        'actionText' => $actionText,
        'actionURL' => $actionUrl,
    ]
)
@endcomponent
@endisset
@endcomponent
