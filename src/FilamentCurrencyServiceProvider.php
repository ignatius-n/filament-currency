<?php

namespace Ariaieboy\FilamentCurrency;

use Akaunting\Money;
use Closure;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCurrencyServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-currency';

    public static string $viewNamespace = 'filament-currency';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name);
    }

    public function bootingPackage(): void
    {
        TextColumn::macro('currency', function (string | Closure $currency = null, bool $shouldConvert = false): static {
            $this->formatStateUsing(static function (Column $column, $state) use ($currency, $shouldConvert): ?string {
                if (blank($state)) {
                    return null;
                }

                if (blank($currency)) {
                    $currency = env('DEFAULT_CURRENCY', 'USD');
                }

                return (new Money\Money(
                    $state,
                    (new Money\Currency(strtoupper($column->evaluate($currency)))),
                    $shouldConvert,
                ))->format();
            });

            return $this;
        });
    }
}
