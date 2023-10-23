<?php
declare(strict_types=1);

namespace App;

class Application
{
    private ExchangeAPI $api;

    public function __construct()
    {
        $this->api = new ExchangeAPI();
    }

    public function run(): void
    {
        while (true) {
            echo "Enter base amount and currency (<amount> <currency>): ";
            $input = explode(' ', readline());
            $amount = $input[0];

            if (empty($amount)) {
                echo 'No base amount was entered' . PHP_EOL;
                continue;
            }

            echo 'Enter target currency: ';
            $currency = strtoupper(readline());

            if (empty($currency)) {
                echo 'No target currency was entered' . PHP_EOL;
                continue;
            }

            $baseCurrency = strtoupper($input[1] ?? '');

            if (empty($baseCurrency)) {
                echo 'No base currency was entered' . PHP_EOL;
                continue;
            }

            $result = $this->api->fetchExchangeData($baseCurrency, $currency);
            if ($result === null) {
                echo 'Could not find exchange rate for ' . $currency . PHP_EOL;
            } else {
                $convertedAmount = round($result * $amount, 2);
                echo "Converted amount: $currency $convertedAmount" . PHP_EOL;
            }
        }
    }
}
