<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\InvestmentOpportunity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── CATEGORIES ────────────────────────────────────
        $categories = [
            ['name' => 'Green Coffee Beans', 'name_am' => 'አረንጓዴ ቡና ፍሬ', 'slug' => 'green-beans'],
            ['name' => 'Roasted Coffee',     'name_am' => 'የተቃጠለ ቡና',       'slug' => 'roasted'],
            ['name' => 'Export Grade',       'name_am' => 'ወደ ውጭ ደረጃ',    'slug' => 'export-grade'],
            ['name' => 'Premium Single Origin','name_am' => 'ምርጥ ቦታ ቡና',  'slug' => 'premium'],
            ['name' => 'Specialty Coffee',   'name_am' => 'ልዩ ቡና',         'slug' => 'specialty'],
        ];
        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ─── USERS ─────────────────────────────────────────
        $adminUser = User::firstOrCreate(['email' => 'admin@bunna.et'], [
            'name'      => 'Bunna Admin',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'country'   => 'Ethiopia',
            'region'    => 'Addis Ababa',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'farmer@bunna.et'], [
            'name'      => 'Abebe Girma',
            'password'  => Hash::make('password'),
            'role'      => 'farmer',
            'country'   => 'Ethiopia',
            'region'    => 'Oromia',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'trader@bunna.et'], [
            'name'      => 'Selam Bekele',
            'password'  => Hash::make('password'),
            'role'      => 'trader',
            'country'   => 'Ethiopia',
            'region'    => 'Addis Ababa',
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'investor@bunna.et'], [
            'name'      => 'James Morgan',
            'password'  => Hash::make('password'),
            'role'      => 'investor',
            'country'   => 'United States',
            'region'    => null,
            'is_active' => true,
        ]);

        User::firstOrCreate(['email' => 'customer@bunna.et'], [
            'name'      => 'Sara Haile',
            'password'  => Hash::make('password'),
            'role'      => 'customer',
            'country'   => 'Germany',
            'region'    => null,
            'is_active' => true,
        ]);

        // ─── PRODUCTS ──────────────────────────────────────
        $greenCat  = Category::where('slug', 'green-beans')->first();
        $premCat   = Category::where('slug', 'premium')->first();
        $expCat    = Category::where('slug', 'export-grade')->first();

        $products = [
            [
                'category_id'    => $premCat->id,
                'user_id'        => $adminUser->id,
                'name'           => 'Yirgacheffe Grade 1 — Washed',
                'name_am'        => 'ይርጋጨፌ ደረጃ 1 — ታጥቦ',
                'description'    => 'Ethiopia\'s most celebrated single-origin coffee. Bright floral aroma with distinct citrus and berry notes. Clean and vivid cup profile.',
                'description_am' => 'የኢትዮጵያ ምርጥ ቡና። ደማቅ አበባ መዓዛ ያለው፣ ሎሚ እና ቤሪ ጣዕም አለው።',
                'origin'         => 'Yirgacheffe, SNNPR',
                'grade'          => 'Grade 1',
                'process'        => 'Washed',
                'price_per_kg'   => 8.50,
                'stock_kg'       => 5000,
                'is_available'   => true,
                'is_featured'    => true,
            ],
            [
                'category_id'    => $premCat->id,
                'user_id'        => $adminUser->id,
                'name'           => 'Sidama Washed Grade 2',
                'name_am'        => 'ሲዳማ ታጥቦ ደረጃ 2',
                'description'    => 'Clean, balanced, with stone fruit sweetness and light acidity. Perfect for specialty roasters worldwide.',
                'description_am' => 'ንጹህ፣ ሚዛናዊ፣ ከድንጋይ ፍሬ ጣዕም ጋር።',
                'origin'         => 'Sidama, Ethiopia',
                'grade'          => 'Grade 2',
                'process'        => 'Washed',
                'price_per_kg'   => 7.20,
                'stock_kg'       => 8000,
                'is_available'   => true,
                'is_featured'    => true,
            ],
            [
                'category_id'    => $expCat->id,
                'user_id'        => $adminUser->id,
                'name'           => 'Harrar Natural Grade 4',
                'name_am'        => 'ሐረር ተፈጥሯዊ ደረጃ 4',
                'description'    => 'Bold and wine-like with deep fruity notes. Ethiopia\'s oldest growing region. Complex and full-bodied.',
                'description_am' => 'ደፋር እና ወይን መሰል፣ ጥልቅ ፍራፍሬ ጣዕም ያለው።',
                'origin'         => 'Harrar, Oromia',
                'grade'          => 'Grade 4',
                'process'        => 'Natural',
                'price_per_kg'   => 6.80,
                'stock_kg'       => 12000,
                'is_available'   => true,
                'is_featured'    => false,
            ],
            [
                'category_id'    => $greenCat->id,
                'user_id'        => $adminUser->id,
                'name'           => 'Jimma Grade 3 — Natural',
                'name_am'        => 'ጅማ ደረጃ 3 — ተፈጥሯዊ',
                'description'    => 'Rich, earthy flavor with chocolate undertones. Excellent for espresso blends and commercial roasters.',
                'description_am' => 'ሀብታም፣ ምድራዊ ጣዕም ከቸኮሌት ጋር።',
                'origin'         => 'Jimma, Oromia',
                'grade'          => 'Grade 3',
                'process'        => 'Natural',
                'price_per_kg'   => 5.90,
                'stock_kg'       => 20000,
                'is_available'   => true,
                'is_featured'    => false,
            ],
            [
                'category_id'    => $premCat->id,
                'user_id'        => $adminUser->id,
                'name'           => 'Guji Anaerobic Natural',
                'name_am'        => 'ጉጂ አናኤሮቢክ ተፈጥሯዊ',
                'description'    => 'Experimental processing from the Guji highlands. Tropical fruit forward with incredible sweetness.',
                'description_am' => 'ከጉጂ ከፍተኛ ቦታ ሙከራ ቡና። ሞቃታማ ፍሬ ጣዕም።',
                'origin'         => 'Guji, Oromia',
                'grade'          => 'Grade 1',
                'process'        => 'Anaerobic Natural',
                'price_per_kg'   => 12.00,
                'stock_kg'       => 2000,
                'is_available'   => true,
                'is_featured'    => true,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product['name']], $product);
        }

        // ─── INVESTMENT OPPORTUNITIES ──────────────────────
        InvestmentOpportunity::firstOrCreate(['title' => 'Yirgacheffe Farm Expansion'], [
            'description'         => 'Support the expansion of a 50-hectare premium coffee farm in Yirgacheffe. Modern processing facilities included.',
            'type'                => 'Farm',
            'location'            => 'Yirgacheffe, SNNPR',
            'min_investment'      => 50000.00,
            'expected_return_pct' => 18.5,
            'duration_months'     => 36,
            'status'              => 'open',
        ]);

        InvestmentOpportunity::firstOrCreate(['title' => 'Sidama Cooperative Processing'], [
            'description'         => 'Co-investment in a new wet processing plant for 200 smallholder farmers in the Sidama region.',
            'type'                => 'Cooperative',
            'location'            => 'Sidama, Ethiopia',
            'min_investment'      => 25000.00,
            'expected_return_pct' => 14.0,
            'duration_months'     => 24,
            'status'              => 'open',
        ]);

        InvestmentOpportunity::firstOrCreate(['title' => 'Ethiopian Coffee Export License'], [
            'description'         => 'Stake in a licensed export operation shipping 500 tonnes/year to EU and US specialty markets.',
            'type'                => 'Export',
            'location'            => 'Addis Ababa, Ethiopia',
            'min_investment'      => 100000.00,
            'expected_return_pct' => 22.0,
            'duration_months'     => 48,
            'status'              => 'open',
        ]);

        $this->command->info('✅ BUNNA database seeded successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin',    'admin@bunna.et',    'password'],
                ['Farmer',   'farmer@bunna.et',   'password'],
                ['Trader',   'trader@bunna.et',   'password'],
                ['Investor', 'investor@bunna.et', 'password'],
                ['Customer', 'customer@bunna.et', 'password'],
            ]
        );
    }
}