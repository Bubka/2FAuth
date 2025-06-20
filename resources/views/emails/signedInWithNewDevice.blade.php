@component('mail::message')
@lang('message.notifications.hello_user', ['username' => $account->name])
<br/><br/>
**@lang('message.notifications.new_device.resume')**<br/>
@lang('message.notifications.new_device.connection_details'):

<x-mail::panel>
@lang('message.time'): **{{ $time->toCookieString() }}**<br/>
@lang('message.ip_address'): **{{ $ipAddress }}**<br/>
@lang('message.device'): **@lang('message.admin.browser_on_platform', ['browser' => $browser, 'platform' => $platform])**<br/>
</x-mail::panel>

@lang('message.notifications.new_device.recommandations')<br/>

@lang('message.notifications.regards'),<br/>
{{ config('app.name') }}
@endcomponent
