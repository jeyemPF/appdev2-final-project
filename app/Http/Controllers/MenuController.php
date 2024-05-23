<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        // Retrieve 20 random menu items
        $menuItems = Menu::inRandomOrder()->limit(20)->get();

        // Return the menu items as a JSON response
        return response()->json($menuItems);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'is_available' => 'boolean',
        ]);

        $menu = Menu::create($data);
        return response()->json($menu, 201);
    }

    public function show($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric',
            'is_available' => 'boolean',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update($data);
        return response()->json($menu);
    }

    public function destroy($id)
    {
        Menu::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
