<?php
namespace App\Modules\Payment\Helpers;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
// use Illuminate\Support\Facades\Http;
use App\Modules\Payment\Models\ExchangeRate;

/**
 *
 */
class ExchangeRateHelper
{

    public static function getRates(){

        // TODO  - oh run worker to fetch latest rates everyday

        // https://free.currconv.com/api/v5/convert?q=USD_NGN&compact=ultra&apiKey=63f0b3cf575148b621cf
        // $response = json_decode(Http::get('https://free.currconv.com/api/v5/convert?q=USD_NGN&compact=ultra&apiKey=63f0b3cf575148b621cf'));
        
        // $rate = $response->USD_NGN;
        // // $naira = $rate * $dollar;
        // dd ($rate); 



        $app_id = env('OpenExchange_APP_ID');
        $client = new \GuzzleHttp\Client();
        $payload = $client->request('GET', "https://openexchangerates.org/api/latest.json?app_id={$app_id}", [
            'verify' => false,
            'headers' => [
                'User-Agent' => 'CUSTOM_AGENT_YOU_WANT' // THIS IS WHAT I ADDED TO MAKE IT WORK
            ]
        ]);

        $response = (json_decode($payload->getBody()->getContents()));
        if( !empty($response->rates)  ){
            $rates = [];
            $base = $response->base;

            foreach ($response->rates as $currency => $rate) {
               $rates['currency'] = $currency;
               $rates['rate'] = $rate;
               $rates['base'] = $base;
               ExchangeRate::updateOrCreate ($rates);
            }
                return response()->json(['status' => 'success', 'message' => 'rates updates successfully']);
        } else{
            return 'dfdf';
        }

        // dd($response);

        // dd(json_decode($response->getBody()->getContents(), true));               

    }

    // TODO make it flexible - and reusable
	public static function dollarToNaira($dollar)
    {
        $nairaRate = ExchangeRate::where('currency', 'NGN')->first()->rate;
        $nairaEquivalent = $dollar*$nairaRate;
        return $nairaEquivalent;

    }

    public static function nairaToDollar($naira)
    {
        $nairaRate = ExchangeRate::where('currency', 'NGN')->first()->rate;
        $dollarEquivalent = $naira/$nairaRate;
        return $dollarEquivalent;
    }
}