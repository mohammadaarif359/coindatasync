<?php namespace App\Traits;

use Log;
use Illuminate\Support\Str;

trait CommonCode
{
	public function fetchCoingeckoData() {
		$service_url = env('COIN_SERVICE');
		$ch = curl_init();
		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
		);
		curl_setopt($ch, CURLOPT_URL, $service_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$body = '{}';

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
		curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Timeout in seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);

		$data = curl_exec($ch);
		
		$data = json_decode($data,true);
		return $data;
	}
	public function fetchCoingeckoDataStatic() {
		$data = [
			array(
			"id"=>"0chain",
			"symbol"=>"zcn",
			"name"=>"Zus",
			"platforms" => [
			"ethereum"=>"0xb9ef770b6a5e12e45983c5d80545258aa38f3b78",
			"polygon-pos"=>"0x8bb30e0e67b11b978a5040144c410e1ccddcba30"
			]
			),
			array(
			"id"=>"0vix-protocol",
			"symbol"=>"vix",
			"name"=>"0VIX Protocol",
			"platforms"=>[]
			),
			array(
			"id"=>"0x",
			"symbol"=>"zrx",
			"name"=>"0x Protocol",
			"platforms"=>[
			"ethereum"=>"0xe41d2489571d322189246dafa5ebde1f4699f498",
			"energi"=>"0x591c19dc0821704bedaa5bbc6a66fee277d9437e",
			"harmony-shard-0"=>"0x8143e2a1085939caa9cef6665c2ff32f7bc08435",
			"avalanche"=>"0x596fa47043f99a4e0f122243b841e55375cde0d2"
			]
			),array(
			"id"=>"0vix-protocol",
			"symbol"=>"vix",
			"name"=>"0VIX Protocol",
			"platforms"=>[]
			)
		];
		return $data;
		
	}
}
?>