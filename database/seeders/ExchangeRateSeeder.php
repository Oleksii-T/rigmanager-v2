<?php

namespace Database\Seeders;

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

class ExchangeRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $froms = [];
        foreach (currencies() as $from => $symbolF) {
            foreach (currencies() as $to => $symbolT) {
                if ($from == $to || in_array($to, $froms)) {
                    continue;
                }

                $rate = ExchangeRate::where('from', $from)->where('to', $to)->first();
                if ($rate) {
                    continue;
                }

                ExchangeRate::create([
                    'from' => $from,
                    'to' => $to,
                    'cost' => 1,
                    'auto_update' => true,
                ]);
            }
            $froms[] = $from;
        }
    }
}
