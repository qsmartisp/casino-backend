<?php

namespace App\Console\Commands\Estchange;

use App\Models\Estchange\EstchangeRate;
use App\Services\Estchange\Tunnel\Gateway\EstchangeService;
use Illuminate\Console\Command;

class RatesUpdate extends Command
{
    protected $signature = 'estchange:rates:update';

    protected $description = 'Update rates from Estchanges';

    private static EstchangeService $estchange;

    public function __construct(
        EstchangeService $estchange,
    ) {
        parent::__construct();

        static::$estchange = $estchange;
    }

    /**
     * @throws \JsonException
     */
    public function handle(): int
    {
        $estchangeRates = EstchangeRate::all();

        $bar = $this->output->createProgressBar(count($estchangeRates));

        $bar->start();

        foreach ($estchangeRates as $estchangeRate) {
            $rate = static::$estchange->getCurrencyRate($estchangeRate->coin, $estchangeRate->currency);

            if($rate->getAsk()) {
                $estchangeRate->rate = $rate->getAsk();
                $estchangeRate->save();
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info(PHP_EOL . 'Estchange rates update completed.');

        return 0;
    }

}
