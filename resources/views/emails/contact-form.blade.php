@component('mail::message')

<span style="font-weight: 600">From:</span> {{ $from }} <br> <br>

<span style="font-weight: 600">Message:</span> {{ $message_content }} <br> <br>

<span style="font-weight: 600">Additional Details:</span> {!! $additional_details !!}

@slot('footer')
@component('mail::footer')
    © {{ date('Y') }} {{ ucwords(config('app.name')) }}. @lang('All rights reserved.')
@endcomponent
@endslot

@endcomponent
