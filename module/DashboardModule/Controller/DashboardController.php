<?php

namespace Module\DashboardModule\Controller;

use Module\CurrencyConverterModule\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private CurrencyRepository $currencyRepository;

    /**
     * @param CurrencyRepository $currencyRepository
     */
    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @Route("/dashboard/currencies", name="currencies")
     */
    public function currenciesList(Request $request): Response
    {
        $currencies = $this->currencyRepository->findAll();

        return $this->render('dashboard/currencies.html.twig', compact('currencies'));
    }
}
