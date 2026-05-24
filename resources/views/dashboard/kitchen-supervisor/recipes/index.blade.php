@extends('dashboard.kitchen-supervisor.layout')

@section('content')

<div class="recipe-page">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="header-card">
        <h1>Recipe Management</h1>
        <p>Create menu items and link them with inventory ingredients.</p>
    </div>

    <div class="grid">

        <div class="form-card">
            <h2>Add Recipe</h2>

            <form method="POST" action="{{ route('kitchen.recipes.store') }}">
                @csrf

                <div class="form-group">
                    <label>Menu Item Name</label>
                    <input type="text" name="name" required placeholder="e.g. Chicken Burger">
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Optional description"></textarea>
                </div>

                <div class="form-group">
                    <label>Selling Price</label>
                    <input type="number" name="selling_price" step="0.01" min="0" placeholder="e.g. 8.99">
                </div>

                <h3>Ingredients</h3>

                <div id="ingredients-wrapper">
                    <div class="ingredient-row">
                        <select name="ingredients[0][inventory_item_id]" required>
                            <option value="">Select Ingredient</option>
                            @foreach($inventoryItems as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} ({{ $item->unit }})
                                </option>
                            @endforeach
                        </select>

                        <input type="number" name="ingredients[0][quantity]" step="0.01" min="0.01" required placeholder="Qty">
                    </div>
                </div>

                <button type="button" class="secondary-btn" onclick="addIngredient()">
                    <i class="fa-solid fa-plus"></i>
                    Add Ingredient
                </button>

                <button class="primary-btn">
                    Save Recipe
                </button>
            </form>
        </div>

        <div class="recipe-card">
            <h2>Current Recipes</h2>

            @forelse($menuItems as $menuItem)
                <div class="recipe-item">
                    <div class="recipe-top">
                        <div>
                            <h3>{{ $menuItem->name }}</h3>
                            <p>{{ $menuItem->description ?? 'No description' }}</p>
                            @if($menuItem->selling_price)
                                <span class="price">£{{ number_format($menuItem->selling_price, 2) }}</span>
                            @endif
                        </div>

                        <form method="POST"
                              action="{{ route('kitchen.recipes.destroy', $menuItem->id) }}"
                              onsubmit="return confirm('Remove this recipe?');">
                            @csrf
                            @method('DELETE')
                            <button class="danger-btn">Remove</button>
                        </form>
                    </div>

                    <div class="ingredients-list">
                        @foreach($menuItem->ingredients as $ingredient)
                            <span>
                                {{ $ingredient->inventoryItem->name ?? 'N/A' }}
                                -
                                {{ $ingredient->quantity }}
                                {{ $ingredient->inventoryItem->unit ?? '' }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="empty">No recipes added yet.</p>
            @endforelse
        </div>

    </div>

</div>

<style>
.recipe-page {
    display: grid;
    gap: 22px;
}

.alert-success {
    background: #dcfce7;
    color: #166534;
    padding: 14px 16px;
    border-radius: 14px;
    font-weight: 800;
}

.header-card,
.form-card,
.recipe-card {
    background: white;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.header-card h1,
.form-card h2,
.recipe-card h2 {
    margin: 0 0 8px;
}

.header-card p {
    margin: 0;
    color: #6b7280;
}

.grid {
    display: grid;
    grid-template-columns: 420px 1fr;
    gap: 22px;
    align-items: start;
}

.form-group {
    margin-bottom: 14px;
}

label {
    display: block;
    font-weight: 800;
    margin-bottom: 7px;
    color: #374151;
}

input,
select,
textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 11px 12px;
    outline: none;
    font-size: 14px;
}

textarea {
    resize: vertical;
}

.ingredient-row {
    display: grid;
    grid-template-columns: 1fr 100px;
    gap: 10px;
    margin-bottom: 10px;
}

.primary-btn,
.secondary-btn,
.danger-btn {
    border: none;
    border-radius: 12px;
    padding: 11px 14px;
    font-weight: 900;
    cursor: pointer;
}

.primary-btn {
    width: 100%;
    background: #f97316;
    color: white;
    margin-top: 14px;
}

.secondary-btn {
    width: 100%;
    background: #fff7ed;
    color: #ea580c;
    margin-top: 4px;
}

.danger-btn {
    background: #fee2e2;
    color: #991b1b;
}

.recipe-item {
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 16px;
    margin-bottom: 14px;
}

.recipe-top {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: flex-start;
}

.recipe-item h3 {
    margin: 0 0 6px;
    font-size: 20px;
}

.recipe-item p {
    margin: 0 0 8px;
    color: #6b7280;
}

.price {
    display: inline-block;
    background: #dcfce7;
    color: #166534;
    padding: 6px 10px;
    border-radius: 999px;
    font-weight: 900;
    font-size: 13px;
}

.ingredients-list {
    margin-top: 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.ingredients-list span {
    background: #f3f4f6;
    color: #374151;
    padding: 7px 10px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
}

.empty {
    color: #6b7280;
    text-align: center;
    padding: 30px 0;
}

@media(max-width: 1000px) {
    .grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width: 600px) {
    .ingredient-row {
        grid-template-columns: 1fr;
    }

    .recipe-top {
        flex-direction: column;
    }

    .danger-btn {
        width: 100%;
    }
}
</style>

<script>
let ingredientIndex = 1;

function addIngredient() {
    const wrapper = document.getElementById('ingredients-wrapper');

    const row = document.createElement('div');
    row.className = 'ingredient-row';

    row.innerHTML = `
        <select name="ingredients[${ingredientIndex}][inventory_item_id]" required>
            <option value="">Select Ingredient</option>
            @foreach($inventoryItems as $item)
                <option value="{{ $item->id }}">
                    {{ $item->name }} ({{ $item->unit }})
                </option>
            @endforeach
        </select>

        <input type="number" name="ingredients[${ingredientIndex}][quantity]" step="0.01" min="0.01" required placeholder="Qty">
    `;

    wrapper.appendChild(row);
    ingredientIndex++;
}
</script>

@endsection