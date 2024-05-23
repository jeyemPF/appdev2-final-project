<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                "Tapasilog",
                "Sisiglog",
                "Kawali Silog",
                "Chicken Silog",
                "Hotsilog",
                "Tocilog",
                "Chowfan",
                "Liempo Silog",
                "Pork Chop",
                "Bangsilog",
                "Burger Silog",
                "Longsilog",  
                "Adobo Silog",  
                "Cornsilog",  
                "Dasilog",  
                "Porksilog",  
                "Spamsilog",  
                "Tunaslog",  
                "Embutidosilog",    
                "Baconsilog" 
            ]),
            'price' => $this->faker->randomFloat(2, 5, 20), 
            'is_available' => $this->faker->boolean,
        ];
    }
}
