@component('mail::message')
@lang('notifications.hello_user', ['username' => $account->name])
<br/><br/>
**@lang('notifications.failed_login.resume')**<br/>
@lang('notifications.failed_login.connection_details'):

<x-mail::panel>
@lang('commons.time'): **{{ $time->toCookieString() }}**<br/>
@lang('commons.ip_address'): **{{ $ipAddress }}**<br/>
@lang('commons.device'): **@lang('admin.browser_on_platform', ['browser' => $browser, 'platform' => $platform])**<br/>
</x-mail::panel>

@lang('notifications.failed_login.recommandations')<br/>

@lang('notifications.regards'),<br/>
{{ config('app.name') }}
@endcomponent
