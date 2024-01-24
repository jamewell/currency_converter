<?php

namespace App\Command;

use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use App\Service\ExchangeRateClient;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand('app:exchange-rate:fetch', 'Fetch Exchange rates and save to database')]
class FetchExchangeRatesCommand extends Command
{
    public function __construct(
        private readonly ExchangeRateClient $exchangeRateClient,
        private readonly ExchangeRateRepository $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run')
        ;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('dry-run')) {
            $io->note('Dry mode Enabled');
        } else {
            $rates = $this->exchangeRateClient->fetchExchangeRates();

            $this->repository->truncate();

            foreach ($rates as $rate) {
                $exchangeRate = new ExchangeRate();

                $exchangeRate
                    ->setCode($rate['code'])
                    ->setAlphaCode($rate['alphaCode'])
                    ->setNumericCode($rate['numericCode'])
                    ->setName($rate['name'])
                    ->setRate($rate['rate'])
                    ->setDate(new DateTimeImmutable($rate['date']))
                    ->setInverseRate($rate['inverseRate'])
                ;

                $this->repository->store($exchangeRate);
            }
        }

        return Command::SUCCESS;
    }
}