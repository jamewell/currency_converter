<?php

namespace App\Entity;

use App\Repository\ExchangeRateRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExchangeRateRepository::class)]
class ExchangeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 3)]
    private string $code;

    #[ORM\Column(length: 3)]
    private string $alphaCode;

    #[ORM\Column(length: 3)]
    private string $numericCode;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column]
    private float $rate;

    #[ORM\Column(nullable: true)]
    private DateTimeImmutable $date;

    #[ORM\Column]
    private float $inverseRate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getAlphaCode(): string
    {
        return $this->alphaCode;
    }

    public function setAlphaCode(string $alphaCode): static
    {
        $this->alphaCode = $alphaCode;

        return $this;
    }

    public function getNumericCode(): string
    {
        return $this->numericCode;
    }

    public function setNumericCode(string $numericCode): static
    {
        $this->numericCode = $numericCode;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getInverseRate(): float
    {
        return $this->inverseRate;
    }

    public function setInverseRate(float $inverseRate): static
    {
        $this->inverseRate = $inverseRate;

        return $this;
    }
}
