<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    public function run()
    {
        // Truncate tables
        Schema::disableForeignKeyConstraints();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            'Clothing' => [
                'Men’s Wear' => [
                    'Shirts' => ['Pistis Oxford Button-Down Shirt', 'Classic White Dress Shirt', 'Premium Graphic Tee', 'Slim Fit Polo Shirt'],
                    'Trousers' => ['Khaki Slim Fit Chinos', 'Formal Grey Dress Pants', 'Multi-pocket Cargo Pants'],
                    'Jeans' => ['Deep Blue Slim Fit Jeans', 'Classic Straight Leg Jeans', 'Vintage Distressed Denim'],
                    'Suits & Blazers' => ['Navy Blue 2-Piece Suit', 'Charcoal 3-Piece Formal Suit', 'Casual Linen Blazer'],
                    'Activewear' => ['Compression Gym Tee', 'Performance Tracksuit', 'Fleece Tech Joggers'],
                    'Traditional Wear' => ['Embroidered White Kaftan', 'Traditional Agbada Set', 'Hand-woven African Print Shirt'],
                ],
                'Women’s Wear' => [
                    'Dresses' => ['Floral Summer Maxi Dress', 'Elegant Black Evening Gown', 'Ribbed Bodycon Midi Dress', 'Casual Cotton Day Dress'],
                    'Tops' => ['Silk Button-up Blouse', 'Essential White V-Neck Tee', 'Ribbed Tank Top', 'Floral Pattern Crop Top'],
                    'Bottoms' => ['A-line Midi Skirt', 'High-waisted Skinny Jeans', 'Comfy Black Leggings', 'Wide-leg Tailored Trousers'],
                    'Outerwear' => ['Premium Denim Jacket', 'Classic Wool Overcoat', 'Chunky Knit Cardigan'],
                    'Activewear' => ['High-Performance Yoga Set', 'Breathable Gym Leggings'],
                    'Traditional Wear' => ['Exclusive Kente Maxi Dress', 'Ankara Print Peplum Top', 'African Embroidery Kaftan'],
                ],
            ],
            'Kids’ Wear' => [
                'Baby Clothing' => ['Organic Cotton Onesie Set', 'Cozy Fleece Romper', 'Complete Newborn Gift Set'],
                'Boys’ Clothing' => ['Boys Graphic Button-down', 'Kids Cotton Shorts', 'Boys Slim Fit Jeans', 'Sturdy School Uniform Shirt'],
                'Girls’ Clothing' => ['Girls Floral Party Dress', 'Denim Skirt for Girls', 'Glitter Print Top'],
            ],
            'Footwear' => [
                'Casual Shoes' => ['White Minimalist Sneakers', 'Leather Slip-on Loafers', 'Canvas Casual Shoes'],
                'Formal Shoes' => ['Classic Leather Oxfords', 'Wingtip Brogue Shoes', 'Elegant Stiletto Heels'],
                'Sandals & Slippers' => ['Comfortable Leather Sandals', 'Sporty Logo Slides', 'Standard Flip-flops'],
            ],
            'Accessories' => [
                'Bags' => ['Leather Tote Handbag', 'Modern Travel Backpack', 'Sparkly Evening Clutch', 'Weekender Leather Bag'],
                'Jewelry' => ['Gold Layered Necklace', 'Silver Hoop Earrings', 'Beaded Infinity Bracelet', 'Crystal Engagement Ring'],
                'Headwear' => ['Classic Baseball Cap', 'Wide-brim Sun Hat', 'Designer Silk Headwrap'],
            ],
        ];

        foreach ($data as $topLevelName => $subCategories) {
            $parent = Category::create([
                'name' => $topLevelName,
                'slug' => Str::slug($topLevelName),
                'is_active' => true,
            ]);

            foreach ($subCategories as $subName => $leafCategories) {
                // Check if it's a 2-level or 3-level
                if (is_array($leafCategories) && !array_is_list($leafCategories)) {
                    // It's a 3-level structure (e.g., Clothing -> Men's Wear -> Shirts)
                    $subParent = Category::create([
                        'name' => $subName,
                        'slug' => Str::slug($topLevelName . ' ' . $subName),
                        'parent_id' => $parent->id,
                        'is_active' => true,
                    ]);

                    foreach ($leafCategories as $leafName => $products) {
                        $leaf = Category::create([
                            'name' => $leafName,
                            'slug' => Str::slug($subName . ' ' . $leafName),
                            'parent_id' => $subParent->id,
                            'is_active' => true,
                        ]);

                        // Seed at least one product per leaf
                        $count = 0;
                        foreach ($products as $productName) {
                            if ($count >= 1) break; // User said one per subcategory is okay
                            Product::create([
                                'category_id' => $leaf->id,
                                'name' => $productName,
                                'slug' => Str::slug($productName),
                                'description' => "Premium {$productName} from our latest collection. High quality materials and modern design.",
                                'price' => rand(50, 500),
                                'stock_quantity' => rand(5, 50),
                                'status' => 'active',
                                'featured' => rand(0, 1),
                            ]);
                            $count++;
                        }
                    }
                } else {
                    // It's a 2-level structure (e.g., Footwear -> Casual Shoes)
                    $leaf = Category::create([
                        'name' => $subName,
                        'slug' => Str::slug($topLevelName . ' ' . $subName),
                        'parent_id' => $parent->id,
                        'is_active' => true,
                    ]);

                    // $leafCategories is actually the list of products here
                    $count = 0;
                    foreach ($leafCategories as $productName) {
                         if ($count >= 1) break;
                        Product::create([
                            'category_id' => $leaf->id,
                            'name' => $productName,
                            'slug' => Str::slug($productName),
                            'description' => "Premium {$productName} from our latest collection. High quality materials and modern design.",
                            'price' => rand(50, 500),
                            'stock_quantity' => rand(5, 50),
                            'status' => 'active',
                            'featured' => rand(0, 1),
                        ]);
                        $count++;
                    }
                }
            }
        }
    }
}
