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
        $user = auth()->user();

        $inventoryItems = InventoryItem::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->get();

        $totalItems = $inventoryItems->count();

        $lowStockItems = $inventoryItems->filter(function ($item) {
            return $item->quantity <= $item->minimum_stock;
        });

        $lowStockCount = $lowStockItems->count();

        $totalRecipes = RecipeIngredient::where('hotel_id', $user->hotel_id)
            ->whereHas('menuItem', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id)
                    ->where('department_id', $user->department_id)
                    ->where('is_active', true);
            })
            ->count();

        $totalBuffets = BuffetMenu::where('hotel_id', $user->hotel_id)
            ->where('department_id', $user->department_id)
            ->where('is_active', true)
            ->count();

        $recentWastages = InventoryWastage::with(['item', 'user'])
            ->where('hotel_id', $user->hotel_id)
            ->whereHas('item', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id)
                    ->where('department_id', $user->department_id);
            })
            ->latest()
            ->limit(5)
            ->get();

        $lowStockAlerts = $lowStockItems->take(5);

        return view('dashboard.kitchen-supervisor.index', compact(
            'totalItems',
            'lowStockCount',
            'totalRecipes',
            'totalBuffets',
            'recentWastages',
            'lowStockAlerts'
        ));
    }

    public function index()
    {
        $user = auth()->user();

        $employees = User::with(['role', 'department'])
            ->where('hotel_id', $user->hotel_id)
            ->where('status', 'active')
            ->where('department_id', $user->department_id)
            ->orderBy('name')
            ->get();

        $departments = Department::where('hotel_id', $user->hotel_id)
            ->where('id', $user->department_id)
            ->get();

        $shifts = RotaShift::with(['user', 'department'])
            ->where('department_id', $user->department_id)
            ->whereHas('user', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id);
            })
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
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:morning,evening,night,split,day_off,holiday,sick',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'notes' => 'nullable|string|max:1000',
        ]);

        $employee = User::where('id', $request->user_id)
            ->where('hotel_id', $supervisor->hotel_id)
            ->where('department_id', $supervisor->department_id)
            ->firstOrFail();

        RotaShift::updateOrCreate(
            [
                'user_id' => $employee->id,
                'shift_date' => $request->shift_date,
            ],
            [
                'department_id' => $supervisor->department_id,
                'shift_type' => $request->shift_type,
                'start_time' => in_array($request->shift_type, ['day_off', 'holiday', 'sick'])
                    ? null
                    : $request->start_time,
                'end_time' => in_array($request->shift_type, ['day_off', 'holiday', 'sick'])
                    ? null
                    : $request->end_time,
                'notes' => $request->notes,
                'created_by' => $supervisor->id,
            ]
        );

        return back()->with('success', 'Shift saved successfully.');
    }

    public function destroy($id)
    {
        $user = auth()->user();

        $shift = RotaShift::where('id', $id)
            ->where('department_id', $user->department_id)
            ->whereHas('user', function ($q) use ($user) {
                $q->where('hotel_id', $user->hotel_id);
            })
            ->firstOrFail();

        $shift->delete();

        return back()->with('success', 'Shift deleted successfully.');
    }

    public function view()
    {
        $supervisor = auth()->user();

        $employees = User::where('hotel_id', $supervisor->hotel_id)
            ->where('department_id', $supervisor->department_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $weekStart = request('week_start')
            ? \Carbon\Carbon::parse(request('week_start'))->startOfWeek()
            : now()->startOfWeek();

        $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
            return $weekStart->copy()->addDays($day);
        });

        $shifts = RotaShift::where('department_id', $supervisor->department_id)
            ->whereHas('user', function ($q) use ($supervisor) {
                $q->where('hotel_id', $supervisor->hotel_id);
            })
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
    }

    public function aiPrepPlan(Request $request)
    {
        $user = auth()->user();
        $date = $request->date ?? now()->toDateString();

        $sales = DB::table('buffet_sales')
            ->join('buffet_menus', 'buffet_sales.buffet_menu_id', '=', 'buffet_menus.id')
            ->where('buffet_sales.hotel_id', $user->hotel_id)
            ->where('buffet_menus.hotel_id', $user->hotel_id)
            ->where('buffet_menus.department_id', $user->department_id)
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
            ->where('buffet_sales.hotel_id', $user->hotel_id)
            ->where('buffet_menus.hotel_id', $user->hotel_id)
            ->where('buffet_menus.department_id', $user->department_id)
            ->where('buffet_menu_items.hotel_id', $user->hotel_id)
            ->where('menu_items.hotel_id', $user->hotel_id)
            ->where('recipe_ingredients.hotel_id', $user->hotel_id)
            ->where('inventory_items.hotel_id', $user->hotel_id)
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