<?php

namespace App\Http\Controllers;

use App\Models\BuffetMenu;
use App\Models\Department;
use App\Models\InventoryItem;
use App\Models\InventoryWastage;
use App\Models\RecipeIngredient;
use App\Models\RotaShift;
use App\Models\User;
use Illuminate\Http\Request;


class KitchenSupervisorController extends Controller
{


    public function dashboard()
    {
        $inventoryItems = InventoryItem::where('department_id', auth()->user()->department_id)->get();
        $totalItems = $inventoryItems->count();
        $lowStockItems = $inventoryItems->filter(function ($item) {
            return $item->quantity <= $item->minimum_stock;
        });
        $lowStockCount = $lowStockItems->count();

        // Get recipe stats
        $totalRecipes = RecipeIngredient::count();
        $totalBuffets = BuffetMenu::count();

        // Get recent activity
        $recentWastages = InventoryWastage::with('item', 'user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get low stock items for alert
        $lowStockAlerts = $lowStockItems->take(5);

        return view('dashboard.kitchen-supervisor.index', compact(
            'totalItems',
            'lowStockCount',
            'totalRecipes',
            'totalBuffets',
            'recentWastages',
            'lowStockAlerts'
        ));
        // return view('dashboard.kitchen-supervisor.index');
    }


public function index()
{
    $user = auth()->user();

    $employees = User::with(['role', 'department'])
        ->where('status', 'active')
        ->where('department_id', $user->department_id)
        ->orderBy('name')
        ->get();

    $departments = Department::where('id', $user->department_id)->get();

    $shifts = RotaShift::with(['user', 'department'])
        ->where('department_id', $user->department_id)
        ->orderBy('shift_date', 'desc')
        ->orderBy('start_time')
        ->get();

    return view('dashboard.kitchen-supervisor.rota.index', compact(
        'employees',
        'departments',
        'shifts'
    ));
}


public function storeRota(Request $request)
{
    $supervisor = auth()->user();

    $request->validate([
        'user_id' => 'required',
        'shift_date' => 'required|date',
        'shift_type' => 'required',
    ]);

    RotaShift::create([
        'user_id' => $request->user_id,
        'department_id' => $supervisor->department_id,
        'shift_date' => $request->shift_date,
        'shift_type' => $request->shift_type,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'notes' => $request->notes,
        'created_by' => $supervisor->id,
    ]);

    return back()->with('success', 'Shift added successfully.');
}

    public function destroy($id)
    {
        $shift = RotaShift::findOrFail($id);
        $shift->delete();

        return back()->with('success', 'Shift deleted successfully.');
    }
    



    public function view()
{
       $supervisor = auth()->user();

    $employees = User::where(
        'department_id',
        $supervisor->department_id
    )->get();

    $weekStart = request('week_start')
        ? \Carbon\Carbon::parse(request('week_start'))->startOfWeek()
        : now()->startOfWeek();

    $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
        return $weekStart->copy()->addDays($day);
    });

    $rotas = RotaShift::whereBetween('shift_date', [
        $weekStart->copy()->startOfDay(),
        $weekStart->copy()->endOfWeek(),
    ])->get();

 $shifts = RotaShift::where('department_id', $supervisor->department_id)
    ->whereBetween('shift_date', [
        $weekStart->copy()->format('Y-m-d'),
        $weekStart->copy()->addDays(6)->format('Y-m-d'),
    ])
    ->orderBy('start_time')
    ->get();

return view('dashboard.kitchen-supervisor.rota.view', compact(
    'supervisor',
    'employees',
    'weekDates',
    'weekStart',
    'shifts'
));
//     $supervisor = auth()->user();

//     $employees = User::where(
//         'department_id',
//         $supervisor->department_id
//     )->get();

//     $weekStart = request('week_start')
//         ? \Carbon\Carbon::parse(request('week_start'))->startOfWeek()
//         : now()->startOfWeek();

//     $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
//         return $weekStart->copy()->addDays($day);
//     });

//     $rotas = RotaShift::whereBetween('shift_date', [
//         $weekStart->copy()->startOfDay(),
//         $weekStart->copy()->endOfWeek(),
//     ])->get();

//  $shifts = RotaShift::where('department_id', $supervisor->department_id)
//     ->whereBetween('shift_date', [
//         $weekStart->copy()->format('Y-m-d'),
//         $weekStart->copy()->addDays(6)->format('Y-m-d'),
//     ])
//     ->orderBy('start_time')
//     ->get();

// return view('dashboard.kitchen-supervisor.rota.view', compact(
//     'supervisor',
//     'employees',
//     'weekDates',
//     'weekStart',
//     'shifts'
// ));
}
}