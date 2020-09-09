<?php

use Illuminate\Database\Seeder;
use App\Category;
class CategorySeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
           ['name' => 'Gold'], 
           ['name' => 'Silver'],  
        ];

        foreach ($categories as $idx => $category) {
            $category_id = Role::create($category);
        } 
    }
}
