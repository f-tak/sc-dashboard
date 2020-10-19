<?php

return [
    // ニュース取得APIのURL
    'news_api_url' => 'https://news.yahoo.co.jp/rss/topics/it.xml',

    // 気象情報取得APIのURL
    'weather_api_url' => 'https://api.openweathermap.org/data/2.5/onecall',
    // 気象情報取得APIのAPIキー
    'weather_api_key' => env('OPEN_WHETHER_API_KEY'),
    // 気象情報取得APIの対象緯度経度
    'weather_api_lat' => 34.2365784,
    'weather_api_lon' => 135.1645049,
    // 気象情報取得APIの計測単
    'weather_api_units' => 'metric',
    // 気象情報取得APIの言語指定
    'weather_api_language' => 'ja',
    // 気象情報の時間別表示間隔
    'weather_hourly_interval' => 2,
    // 気象情報の時間別表示件数
    'weather_hourly_count' => 5,
    // 気象情報の日別表示件数
    'weather_daily_count' => 5,

    // 遅延路線取得APIのURL
    'train_api_url' => 'https://tetsudo.rti-giken.jp/free/delay.json',
    // 遅延路線取得APIの表示対象路線
    'railway-lines' => [
        [
            'company' => 'JR西日本',
            'name' => '阪和線',
        ],
        [
            'company' => 'JR東海',
            'name' => '紀勢本線',
        ],
        [
            'company' => 'JR西日本',
            'name' => 'きのくに線',
        ],
        [
            'company' => 'JR西日本',
            'name' => '和歌山線',
        ],
        [
            'company' => '南海電気鉄道',
            'name' => '加太線',
        ],
        [
            'company' => '南海電気鉄道',
            'name' => '特急サザン',
        ],
        [
            'company' => '南海電気鉄道',
            'name' => '南海本線',
        ],
        [
            'company' => '和歌山電鐵',
            'name' => '貴志川線',
        ],
    ],
];
