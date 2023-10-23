<?php
declare(strict_types=1);

namespace App;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeAPI
{
    private const BASE_URL = 'https://api.freecurrencyapi.com/v1/latest';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
        ]);
    }

    /**
     * @throws Exception
     */
    public function fetchExchangeData(string $baseCurrency, string $currency): ?float
    {
        $url = self::BASE_URL . '?' . http_build_query([
                'apikey' => $_ENV['API_KEY'],
                'base_currency' => $baseCurrency,
                'currencies' => $currency,
            ]);

        try {
            $response = $this->client->get($url);

            if ($response->getStatusCode() !== 200) {
                throw new Exception("API Request Failed! Base Currency: $baseCurrency, Currency: $currency");
            }

            $data = json_decode($response->getBody()->getContents());

            if (empty($data) || !property_exists($data, 'data') || !property_exists($data->data, $currency)) {
                return null;
            }

            return (float)$data->data->$currency;
        } catch (GuzzleException $e) {
            throw new Exception('API Request Failed: ' . $e->getMessage());
        }
    }
}
