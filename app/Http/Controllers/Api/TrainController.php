<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TrainController extends Controller
{
    // json戻り値
    public $return_json = null;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $response = Http::get(config('const.train_api_url'));
        // Jsonデコード
        $response_json  = $response->body();
        $delay_railway_lines = json_decode($response_json, true);

        // 監視対象の路線
        $target_railway_lines = config('const.railway-lines');

        foreach ($target_railway_lines as $key => $target_railway_line) {
            // 路線名
            $line = $target_railway_line['name'];
            // 鉄道会社名
            $company = $target_railway_line['company'];
            // 遅延の有無
            $is_delay = false;

            foreach ($delay_railway_lines as $delay_railway_line) {
                if ($delay_railway_line['name'] === $line && $delay_railway_line['company'] === $company) {
                    // 遅延一覧に存在する場合は、フラグを立てる
                    $is_delay= true;
                    break;
                }
            }

            $this->return_json[] = [
                'line' => $line,
                'company' => $company,
                'is_delay' => $is_delay,
            ];
        }

        return response()->json($this->return_json);
    }
}