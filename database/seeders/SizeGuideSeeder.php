<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SizeGuide;
use Illuminate\Database\Seeder;

class SizeGuideSeeder extends Seeder
{
    public function run(): void
    {
        $menCategory = Category::where('slug', 'men')->first();
        $womenCategory = Category::where('slug', 'women')->first();

        $sizes = ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL'];

        $measurements = [
            [
                'name' => 'Body Length',
                'unit' => 'cm',
                'values' => [
                    'XXS' => 66,
                    'XS'  => 68,
                    'S'   => 70,
                    'M'   => 72,
                    'L'   => 74,
                    'XL'  => 76,
                    '2XL' => 78,
                ],
            ],
            [
                'name' => 'Chest Width',
                'unit' => 'cm',
                'values' => [
                    'XXS' => 62,
                    'XS'  => 64,
                    'S'   => 66,
                    'M'   => 68,
                    'L'   => 70,
                    'XL'  => 72,
                    '2XL' => 74,
                ],
            ],
            [
                'name' => 'Bottom Width',
                'unit' => 'cm',
                'values' => [
                    'XXS' => 44,
                    'XS'  => 46,
                    'S'   => 48,
                    'M'   => 50,
                    'L'   => 52,
                    'XL'  => 54,
                    '2XL' => 56,
                ],
            ],
            [
                'name' => 'Sleeve Length',
                'unit' => 'cm',
                'values' => [
                    'XXS' => 78,
                    'XS'  => 80,
                    'S'   => 82,
                    'M'   => 84,
                    'L'   => 86,
                    'XL'  => 88,
                    '2XL' => 90,
                ],
            ],
        ];

        // 1. Women's Hoodie Size Guide (Unisex Fit)
        SizeGuide::updateOrCreate(
            ['name' => "Women's Hoodie Size Guide (Unisex Fit)"],
            [
                'category_id'  => $womenCategory ? $womenCategory->id : null,
                'fit_type'     => 'Unisex Fit',
                'description'  => 'Pistis luxury unisex hoodie sizing in centimeters. For an oversized drape, we recommend choosing your standard size.',
                'sizes'        => $sizes,
                'measurements' => $measurements,
                'default_unit' => 'cm',
                'is_active'    => true,
                'sort_order'   => 1,
            ]
        );

        // 2. Men's Hoodie Size Guide (Unisex Fit)
        SizeGuide::updateOrCreate(
            ['name' => "Men's Hoodie Size Guide (Unisex Fit)"],
            [
                'category_id'  => $menCategory ? $menCategory->id : null,
                'fit_type'     => 'Unisex Fit',
                'description'  => 'Pistis luxury unisex hoodie sizing in centimeters. For an oversized drape, we recommend choosing your standard size.',
                'sizes'        => $sizes,
                'measurements' => $measurements,
                'default_unit' => 'cm',
                'is_active'    => true,
                'sort_order'   => 2,
            ]
        );
    }
}
