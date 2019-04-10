@component('mail::layout')
@slot('header')
   {{--Empty header--}}
@endslot
**@Lang('mail.ticket_id'):** {{$ticketMessage->ticket->id}}<br>
**@Lang('mail.subject'):** {{$ticketMessage->ticket->subject}}<br><br>
**@Lang('mail.message')**<br/>
{{$ticketMessage->content}}
@slot('footer')
    {{--Empty footer--}}
@endslot
@endcomponent