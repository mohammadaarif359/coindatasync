<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Models\CoinSync;
use App\Jobs\CoinDataSync;
use App\Jobs\SendCoinEmailJob;

class SaveCoingeckoDataToCoins extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coins:save_coingecko_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Save coingecko coins api data to our coins db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $alreadySyncInProgress = CoinSync::whereNull('completed_at')->whereNull('errors')->orderBy('started_at','Desc')->first();
		if (!empty($alreadySyncInProgress)) {
			$msg = 'A Synchronisation  for dealer point started at ' . $alreadySyncInProgress->started_at->format('d F\, Y') .
					' is already in progress';
			return $this->info($msg);
		}
		$apiSync = CoinSync::create(
                [
                    "requested_by" => 0,
                    "status"=>"in_progress",
					"started_at" => now()
                ]
            );
		Bus::chain(
			[
				new CoinDataSync($apiSync),
			]
		)->catch(
			fn(\Throwable $e) => Log::channel('sync')->error($e)
		)->dispatch();
		return $this->info('Coin sync run in background process by queue work command Once this job process is complete you recieved an email');
		//return Command::SUCCESS;
    }
}
