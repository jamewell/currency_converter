<?php

namespace App\Controller;

use App\Exception\MissingParameterException;
use App\Repository\ExchangeRateRepository;
use App\Service\ConvertCurrencyService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeRateController extends AbstractController
{
    public function __construct(
        private readonly ExchangeRateRepository $exchangeRateRepository,
        private readonly ConvertCurrencyService $convertCurrencyService,
    )
    {
    }

    /**
     * @throws MissingParameterException
     * @throws Exception
     */
    #[Route('/exchange-rate', name: 'app_exchange_rate', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $exchangeRates = $this->exchangeRateRepository->findAll();
        $convertedRates = [];

        if ($request->getContent()) {
            $requestedRate = $this->getRequiredParameterFromRequest('exchange_rate', $request);
            $requestedAmount = $this->getRequiredParameterFromRequest('amount', $request);

            if  (! is_numeric($requestedAmount)) throw new Exception('Amount needs to be numerical', Response::HTTP_BAD_REQUEST);

            $baseRate = $this->exchangeRateRepository->findOneBy(['code' => $requestedRate]);

            foreach ($exchangeRates as $rate) {
                $newRate = $this->convertCurrencyService
                    ->convert(
                        $rate,
                        $baseRate->getRate(),
                        $requestedAmount,
                    );
                $convertedRates[] = $newRate;
            }
        }

//        TODO: fix rendering converted_rates
        return $this->render('exchange_rate/index.html.twig', [
            'converted_rates' => $convertedRates,
            'exchange_rates' => $exchangeRates,
        ]);
    }

    /**
     * @throws MissingParameterException
     */
    private function getRequiredParameterFromRequest(
        string $parameterName,
        Request $request,
    ): string|int|bool|float {
        $requiredParameter = $request->request->get($parameterName);
        if (!$requiredParameter) throw new MissingParameterException("$parameterName is required");
        return $requiredParameter;
    }
}
