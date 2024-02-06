<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;
use \App\Services\OpenExchangeRates;
use Log;

class UpdateExchangeRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rates:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Exchange Rates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $ratesByCurr = ExchangeRate::where('auto_update', true)->get()->groupBy('from');

            foreach ($ratesByCurr as $from => $rates) {
                $service = new OpenExchangeRates();
                try {
                    $data = $service->latest($from);
                    $base = $data['base'];
                } catch (\Throwable $th) {
                    continue;
                }
                if (!$base || $base != strtoupper($from)) {
                    continue;
                }

                $exchRates = $data['rates'];

                foreach ($rates as $rate) {
                    $to = strtoupper($rate->to);
                    $cost = $exchRates[$to] ?? null;
                    if (!$cost) {
                        continue;
                    }
                    $rate->update([
                        'cost' => $cost
                    ]);
                }

            }
        } catch (\Throwable $th) {
            Log::channel('commands')->error("[$this->signature] " . exceptionAsString($th));
        }

        return 0;
    }
}
