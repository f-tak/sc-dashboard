<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClockController extends Controller
{
    // json戻り値
    public $return_json = null;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $this->return_json = config('app.name');

        return response()->json($this->return_json);
    }
}