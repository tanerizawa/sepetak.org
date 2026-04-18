<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class KajianIlmiahCategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::updateOrCreate(
            ['slug' => 'kajian-ilmiah'],
            [
                'name' => 'Kajian Ilmiah',
                'description' => 'Kajian, analisis, dan tulisan ilmiah atau semi-ilmiah.',
            ],
        );
    }
}
