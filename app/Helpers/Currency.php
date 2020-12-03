<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class Currency
{
    const BASE_URL = 'http://www.cbr.ru/scripts/XML_daily_eng.asp';

    /**
     * @param Carbon $date
     *
     * @return array
     */
    public static function getByDate(Carbon $date)
    {
        $res = Http::get(self::BASE_URL, ['date_req' => $date->format('d/m/Y')]);
        $xml = simplexml_load_string($res->body());

        return ((array)$xml)['Valute'];
    }
}
