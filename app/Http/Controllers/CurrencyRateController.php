<?php

namespace App\Http\Controllers;

use App\Models\CurrencyRate;
use Illuminate\Database\Eloquent\Builder;

class CurrencyRateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $per_page = request()->get('per_page') ?: 10;
        $date = request()->get('date', null);

        $latestIds = CurrencyRate::query()
            ->select(['id', 'num_code'])
            ->when($date, function (Builder $q) use ($date) {
                return $q->where('date', $date);
            })
            ->orderByDesc('date')
            ->get()
            ->unique('num_code')
            ->pluck('id');

        $currencies = CurrencyRate::query()
            ->whereIn('id', $latestIds)
            ->paginate($per_page);

        return response()->json($currencies);
    }

    /**
     * Display the specified resource.
     *
     * @param int $num_code
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $num_code)
    {
        $per_page = request()->get('per_page') ?: 10;

        $rates = CurrencyRate::query()
            ->where('num_code', $num_code)
            ->orderByDesc('date')
            ->paginate($per_page);

        if ($rates->total() < 1) {
            return response()->json(compact('rates'));
        }

        $name = $rates->items()[0]->name;

        return response()->json(compact('rates', 'name'));
    }
}
