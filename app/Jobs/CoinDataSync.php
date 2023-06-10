<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Traits\CommonCode;
use App\Models\Coin;
use App\Models\CoinSync;
use App\Mail\SendCoinSaveMail;
use Carbon\Carbon;
use Throwable;

class CoinDataSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CommonCode;
	
	public CoinSync $coinSync;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CoinSync $coinSync)
    {
        $this->coinSync = $coinSync;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');
		$data = $this->fetchCoingeckoData();
		//$data = $this->fetchCoingeckoDataStatic();
		if(!empty($data)) {
			Coin::truncate();
			foreach($data as $item) {
				DB::beginTransaction();
				try {
					$coin = Coin::create([
						'coin_id'=>	$item['id'],
						'symbol'=> $item['symbol'],
						'name'=> $item['name'],
						'platforms' => !empty($item['platforms']) ? $item['platforms'] : null
					]);
				} catch (\Exception $exception) {
					DB::rollBack();

					throw $exception;
				}
				DB::commit();
			}
		}
		$this->coinSync->update(['completed_at' => now(),'status'=>'completed']);
		\Mail::to('mohammedaarif359@gmail.com')->queue(new SendCoinSaveMail($this->coinSync));
    }
	/**
     * Handle a job failure.
     *
     * @param Throwable $exception
     * @return void
     */
    public function failed(Throwable $exception)
    {
        Log::channel('sync')->error($exception);

        $this->coinSync->update(
            [
                'errors' => [
                    'message' => $exception->getMessage(),
                    'code' => $exception->getCode(),
                ],
				'status'=>'error'
            ]
        );
    }
}
