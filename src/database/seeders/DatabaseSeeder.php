<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
         $this->call(CategorySeeder::class);
         $this->call(ProductSeeder::class);
    }
}
