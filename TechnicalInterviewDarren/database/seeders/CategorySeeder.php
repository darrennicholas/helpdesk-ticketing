<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;
class CategorySeeder extends Seeder
{
    public function run()
    {
        $names = ['Hardware','Software','Network','Email','Access Rights','Other'];
        foreach($names as $name) Category::create(['name'=>$name, 'slug'=>strtolower($name)]);
    }
}