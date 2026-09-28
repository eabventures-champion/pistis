<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlide::truncate();

        HeroSlide::create([
            'collection_name' => 'NEW COLLECTION',
            'subtitle' => 'AUTUMN / WINTER 2026',
            'title' => "THE ART\nOF SIMPLICITY",
            'description' => 'Timeless silhouettes designed for modern expression. Crafted from heavyweight organic textiles and precision tailoring.',
            'image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1600&q=85',
            'mobile_image' => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=900&q=85',
            'primary_button_text' => 'SHOP COLLECTION',
            'primary_button_url' => '/shop',
            'secondary_button_text' => 'VIEW LOOKBOOK',
            'secondary_button_url' => '/shop',
            'sort_order' => 1,
            'display_duration' => 7,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'collection_name' => 'MONOCHROME EDITION',
            'subtitle' => 'CAPSULE 02',
            'title' => "ARCHITECTURAL\nSILHOUETTES",
            'description' => 'Sharp tailoring meets fluid drape. An uncompromising study in structural volume and refined minimalism.',
            'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1600&q=85',
            'mobile_image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85',
            'primary_button_text' => 'EXPLORE CAPSULE',
            'primary_button_url' => '/shop',
            'secondary_button_text' => 'DISCOVER MORE',
            'secondary_button_url' => '/shop',
            'sort_order' => 2,
            'display_duration' => 7,
            'is_active' => true,
        ]);

        HeroSlide::create([
            'collection_name' => 'NOCTURNE SERIES',
            'subtitle' => 'LIMITED RELEASE',
            'title' => "AFTER DARK\nESSENTIALS",
            'description' => 'Deep charcoal tones, premium Italian wool blends, and subtle luster crafted for elevated evening aesthetics.',
            'image' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1600&q=85',
            'mobile_image' => 'https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85',
            'primary_button_text' => 'DISCOVER NOW',
            'primary_button_url' => '/shop',
            'secondary_button_text' => null,
            'secondary_button_url' => null,
            'sort_order' => 3,
            'display_duration' => 7,
            'is_active' => true,
        ]);
    }
}
