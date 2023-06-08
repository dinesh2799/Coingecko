<?php

namespace App\Helpers;

class CoinHelper
{
    public function getCoinsData($coinApiData)
    {
        $coins = array_map(function ($coinData) {
            return [
                'id' => $coinData['id'],
                'symbol' => $coinData['symbol'],
                'name' => $coinData['name'],
            ];
        }, $coinApiData);

        return $coins;
    }

    public function getPlatformsData($coinApiData)
    {
        $platforms = array_map(function ($coinData) {
            $coinPlatforms = $coinData['platforms'] ?? [];

            $platforms = array_map(function ($key, $value) use ($coinData) {
                return [
                    'name' => $key,
                    'value' => $value,
                    'coin_id' => $coinData['id'],
                ];
            }, array_keys($coinPlatforms), $coinPlatforms);

            return $platforms;
        }, $coinApiData);

        $platforms = array_merge(...$platforms);

        return $platforms;
    }
}
