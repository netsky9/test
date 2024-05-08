<?php

namespace Module\CurrencyConverterModule\Service;

use Module\CurrencyConverterModule\Repository\CurrencyRepository;

class ConvertService
{
    private CurrencyRepository $currencyRepository;

    public function __construct(
        CurrencyRepository $currencyRepository
    ) {
        $this->currencyRepository = $currencyRepository;
    }

    public function convert($value, $fromCurrency, $toCurrency)
    {
        $toCurrencyEntity = $this->currencyRepository->findOneBy(['code' => $toCurrency]);

        if (!$toCurrencyEntity) {
            throw new \Exception("$toCurrency currency not found");
        }

        $fromCurrencyEntity = $this->currencyRepository->findOneBy(['code' => $fromCurrency]);

        if (!$fromCurrencyEntity) {
            throw new \Exception("$fromCurrency currency not found");
        }

        if ($fromCurrency == CurrencyService::DEFAULT_CURRENCY) {
            return $value * $toCurrencyEntity->getValue();
        }

        return $value * (1 / $fromCurrencyEntity->getValue()) * $toCurrencyEntity->getValue();
    }

}
