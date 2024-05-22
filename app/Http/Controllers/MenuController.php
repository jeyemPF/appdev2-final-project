<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return Menu::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'required|numeric',
            'image_url' => 'nullable|string',
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
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'image_url' => 'nullable|string',
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
