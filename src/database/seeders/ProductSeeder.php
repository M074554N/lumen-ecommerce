<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 0; $i < 200; $i++) {
            Product::create(
                [
                    'name' => $this->faker->sentence(rand(3, 6)),
                    'price' => $this->faker->randomNumber(rand(3, 4)),
                    'category_id' => rand(1, 10),
                ]
            );
        }
    }
}
