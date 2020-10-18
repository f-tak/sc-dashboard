<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    // json戻り値
    public $return_json = null;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $response = Http::get(config('const.weather_api_url'), [
            'appid' => config('const.weather_api_key'),
            'units' => config('const.weather_api_units'),
            'lat' => config('const.weather_api_lat'),
            'lon' => config('const.weather_api_lon'),
            'lang' => config('const.weather_api_language'),
            'exclude' => 'minutely'
        ]);
        $response_json  = $response->body();
        // Jsonデコード
        $weather = json_decode($response_json, true );

        // 時間別
        foreach ($weather['hourly'] as $index => $hourly) {
            if ($index >= config('const.weather_hourly_interval') * config('const.weather_hourly_count')) {
                break;
            }
            if ($index % config('const.weather_hourly_interval') === 0) {
                $this->return_json['hourly'][] = [
                    'dt' => $hourly['dt'],
                    'temp' => $hourly['temp'],
                    'weather' => $hourly['weather'],
                ];
            }
        }

        // 日別
        foreach ($weather['daily'] as $index =>  $daily) {
            if ($index >= config('const.weather_daily_count')) {
                break;
            }
            $this->return_json['daily'][] = [
                'dt' => $daily['dt'],
                'temp' => $daily['temp'],
                'weather' => $daily['weather'],
            ];
        }

        return response()->json($this->return_json);
    }
}