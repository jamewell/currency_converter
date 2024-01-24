<?php

namespace App\Service;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ExchangeRateClient
{
    public function __construct(
        private HttpClientInterface $client,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @return array<int,array<string,mixed>>
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