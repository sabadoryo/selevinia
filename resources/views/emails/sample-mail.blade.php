@component('mail::message')
# {{$title}}

{{$body}}

@component('mail::button', ['url' => $postUrl])
Перейти к чтению
@endcomponent

С уважением,<br>
главный редактор
@endcomponent
