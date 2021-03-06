<?php

namespace Database\Seeders;

use App\Enums\Categories;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (Categories::ALL as $category) {
            Category::create(
                [
                    'name' => $category,
                ]
            );
        }
    }
}
