<?php

namespace App\Service;

use http\Exception\RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateClient
{
    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function fetchExchangeRates(): array
    {
        $response = $this->client->request(
            'GET',
            'http://www.floatrates.com/daily/usd.json'
        );

        $statusCode = $response->getStatusCode();
        if ($statusCode !== Response::HTTP_OK) {
            throw new RuntimeException("Rates could not be fetched", $statusCode);
        }

        return $response->toArray();
    }
}