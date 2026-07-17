<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    public function index(Request $request)
    {
        [$sort, $direction] = $this->tableSort($request, ['created_at', 'name', 'status']);
        $categories = MenuCategory::withCount('items')->orderBy($sort, $direction)->paginate($this->tablePerPage($request))->withQueryString();

        return view('menu-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('menu-categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        MenuCategory::create($data);

        return redirect()->route('menu-categories.index')->with('success', 'Category created.');
    }

    public function edit(MenuCategory $menu_category)
    {
        return view('menu-categories.edit', ['category' => $menu_category]);
    }

    public function update(Request $request, MenuCategory $menu_category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $menu_category->update($data);

        return redirect()->route('menu-categories.index')->with('success', 'Category updated.');
    }

    public function destroy(MenuCategory $menu_category)
    {
        $menu_category->delete();

        return redirect()->route('menu-categories.index')->with('success', 'Category deleted.');
    }
}
