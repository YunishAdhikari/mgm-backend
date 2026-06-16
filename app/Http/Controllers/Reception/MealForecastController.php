<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\ForecastGroup;
use App\Models\MealForecast;
use App\Models\MealForecastGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MealForecastController extends Controller
{
    public function index(Request $request)
    {
        $query = MealForecast::with('groups.forecastGroup')
            ->orderBy('forecast_date', 'desc');

        if ($request->filled('from_date')) {
            $query->whereDate('forecast_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('forecast_date', '<=', $request->to_date);
        }

        $forecasts = $query->get();

        return view('dashboard.reception.meal-forecasts.index', compact('forecasts'));
    }

   public function storeOrUpdateDailyTotal(Request $request)
{
    $request->validate([
        'forecast_date' => 'required|date',
        'total_breakfast' => 'required|integer|min:0',
        'total_dinner' => 'required|integer|min:0',
        'notes' => 'nullable|string',
    ]);

    MealForecast::updateOrCreate(
        ['forecast_date' => $request->forecast_date],
        [
            'total_breakfast' => $request->total_breakfast,
            'total_dinner' => $request->total_dinner,
            'notes' => $request->notes,
            'created_by' => Auth::id(),
        ]
    );

    return redirect()
        ->route('reception.meal-forecasts.index')
        ->with('success', 'Daily forecast saved successfully.');
}

public function destroyGroup(MealForecastGroup $mealForecastGroup)
{
    $mealForecastId = $mealForecastGroup->meal_forecast_id;

    $mealForecastGroup->delete();

    return redirect()
        ->route('reception.meal-forecasts.groups.create', $mealForecastId)
        ->with('success', 'Group stay removed successfully.');
}

    public function createGroup(MealForecast $mealForecast)
    {
        $mealForecast->load('groups.forecastGroup');

        $groups = ForecastGroup::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('dashboard.reception.meal-forecasts.create', compact(
            'mealForecast',
            'groups'
        ));
    }

    public function storeGroup(Request $request, MealForecast $mealForecast)
    {
        $request->validate([
            'forecast_group_id' => 'required|exists:forecast_groups,id',
            'package_type' => 'required|in:room_only,bb,dbb,dinner_only',
            'pax' => 'required|integer|min:1',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'notes' => 'nullable|string',
        ]);

        MealForecastGroup::create([
            'meal_forecast_id' => $mealForecast->id,
            'forecast_group_id' => $request->forecast_group_id,
            'package_type' => $request->package_type,
            'pax' => $request->pax,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'notes' => $request->notes,
        ]);

        return redirect()
            ->route('reception.meal-forecasts.groups.create', $mealForecast->id)
            ->with('success', 'Group stay added successfully.');
    }

public function report(Request $request)
{
    $request->validate([
        'from_date' => 'required|date',
        'to_date' => 'required|date|after_or_equal:from_date',
    ]);

    $fromDate = $request->from_date;
    $toDate = $request->to_date;

    $forecasts = MealForecast::whereBetween('forecast_date', [$fromDate, $toDate])
        ->orderBy('forecast_date', 'asc')
        ->get();

    $groupStays = MealForecastGroup::with('forecastGroup')
        ->whereDate('check_in_date', '<=', $toDate)
        ->whereDate('check_out_date', '>=', $fromDate)
        ->get();

    return view('dashboard.reception.meal-forecasts.report', compact(
        'forecasts',
        'groupStays',
        'fromDate',
        'toDate'
    ));
}
}