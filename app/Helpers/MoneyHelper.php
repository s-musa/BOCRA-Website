<?php

namespace App\Helpers;

use App\Models\Organisation;
use App\Models\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Currency as MoneyCurrency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class MoneyHelper
{
    public static function parseToMoney($amount, $currency = null): Money
    {
        $currency = self::getCurrency($currency);
        
        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());
        
        // FIX: wrap iso in Money\Currency
        return $moneyParser->parse((string) $amount, new \Money\Currency($currency->iso));
    }

    /**
     * Get currency object.
     *
     * @param  Currency|string|null  $currency
     */
    public static function getCurrency($currency): ?Currency
    {
        if (is_numeric($currency)) {
            return Currency::find($currency);
        } elseif (is_string($currency)) {
            return Currency::firstWhere('iso', '=', $currency);
        }

        if (! $currency) {
            return Currency::firstWhere('iso', '=', 'KES');
        }
    }

    /**
     * Format a monetary value as real value. Real value is the amount to be entered
     * in an input by a user, using ordinary format
     *
     * @param  int|null  $amount Amount value in store-able format (ex: 100 for 1,00€).
     * @param  Currency|int|null  $currency
     * @return string  Real value of amount in exchange format (eg: 1.24).
     */
    public static function displayFormat(?int $amount, $currency = null): string
    {
        $currency = self::getCurrency($currency);

        $moneyCurrency = new MoneyCurrency($currency->iso);

        $money = new Money($amount ?? 0, $moneyCurrency);

        $moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());

        return $moneyFormatter->format($money);

    }

    public static function intDecimalFormat(?int $amount, $currency = null): string
    {
        $currency = self::getCurrency($currency);

        $money = new Money($amount ?? 0, new MoneyCurrency($currency->iso));

        $numberFormatter = new \NumberFormatter('en_Ke', \NumberFormatter::DECIMAL);

        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, new ISOCurrencies());

        return $moneyFormatter->format($money);
    }
}
