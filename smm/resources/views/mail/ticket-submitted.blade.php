@component('mail::layout')
@slot('header')
    {{--Empty header--}}
@endslot
**@Lang('mail.user')**: {{ $ticket->user->email }}

**@Lang('mail.subject')**: {{ $ticket->subject }}

**@Lang('mail.description')**<br>
{{ $ticket->description}}
@slot('footer')
    {{--Empty footer--}}
@endslot
@endcomponent
