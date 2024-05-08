<?php

namespace Module\CurrencyConverterModule\Command;

use Module\CurrencyConverterModule\Service\ConvertService;
use Module\CurrencyConverterModule\Service\CurrencyService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConvertCurrencyCommand extends Command
{
    private ConvertService $converter;
    protected static $defaultName = 'app:convert-currency';
    protected static $defaultDescription = 'convert currency';

    public function __construct(
        ConvertService $convertService,
        ?string $name = null
    ) {
        $this->converter = $convertService;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('value', InputArgument::OPTIONAL, 'Value')
            ->addArgument('from', InputArgument::OPTIONAL, 'From currency')
            ->addArgument('to', InputArgument::OPTIONAL, 'To currency')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $value = $input->getArgument('value');
        $fromCurrency = $input->getArgument('from');
        $toCurrency = $input->getArgument('to');

        $result = $this->converter->convert($value, $fromCurrency, $toCurrency);

        $io->success("$value $fromCurrency = $result $toCurrency");

        return Command::SUCCESS;
    }
}
