@php use Illuminate\Support\Carbon; @endphp
@php
    $expireDate = Carbon::parse(config('filament-expired-invoice.invoice_expire_date'));

    $daysExpired = now()->diffInDays($expireDate);
@endphp
<div>
    <p>{{ trans_choice('filament-expired-invoice::messages.notice', $daysExpired) }}</p>
</div>