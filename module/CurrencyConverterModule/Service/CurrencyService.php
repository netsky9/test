<?php

namespace Module\CurrencyConverterModule\Service;

use Doctrine\ORM\EntityManagerInterface;
use Module\CurrencyConverterModule\Entity\Currency;
use Module\CurrencyConverterModule\Repository\CurrencyRepository;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyService
{
    public const DEFAULT_CURRENCY = 'USD';
    public const CURRENCY = [
        'EUR',
        'USD',
        'CAD',
        'BGN',
        'GBP',
        'ILS',
        'INR',
        'KRW',
        'MXN',
        'MYR',
        'RUB'
    ];

    private HttpClientInterface $client;
    private string $token;
    private LoggerInterface $logger;
    private CurrencyRepository $currencyRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        HttpClientInterface $client,
        LoggerInterface $logger,
        CurrencyRepository $currencyRepository,
        EntityManagerInterface $entityManager,
        string $freeCurrencyApiToken
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->currencyRepository = $currencyRepository;
        $this->entityManager = $entityManager;
        $this->token = $freeCurrencyApiToken;
    }

    public function updateCurrency(): bool
    {
        $currencies = $this->getCurrency();

        $this->entityManager->beginTransaction();

        try {
            $this->currencyRepository->deleteAll();

            foreach ($currencies as $currencyCode => $currencyValue) {
                $currency = new Currency();
                $currency->setCode($currencyCode);
                $currency->setValue($currencyValue);

                $this->entityManager->persist($currency);
            }

            $this->entityManager->flush();
            $this->entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $this->logger->error('Database error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $this->entityManager->rollBack();
        }

        return false;
    }

    public function getCurrency(): array
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://api.freecurrencyapi.com/v1/latest?apikey=' . $this->token,
                [
                    'query' => [
                        'currencies' => implode(',', self::CURRENCY),
                    ],
                ]
            );

            return $response->toArray()['data'] ?? [];
        } catch (\Exception $e) {
            $this->logger->error('Error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
        }

        return [];
    }
}
