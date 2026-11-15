<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the owner account and the demo marketplace listings.
     *
     * Everything belongs to a single account. Chat, meet-up and dashboard
     * activity is created naturally once a second account is registered and
     * starts a conversation.
     */
    public function run(): void
    {
        $frontend = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/');
        $image = fn (string $file) => "{$frontend}/images/{$file}";
        $password = Hash::make('password');

        $owner = User::updateOrCreate(
            ['email' => 'chrislawrencedegamo182@gmail.com'],
            [
                'name' => 'Chris Lawrence Degamo',
                'first_name' => 'Chris',
                'last_name' => 'Lawrence Degamo',
                'username' => 'chrislawrence',
                'phone' => '+63 967 491 3012',
                'bio' => 'Buy & Sell',
                'location' => 'Bacolod City',
                'password' => $password,
            ],
        );

        $listings = [
            [
                'name' => 'UA T-Shirt',
                'category' => 'Clothing',
                'price' => 8500,
                'condition' => 'Used',
                'location' => 'San Remigio',
                'status' => 'Active',
                'views' => 486,
                'description' => 'UA T-shirt in good condition and comfortable to wear. Perfect for everyday use, workouts, or casual outfits.',
                'image' => 'ua-tshirt.png',
            ],
            [
                'name' => 'Cage',
                'category' => 'Pet Supplies',
                'price' => 15000,
                'condition' => 'Used',
                'location' => 'Cebu City',
                'status' => 'Active',
                'views' => 352,
                'description' => 'Sturdy pet cage with plenty of room for small to medium pets. Clean, well-kept, and ready for a new home.',
                'image' => 'cage.png',
            ],
            [
                'name' => 'Iphone 17',
                'category' => 'Electronics',
                'price' => 2200,
                'condition' => 'Like New',
                'location' => 'Mandaue City',
                'status' => 'Sold',
                'views' => 214,
                'description' => 'iPhone 17 in excellent condition with no visible scratches. Complete with box and charger.',
                'image' => 'iphone17.png',
            ],
            [
                'name' => 'JBL Bluetooth Speaker',
                'category' => 'Audio',
                'price' => 1000,
                'condition' => 'Used',
                'location' => 'Bogo City',
                'status' => 'Active',
                'views' => 168,
                'description' => 'JBL Bluetooth speaker with clear sound and strong bass. Battery still holds a good charge.',
                'image' => 'jbl.png',
            ],
            [
                'name' => 'Hydro Flask',
                'category' => 'Home & Living',
                'price' => 800,
                'condition' => 'Brand New',
                'location' => 'Lapu-Lapu City',
                'status' => 'Draft',
                'views' => 92,
                'description' => 'Brand new Hydro Flask with a leak-proof lid. Keeps drinks cold for hours. Never used.',
                'image' => 'hydroflask.png',
            ],
            [
                'name' => 'Powerbank',
                'category' => 'Electronics',
                'price' => 1000,
                'condition' => 'Used',
                'location' => 'Cebu City',
                'status' => 'Sold',
                'views' => 143,
                'description' => 'Fast-charging powerbank with enough capacity for multiple phone charges. Works perfectly.',
                'image' => 'powerbank.png',
            ],
            [
                'name' => 'Rusi Motor x ADV',
                'category' => 'Vehicles',
                'price' => 50000,
                'condition' => 'Used',
                'location' => 'Naga',
                'status' => 'Active',
                'views' => 521,
                'description' => 'RUSI Motor X ADV in good condition, ideal for daily commuting. Meet-up in Naga, Cebu.',
                'image' => 'rusimotor.png',
            ],
            [
                'name' => 'Android Phone',
                'category' => 'Electronics',
                'price' => 2500,
                'condition' => 'Like New',
                'location' => 'Molocboloc',
                'status' => 'Active',
                'views' => 310,
                'description' => 'Well-kept Android phone with a clean screen and reliable battery. Comes with a charger and protective case.',
                'image' => 'androidphone.png',
            ],
            [
                'name' => 'Mountain Bike',
                'category' => 'Sports',
                'price' => 8500,
                'condition' => 'Used',
                'location' => 'Minglanilla',
                'status' => 'Active',
                'views' => 187,
                'description' => 'Reliable mountain bike with smooth gears and strong brakes. Ready for weekend trails.',
                'image' => 'mountainbike.png',
            ],
            [
                'name' => 'Wooden Study Chair',
                'category' => 'Furniture',
                'price' => 1200,
                'condition' => 'Used',
                'location' => 'Talisay',
                'status' => 'Active',
                'views' => 96,
                'description' => 'Solid wooden study chair with a comfortable backrest. Minor scratches from daily use.',
                'image' => 'woodenstudychair.png',
            ],
            [
                'name' => 'Nike Running Shoes',
                'category' => 'Apparel',
                'price' => 1500,
                'condition' => 'Like New',
                'location' => 'Cebu City',
                'status' => 'Active',
                'views' => 233,
                'description' => 'Lightweight Nike running shoes, worn only a few times. Clean soles and fresh laces.',
                'image' => 'nikeshoes.png',
            ],
            [
                'name' => '3 by 3 Chicken Cage',
                'category' => 'Pet Supplies',
                'price' => 350,
                'condition' => 'Used',
                'location' => 'Ibabao',
                'status' => 'Active',
                'views' => 74,
                'description' => 'Sturdy 3 by 3 chicken cage made from durable wire mesh. Still in good working condition.',
                'image' => '3by3cage.png',
            ],
            [
                'name' => 'Preloved Book Set',
                'category' => 'Books',
                'price' => 450,
                'condition' => 'Used',
                'location' => 'Cordova',
                'status' => 'Active',
                'views' => 61,
                'description' => 'Bundle of five preloved novels in good condition. Great starter set for your home library.',
                'image' => 'prelovedbookset.png',
            ],
        ];

        foreach ($listings as $data) {
            $listing = Listing::updateOrCreate(
                ['user_id' => $owner->id, 'name' => $data['name']],
                collect($data)->except(['image'])->all(),
            );

            $listing->images()->updateOrCreate(
                ['position' => 0],
                ['url' => $image($data['image'])],
            );
        }
    }
}
