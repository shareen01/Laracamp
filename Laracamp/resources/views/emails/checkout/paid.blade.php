@component('mail::message')
# Your transaction has been confirmed

Hi, {{ $checkout->User->name }}
<br>
Your transaction has been confirmed, now you can enjoy the benefits of <b>{{$checkout->Camp->title}}</b> camp.

@component('mail::button', ['url' => route('user.dashboard')])
My dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
