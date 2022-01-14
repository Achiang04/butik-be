<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\User;

use function PHPSTORM_META\map;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Williams',
            'email' => 'williams342002@gmail.com',
            'password' => bcrypt('Password')
        ]);

        Category::create([
            'name' => 'Batik',
            'slug' => 'batik'
        ]);

        Category::create([
            'name' => 'Kemeja',
            'slug' => 'kemeja'
        ]);

        Product::create([
           'name' => 'Baju batik bunga',
           'category_id' => '1',
           'stock' => 5,
           'price' => 149000,
           'user_id' => '1',
           'image' => ''
        ]);

    }
}
