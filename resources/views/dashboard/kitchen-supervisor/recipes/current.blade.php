@extends('dashboard.kitchen-supervisor.layout')

@section('content')

<div class="recipe-page">

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="header-card">
        <h1>Current Recipes</h1>
        <p>Search, edit, and manage all kitchen recipes.</p>
    </div>

    <div class="recipe-card">

        <div class="recipe-card-header">
            <h2>Recipe List</h2>

            <input
                type="text"
                id="recipeSearch"
                placeholder="Search recipe..."
                onkeyup="searchRecipes()">
        </div>

        <div class="recipe-scroll">

            @forelse($menuItems as $menuItem)

                <div class="recipe-item searchable-recipe"
                     data-name="{{ strtolower($menuItem->name) }}">

                    <div class="recipe-top">

                        <div>
                            <h3>{{ $menuItem->name }}</h3>

                            <p>
                                {{ $menuItem->description ?? 'No description' }}
                            </p>

                            @if($menuItem->selling_price)
                                <span class="price">
                                    £{{ number_format($menuItem->selling_price, 2) }}
                                </span>
                            @endif
                        </div>

                        <div class="action-buttons">

                            <button
                                type="button"
                                class="edit-btn"
                                onclick="openEditRecipe({{ $menuItem->id }})">
                                Edit
                            </button>

                            <form method="POST"
                                  action="{{ route('kitchen.recipes.destroy', $menuItem->id) }}"
                                  onsubmit="return confirm('Remove this recipe?');">

                                @csrf
                                @method('DELETE')

                                <button class="danger-btn">
                                    Remove
                                </button>

                            </form>

                        </div>

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

                <div class="modal" id="editRecipeModal{{ $menuItem->id }}">

                    <div class="modal-box">

                        <div class="modal-header">

                            <h2>Edit Recipe</h2>

                            <button type="button"
                                    onclick="closeEditRecipe({{ $menuItem->id }})">
                                &times;
                            </button>

                        </div>

                        <form method="POST"
                              action="{{ route('kitchen.recipes.update', $menuItem->id) }}">

                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Menu Item Name</label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ $menuItem->name }}"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>

                                <textarea
                                    name="description"
                                    rows="3">{{ $menuItem->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Selling Price</label>

                                <input
                                    type="number"
                                    name="selling_price"
                                    step="0.01"
                                    min="0"
                                    value="{{ $menuItem->selling_price }}">
                            </div>

                            <h3>Ingredients</h3>

                            <div id="edit-ingredients-wrapper-{{ $menuItem->id }}">

                                @foreach($menuItem->ingredients as $index => $ingredient)

                                    <div class="ingredient-row">

                                        <select
                                            name="ingredients[{{ $index }}][inventory_item_id]"
                                            required>

                                            <option value="">Select Ingredient</option>

                                            @foreach($inventoryItems as $item)

                                                <option value="{{ $item->id }}"
                                                    {{ $ingredient->inventory_item_id == $item->id ? 'selected' : '' }}>

                                                    {{ $item->name }}
                                                    ({{ $item->unit }})

                                                </option>

                                            @endforeach

                                        </select>

                                        <input
                                            type="number"
                                            name="ingredients[{{ $index }}][quantity]"
                                            step="0.01"
                                            min="0.01"
                                            value="{{ $ingredient->quantity }}"
                                            required>

                                    </div>

                                @endforeach

                            </div>

                            <button type="button"
                                    class="secondary-btn"
                                    onclick="addEditIngredient({{ $menuItem->id }})">

                                Add Ingredient

                            </button>

                            <button class="primary-btn">
                                Save Changes
                            </button>

                        </form>

                    </div>

                </div>

            @empty

                <p class="empty">
                    No recipes added yet.
                </p>

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
.recipe-card {
    background: white;
    border-radius: 22px;
    padding: 22px;
    box-shadow: 0 10px 28px rgba(0,0,0,0.06);
}

.recipe-card-header {
    display: flex;
    justify-content: space-between;
    gap: 14px;
    align-items: center;
    margin-bottom: 16px;
}

.recipe-card-header input {
    max-width: 280px;
}

.recipe-scroll {
    max-height: 700px;
    overflow-y: auto;
    padding-right: 6px;
}

.recipe-scroll::-webkit-scrollbar {
    width: 8px;
}

.recipe-scroll::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 999px;
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

.action-buttons {
    display: flex;
    gap: 8px;
}

.ingredients-list {
    margin-top: 14px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.ingredients-list span {
    background: #f3f4f6;
    padding: 7px 10px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 13px;
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

.edit-btn,
.danger-btn,
.primary-btn,
.secondary-btn {
    border: none;
    border-radius: 12px;
    padding: 11px 14px;
    font-weight: 900;
    cursor: pointer;
}

.edit-btn {
    background: #1583ff;
    color: white;
}

.danger-btn {
    background: #fee2e2;
    color: #991b1b;
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
    margin-top: 8px;
}

.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 18px;
}

.modal.active {
    display: flex;
}

.modal-box {
    width: 100%;
    max-width: 620px;
    max-height: 90vh;
    overflow-y: auto;
    background: white;
    border-radius: 22px;
    padding: 22px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 18px;
}

.modal-header button {
    border: none;
    background: #f3f4f6;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    font-size: 26px;
    cursor: pointer;
}

.form-group {
    margin-bottom: 14px;
}

input,
select,
textarea {
    width: 100%;
    border: 1px solid #d1d5db;
    border-radius: 12px;
    padding: 11px 12px;
}

.ingredient-row {
    display: grid;
    grid-template-columns: 1fr 100px;
    gap: 10px;
    margin-bottom: 10px;
}

.empty {
    text-align: center;
    color: #6b7280;
    padding: 30px;
}

</style>

<script>

function searchRecipes() {
    const input = document.getElementById('recipeSearch').value.toLowerCase();
    const recipes = document.querySelectorAll('.searchable-recipe');

    recipes.forEach(recipe => {
        const name = recipe.getAttribute('data-name');

        recipe.style.display = name.includes(input)
            ? 'block'
            : 'none';
    });
}

function openEditRecipe(id) {
    document.getElementById('editRecipeModal' + id)
        .classList.add('active');
}

function closeEditRecipe(id) {
    document.getElementById('editRecipeModal' + id)
        .classList.remove('active');
}

let editIngredientIndexes = {};

function addEditIngredient(menuItemId) {

    const wrapper =
        document.getElementById(
            'edit-ingredients-wrapper-' + menuItemId
        );

    if (!editIngredientIndexes[menuItemId]) {
        editIngredientIndexes[menuItemId] =
            wrapper.children.length;
    }

    const index =
        editIngredientIndexes[menuItemId];

    const row =
        document.createElement('div');

    row.className = 'ingredient-row';

    row.innerHTML = `
        <select name="ingredients[${index}][inventory_item_id]" required>
            <option value="">Select Ingredient</option>

            @foreach($inventoryItems as $item)

                <option value="{{ $item->id }}">
                    {{ $item->name }}
                    ({{ $item->unit }})
                </option>

            @endforeach
        </select>

        <input type="number"
               name="ingredients[${index}][quantity]"
               step="0.01"
               min="0.01"
               required
               placeholder="Qty">
    `;

    wrapper.appendChild(row);

    editIngredientIndexes[menuItemId]++;
}

</script>

@endsection