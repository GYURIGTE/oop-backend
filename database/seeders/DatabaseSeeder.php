<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Listing;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $frontend = rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/');
        $image = fn (string $file) => "{$frontend}/images/{$file}";
        $password = Hash::make('password');

        $celestine = User::updateOrCreate(
            ['email' => 'celestine@gmail.com'],
            [
                'name' => 'Celestine Degamo',
                'first_name' => 'Celestine',
                'last_name' => 'Degamo',
                'username' => 'celestinedegamo',
                'phone' => '+63 967 491 3012',
                'bio' => 'Buy & Sell',
                'location' => 'Bacolod City',
                'password' => $password,
            ],
        );

        $joniverPogoy = User::updateOrCreate(
            ['email' => 'joniver@gmail.com'],
            [
                'name' => 'Joniver Pogoy',
                'first_name' => 'Joniver',
                'last_name' => 'Pogoy',
                'username' => 'joniverpogoy',
                'location' => 'Molocboloc',
                'password' => $password,
            ],
        );

        $christian = User::updateOrCreate(
            ['email' => 'christian@gmail.com'],
            [
                'name' => 'Christian Sumalinog',
                'first_name' => 'Christian',
                'last_name' => 'Sumalinog',
                'username' => 'christiansumalinog',
                'location' => 'Talisay',
                'password' => $password,
            ],
        );

        $charles = User::updateOrCreate(
            ['email' => 'charles@gmail.com'],
            [
                'name' => 'Charles Dinoy',
                'first_name' => 'Charles',
                'last_name' => 'Dinoy',
                'username' => 'charlesdinoy',
                'location' => 'Cebu City',
                'password' => $password,
            ],
        );

        $joniverDinoy = User::updateOrCreate(
            ['email' => 'joniverd@gmail.com'],
            [
                'name' => 'Joniver Dinoy',
                'first_name' => 'Joniver',
                'last_name' => 'Dinoy',
                'username' => 'joniverdinoy',
                'location' => 'Naga',
                'password' => $password,
            ],
        );

        $listings = [
            // Celestine's listings
            [
                'user_id' => $celestine->id,
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
                'user_id' => $celestine->id,
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
                'user_id' => $celestine->id,
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
                'user_id' => $celestine->id,
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
                'user_id' => $celestine->id,
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
                'user_id' => $celestine->id,
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
                'user_id' => $celestine->id,
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
            // Other sellers' marketplace listings
            [
                'user_id' => $joniverPogoy->id,
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
                'user_id' => $joniverPogoy->id,
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
                'user_id' => $christian->id,
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
                'user_id' => $charles->id,
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
                'user_id' => $joniverDinoy->id,
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
                'user_id' => $joniverDinoy->id,
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

        $seededListings = [];

        foreach ($listings as $data) {
            $listing = Listing::updateOrCreate(
                ['user_id' => $data['user_id'], 'name' => $data['name']],
                collect($data)->except(['image'])->all(),
            );

            $listing->images()->updateOrCreate(
                ['position' => 0],
                ['url' => $image($data['image'])],
            );

            $seededListings[$data['name']] = $listing;
        }

        $conversations = [
            [
                'listing' => 'Rusi Motor x ADV',
                'buyer' => $joniverPogoy,
                'when' => '-1 month',
                'messages' => [
                    ['them', 'Hi Celestine! Is this still available? I\'m interested.', '-2 hours', true],
                    ['me', 'Yes, it\'s available. It\'s in good condition and ready for a new owner.', '-2 hours', true],
                    ['them', 'Okay pwede nanako ma check? meet up lang ta around cordova', '-2 hours', true],
                    ['me', 'sure sir send lang nako contact no# so i can call you', '-2 hours', true],
                    ['them', 'Thanks! See you at 3 PM.', '-2 hours', false],
                ],
            ],
            [
                'listing' => 'Iphone 17',
                'buyer' => $christian,
                'when' => '-2 months',
                'messages' => [
                    ['them', 'Is the battery still in good condition?', '-4 hours', true],
                    ['me', 'Yes, 100% battery health. I can send photos if you like.', '-4 hours', true],
                    ['them', 'Please do, thank you!', '-4 hours', false],
                ],
            ],
            [
                'listing' => 'UA T-Shirt',
                'buyer' => $charles,
                'when' => '-4 months',
                'messages' => [
                    ['them', 'Hi, is the UA T-shirt still available?', '-1 day', true],
                    ['me', 'Yes, still available!', '-1 day', true],
                ],
            ],
            // Celestine as the buyer, so the dashboard has both buy and sell
            // activity to chart.
            [
                'listing' => 'Mountain Bike',
                'buyer' => $celestine,
                'seller' => $joniverPogoy,
                'me' => $celestine,
                'when' => '-3 months',
                'messages' => [
                    ['me', 'Hi! Is the mountain bike still available?', '-3 months', true],
                    ['them', 'Yes, still available. You can check it this weekend.', '-3 months', true],
                ],
            ],
        ];

        foreach ($conversations as $data) {
            $listing = $seededListings[$data['listing']];
            $seller = $data['seller'] ?? $celestine;
            $buyer = $data['buyer'];
            $me = $data['me'] ?? $celestine;

            $conversation = Conversation::firstOrCreate([
                'listing_id' => $listing->id,
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
            ]);

            $lastMessageAt = null;

            foreach ($data['messages'] as [$from, $body, $when, $read]) {
                $sender = $from === 'me'
                    ? $me
                    : ($me->id === $buyer->id ? $seller : $buyer);

                $message = Message::updateOrCreate(
                    [
                        'conversation_id' => $conversation->id,
                        'body' => $body,
                    ],
                    [
                        'sender_id' => $sender->id,
                        'read_at' => $read ? now() : null,
                        'created_at' => now()->modify($when),
                        'updated_at' => now()->modify($when),
                    ],
                );

                $lastMessageAt = $message->created_at;
            }

            // Spread conversations across the last few months so the
            // dashboard chart has a real trend to display.
            $conversation->forceFill([
                'created_at' => now()->modify($data['when'] ?? '-1 day'),
                'updated_at' => now()->modify($data['when'] ?? '-1 day'),
                'last_message_at' => $lastMessageAt,
            ])->save();
        }
    }
}
