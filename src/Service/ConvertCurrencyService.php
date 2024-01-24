<?php

namespace App\Service;

use App\Entity\ExchangeRate;

class ConvertCurrencyService
{
    /**
     * @return array<string,mixed>
     */
    public function convert(
        ExchangeRate $rate,
        float $calculatingRate,
        float $amount
    ): array {
        $convertedAmount = $amount / $calculatingRate * $rate->getRate();
        $convertedAmount = number_format($convertedAmount, 2);

        return [
            'name' => '(' . $rate->getCode() . ') ' . $rate->getName(),
            'amount' => $convertedAmount,
        ];
    }
}