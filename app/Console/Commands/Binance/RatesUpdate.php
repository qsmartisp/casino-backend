<?php

namespace App\Console\Commands\Binance;

use App\Models\Binance\BinanceRate;
use App\Services\Binance\BinanceService;
use Illuminate\Console\Command;

class RatesUpdate extends Command
{
    protected $signature = 'binance:rates:update';

    protected $description = 'Update rates from Binance';

    private static BinanceService $binance;

    public function __construct(
        BinanceService $binance,
    ) {
        parent::__construct();

        static::$binance = $binance;
    }

    /**
     * @throws \JsonException
     */
    public function handle(): int
    {
        $binanceRates = BinanceRate::all();

        $bar = $this->output->createProgressBar(count($binanceRates));

        $bar->start();

        foreach ($binanceRates as $binanceRate) {
            $rate = static::$binance->getCurrencyRate($binanceRate->symbol);

            if($rate->getRate()) {
                $binanceRate->rate = $rate->getRate();
                $binanceRate->save();
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info(PHP_EOL . 'Binance rates update completed.');

        return 0;
    }

}
