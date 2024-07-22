<?php

namespace LearnKit\FilamentExpiredInvoice\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Notice extends Component implements HasActions, HasForms
{
    use InteractsWithActions, InteractsWithForms;

    public function showModal()
    {
        $snooze = Session::get(static::class);

        if ($snooze && now()->diffInMinutes($snooze) < 30) {
            return;
        }

        $expireDate = Carbon::parse(config('filament-expired-invoice.invoice_expire_date'));

        if (now()->lt($expireDate)) {
            return;
        }

        $this->mountAction('notice');
    }

    public function noticeAction(): Action
    {
        $invoiceNumber = config('filament-expired-invoice.invoice_number');

        return Action::make('notice')
            ->modalHeading($invoiceNumber ? __('filament-expired-invoice::messages.invoice_number_expired', ['number' => $invoiceNumber]) : __('filament-expired-invoice::messages.invoice_expired'))
            ->modalIcon('heroicon-o-exclamation-triangle')
            ->modalContent(view('filament-expired-invoice::content'))
            ->modalWidth('xl')
            ->color('danger')
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            ->modalCloseButton(false)
            ->modalFooterActions([
                Action::make('snooze')
                    ->label('Snooze')
                    ->icon('heroicon-o-clock')
                    ->color('danger')
                    ->action(function () {
                        Session::put(static::class, now());

                        $this->closeActionModal();
                    }),
            ]);
    }

    public function render()
    {
        return view('filament-expired-invoice::livewire.notice');
    }
}