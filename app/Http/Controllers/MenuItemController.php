<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index(Request $request)
    {
        $query = MenuItem::with('category');
        if ($request->filled('category_id')) {
            $query->where('menu_category_id', $request->input('category_id'));
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        $categories = MenuCategory::orderBy('name')->get();

        return view('menu-items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = MenuCategory::where('status', 'active')->orderBy('name')->get();

        return view('menu-items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'prep_time' => 'required|integer|min:1',
            'is_vegetarian' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);
        $data['is_vegetarian'] = $request->boolean('is_vegetarian');
        MenuItem::create($data);

        return redirect()->route('menu-items.index')->with('success', 'Menu item created.');
    }

    public function edit(MenuItem $menu_item)
    {
        $categories = MenuCategory::where('status', 'active')->orderBy('name')->get();

        return view('menu-items.edit', ['item' => $menu_item, 'categories' => $categories]);
    }

    public function update(Request $request, MenuItem $menu_item)
    {
        $data = $request->validate([
            'menu_category_id' => 'required|exists:menu_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'prep_time' => 'required|integer|min:1',
            'is_vegetarian' => 'nullable|boolean',
            'status' => 'required|in:active,inactive',
        ]);
        $data['is_vegetarian'] = $request->boolean('is_vegetarian');
        $menu_item->update($data);

        return redirect()->route('menu-items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menu_item)
    {
        $menu_item->delete();

        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted.');
    }
}
