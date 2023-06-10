<x-mail::message>
# Hello {{ !empty($coinSync->admin) ? $coinSync->admin->name : 'admin'; }}

The Coin synchronization that you requested has been completed.

Synchronization started at **{{ $coinSync->started_at->format('F\, Y') }}** took **{{ $coinSync->completed_in_seconds }}** seconds to complete.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
