<?php

namespace App\Console\Commands;

use App\Helpers\Currency;
use App\Models\CurrencyRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class FetchCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:fetch {days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load currencies from cbr and store in db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CurrencyRate::truncate();

        $now = Carbon::now();
        $days = (int)$this->argument('days');

        if ($days < 1) {
            $days = 1;
        }

        $bar = $this->output->createProgressBar($days + 1);
        $bar->start();

        $currenciesToInsert = [];

        foreach (range(0, $days - 1) as $daysToSubtract) {
            $date = $now->clone()->subDays($daysToSubtract);

            $currencies = Currency::getByDate($date);

            foreach ($currencies as $currency) {
                $currenciesToInsert[] = [
                    'num_code' => (int)$currency->NumCode,
                    'char_code' => (string)$currency->CharCode,
                    'name' => (string)$currency->Name,
                    'nominal' => (int)$currency->Nominal,
                    'value' => (float)str_replace(',', '.', $currency->Value),
                    'date' => $date,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            $bar->advance();
        }

        $currenciesToInsertChunks = collect($currenciesToInsert)->chunk(1000);

        $currenciesToInsertChunks->each(function (Collection $chunk) {
            CurrencyRate::insert($chunk->all());
        });

        $bar->finish();

        $this->info(PHP_EOL);

        return 0;
    }
}
