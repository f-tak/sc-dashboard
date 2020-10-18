<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsController extends Controller
{
    // json戻り値
    public $return_json = null;

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function get(Request $request)
    {
        $response = Http::get(config('const.news_api_url'));
        $response_xml  = $response->body();
        // XMLパース
        $xml_object = simplexml_load_string($response_xml);
        $news_json = json_decode(json_encode($xml_object), true );
        $news_list = $news_json['channel']['item'];

        foreach ($news_list as $news) {
            $this->return_json[] = [
                'title' => $news['title'],
                'date' => (new DateTime($news['pubDate']))->format('Y/m/d H:i:s'),
                'description' => $news['description'],
                'link' => $news['link'],
            ];
        }

        return response()->json($this->return_json);
    }
}