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
use Illuminate\Support\Facades\DB;

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



public function aiPrepPlan(Request $request)
{
    $date = $request->date ?? now()->toDateString();

    $sales = DB::table('buffet_sales')
        ->join('buffet_menus', 'buffet_sales.buffet_menu_id', '=', 'buffet_menus.id')
        ->whereDate('buffet_sales.sale_date', $date)
        ->select(
            'buffet_sales.id',
            'buffet_sales.pax',
            'buffet_sales.note',
            'buffet_menus.id as buffet_menu_id',
            'buffet_menus.name as buffet_name',
            'buffet_menus.service_type'
        )
        ->get();

    $totalPax = $sales->sum('pax');

    $ingredients = DB::table('buffet_sales')
        ->join('buffet_menus', 'buffet_sales.buffet_menu_id', '=', 'buffet_menus.id')
        ->join('buffet_menu_items', 'buffet_menus.id', '=', 'buffet_menu_items.buffet_menu_id')
        ->join('menu_items', 'buffet_menu_items.menu_item_id', '=', 'menu_items.id')
        ->join('recipe_ingredients', 'menu_items.id', '=', 'recipe_ingredients.menu_item_id')
        ->join('inventory_items', 'recipe_ingredients.inventory_item_id', '=', 'inventory_items.id')
        ->whereDate('buffet_sales.sale_date', $date)
        ->select(
            'inventory_items.name as ingredient_name',
            DB::raw('SUM(recipe_ingredients.quantity * buffet_sales.pax) as total_required')
        )
        ->groupBy('inventory_items.name')
        ->orderBy('inventory_items.name')
        ->get();

    $allergyWarnings = $sales->filter(function ($sale) {
        return $sale->note &&
            preg_match('/allergy|nut|gluten|vegan|vegetarian|dairy|halal|pork/i', $sale->note);
    });

    return view('dashboard.kitchen-supervisor.ai-prep-plan', compact(
        'date',
        'sales',
        'totalPax',
        'ingredients',
        'allergyWarnings'
    ));
}
}