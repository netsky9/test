<?php

namespace Module\CurrencyConverterModule\Command;

use Module\CurrencyConverterModule\Service\CurrencyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateCurrencyCommand extends Command
{
    private CurrencyService $currencyService;
    protected static $defaultName = 'app:update-currency';
    protected static $defaultDescription = 'Update currency';

    public function __construct(
        CurrencyService $currencyService,
        ?string $name = null
    ) {
        $this->currencyService = $currencyService;

        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($this->currencyService->updateCurrency()) {
            $io->success('Currency was updated successfully');

            return Command::SUCCESS;
        }

        $io->error('Something went wrong, check logs');

        return Command::FAILURE;
    }
}
