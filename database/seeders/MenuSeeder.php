<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
       
        $menuItems = [
            [
                'name' => 'Burger',
                'description' => 'Delicious burger with all the fixings',
                'category' => 'Main',
                'price' => 9.99,
                'image_url' => 'burger.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Pizza',
                'description' => 'Tasty pizza with your favorite toppings',
                'category' => 'Main',
                'price' => 12.99,
                'image_url' => 'pizza.jpg',
                'is_available' => true,
            ],
            [
                'name' => 'Salad',
                'description' => 'Fresh salad with mixed greens and dressing',
                'category' => 'Starter',
                'price' => 6.99,
                'image_url' => 'salad.jpg',
                'is_available' => true,
            ],
           
        ];
        

        foreach ($menuItems as $menuItem) {
            Menu::create($menuItem);
        }
    }
}
