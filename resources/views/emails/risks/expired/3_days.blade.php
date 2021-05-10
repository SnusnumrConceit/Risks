@component('mail::message')
Здравствуйте, {{ $user->short_name }}!

Риск <strong>{{ $risk->name }}</strong> подразделения <strong>{{ $risk->division->name }}</strong> истекает <strong>через 3 дня!</strong>

@component('mail::button', ['url' => route('risks.show', ['risk' => $risk->uuid])])
Перейти к риску
@endcomponent

С уважением,<br>
{{ __('ui.app_name') }}
@endcomponent
