<?php

namespace LearnKit\FilamentExpiredInvoice;

use Filament\Facades\Filament;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class FilamentExpiredInvoiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-expired-invoice.php', 'filament-expired-invoice');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-expired-invoice');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'filament-expired-invoice');
    }

    public function boot(): void
    {
        Livewire::component('filament-expired-invoice-notice', \LearnKit\FilamentExpiredInvoice\Livewire\Notice::class);

        Filament::serving(function () {
            Filament::registerRenderHook(PanelsRenderHook::BODY_END, fn() => Blade::render("@livewire('filament-expired-invoice-notice')", deleteCachedView: true));
        });
    }
}
