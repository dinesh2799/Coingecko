<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

use App\Models\Coingecko\Coin;
use App\Models\Coingecko\Platform;

use App\Helpers\CoinHelper;
class FetchCoingeckoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:fetch';

    protected $coinHelper;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches data from the CoinGecko API and stores it in the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CoinHelper $coinHelper)
    {
        parent::__construct();
        $this->coinHelper = $coinHelper;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->newLine();
        $this->info('Connecting to Coingecko API ....');
        $response = Http::get(env('COINGECKO_URL'));

        if ($response->successful()) {
            $this->newLine();
            $this->info('Fetching Data from Coingecko API ....');

            $coinApiData = json_decode($response->body(), true);

            $coins = $this->coinHelper->getCoinsData($coinApiData);
            $platforms = $this->coinHelper->getPlatformsData($coinApiData);

            //////////////////////////////////////////////////////////////////////////////////////
            // => Using upsert method instead of insert method because of two reasons
            // 1. If the command is run twice there should not be any integrity error or
            //    preventing the insertion of duplicate entries.
            // 2. If there is another source where data is inserting into the database
            //    like from crud then to maintain previous crud data when api data is added.
            // => In the below code after update or inserting the data into coins table, I am
            // deleting its relation data of platforms table just because if any new data is
            // present in the api response we need to insert it also. Not truncating the previous
            // platforms table because it may contain data from other source such as crud.
            ////////////////////////////////////////////////////////////////////////////////////////

            Coin::upsert($coins,'id');
            Platform::whereIn('coin_id', array_column($coins, 'id'))->delete();
            Platform::insert($platforms);

            /////////////////////////////////////////////////////////////////////////////////////////
            /// If the only source of inserting data into the database is through API then
            /// we can replace the above 3 lines with the below 6 lines
            //            Schema::disableForeignKeyConstraints();
            //            Coin::truncate();
            //            Platform::truncate();
            //            Schema::enableForeignKeyConstraints();
            //            Coin::insert($coins);
            //            Platform::insert($platforms);
            /// These lines will truncate the previous data and insert new data from api response
            /// when we run the command. Truncating the data is because of integrity error and new
            /// occurance of data from api response.
            /// /////////////////////////////////////////////////////////////////////////////////////

            $this->newLine();
            $this->info('Stored in the database successfully.');

        }else {
            $this->error('Failed to fetch data from the API.');
        }
    }
}
