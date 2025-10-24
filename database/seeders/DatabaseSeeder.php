<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kavi.cz',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Create test customer
        User::create([
            'name' => 'Test Zákazník',
            'email' => 'zakaznik@example.com',
            'password' => bcrypt('password'),
            'phone' => '+420 123 456 789',
            'address' => 'Testovací 123',
            'city' => 'Praha',
            'postal_code' => '110 00',
        ]);

        // Create subscription plans
        SubscriptionPlan::create([
            'name' => 'Espresso BOX',
            'slug' => 'espresso-box',
            'description' => 'Měsíční balíček prémiových espresso káv z celého světa',
            'price' => 599,
            'interval' => 'monthly',
            'coffee_count' => 2,
            'coffee_weight' => 250,
            'coffee_type' => 'espresso',
            'is_active' => true,
            'features' => [
                '2x 250g kávy měsíčně',
                'Vždy čerstvě pražená',
                'Doprava zdarma',
                'Exkluzivní směsi',
            ],
        ]);

        SubscriptionPlan::create([
            'name' => 'Filtr BOX',
            'slug' => 'filtr-box',
            'description' => 'Objevte svět filtrovaných káv s našimi výběrovými zrny',
            'price' => 649,
            'interval' => 'monthly',
            'coffee_count' => 2,
            'coffee_weight' => 250,
            'coffee_type' => 'filter',
            'is_active' => true,
            'features' => [
                '2x 250g kávy měsíčně',
                'Single origin kávy',
                'Doprava zdarma',
                'Detailní popisky',
            ],
        ]);

        SubscriptionPlan::create([
            'name' => 'Premium BOX',
            'slug' => 'premium-box',
            'description' => 'Ultimátní kávový zážitek s 3 balíčky různých káv',
            'price' => 899,
            'interval' => 'monthly',
            'coffee_count' => 3,
            'coffee_weight' => 250,
            'coffee_type' => 'mixed',
            'is_active' => true,
            'features' => [
                '3x 250g kávy měsíčně',
                'Mix espresso i filtr',
                'Doprava zdarma',
                'Prioritní podpora',
                'Exkluzivní limitované edice',
            ],
        ]);

        // Create sample products
        $products = [
            [
                'name' => 'Brazil Santos',
                'slug' => 'brazil-santos',
                'short_description' => 'Jemná káva s tóny čokolády a ořechů',
                'description' => "Brazil Santos je klasická káva z oblasti Santos v Brazílii. Vyznačuje se jemnou chutí s tóny mléčné čokolády, lískových oříšků a karamelu. Ideální pro espresso i filtrovanou kávu.\n\nPůvod: Brazílie, Santos\nNadmořská výška: 1000-1200 m n.m.\nDruh: Arabica\nZpracování: Natural",
                'price' => 299,
                'stock' => 50,
                'category' => 'espresso',
                'is_active' => true,
                'is_featured' => true,
                'attributes' => [
                    'Původ' => 'Brazílie',
                    'Druh' => 'Arabica',
                    'Pražení' => 'Střední',
                    'Chuť' => 'Čokoláda, ořechy, karamel',
                ],
            ],
            [
                'name' => 'Ethiopia Yirgacheffe',
                'slug' => 'ethiopia-yirgacheffe',
                'short_description' => 'Květinová káva s ovocnými tóny',
                'description' => "Etiopská káva z oblasti Yirgacheffe je známá svou komplexní chutí a výraznou kyselinou. V chuti najdete tóny citrusů, jasmínu a lesních plodů. Ideální pro filtrovanou kávu.\n\nPůvod: Etiopie, Yirgacheffe\nNadmořská výška: 1700-2200 m n.m.\nDruh: Arabica\nZpracování: Washed",
                'price' => 349,
                'stock' => 30,
                'category' => 'filter',
                'is_active' => true,
                'is_featured' => true,
                'attributes' => [
                    'Původ' => 'Etiopie',
                    'Druh' => 'Arabica',
                    'Pražení' => 'Světlé',
                    'Chuť' => 'Citrus, jasmín, lesní plody',
                ],
            ],
            [
                'name' => 'Colombia Supremo',
                'slug' => 'colombia-supremo',
                'short_description' => 'Vyvážená káva s příjemnou kyselinou',
                'description' => "Kolumbijská káva Supremo představuje vrchol kvality kolumbijských káv. Vyznačuje se vyváženou chutí s příjemnou kyselinou a tóny karamelu a ovoce.\n\nPůvod: Kolumbie\nNadmořská výška: 1200-1800 m n.m.\nDruh: Arabica\nZpracování: Washed",
                'price' => 319,
                'stock' => 40,
                'category' => 'espresso',
                'is_active' => true,
                'is_featured' => true,
                'attributes' => [
                    'Původ' => 'Kolumbie',
                    'Druh' => 'Arabica',
                    'Pražení' => 'Střední',
                    'Chuť' => 'Karamel, ovoce, med',
                ],
            ],
            [
                'name' => 'Kenya AA',
                'slug' => 'kenya-aa',
                'short_description' => 'Intenzivní káva s výraznou kyselinou',
                'description' => "Keňská káva AA grade je považována za jednu z nejkvalitnějších káv světa. Výrazná, komplexní chuť s tóny černého rybízu, grapefruitu a rajčete.\n\nPůvod: Keňa\nNadmořská výška: 1500-2100 m n.m.\nDruh: Arabica\nZpracování: Washed",
                'price' => 389,
                'stock' => 25,
                'category' => 'filter',
                'is_active' => true,
                'is_featured' => true,
                'attributes' => [
                    'Původ' => 'Keňa',
                    'Druh' => 'Arabica',
                    'Pražení' => 'Světlé až střední',
                    'Chuť' => 'Černý rybíz, grapefruit',
                ],
            ],
            [
                'name' => 'Italian Espresso Blend',
                'slug' => 'italian-espresso-blend',
                'short_description' => 'Silná směs pro dokonalé espresso',
                'description' => "Naše vlastní směs inspirovaná italskou tradicí. Ideální pro přípravu cremového espressa s plnou chutí a nízkou kyselinou.\n\nSložení: Brazílie, Indie, Robusta\nPražení: Tmavé\nIdeální pro: Espresso, Cappuccino",
                'price' => 279,
                'stock' => 60,
                'category' => 'espresso',
                'is_active' => true,
                'is_featured' => true,
                'attributes' => [
                    'Typ' => 'Směs',
                    'Druh' => 'Arabica + Robusta',
                    'Pražení' => 'Tmavé',
                    'Chuť' => 'Čokoláda, pražené ořechy',
                ],
            ],
            [
                'name' => 'Decaf Colombia',
                'slug' => 'decaf-colombia',
                'short_description' => 'Bezkofeinová káva plná chuti',
                'description' => "Výborná bezkofeinová káva zpracovaná Swiss Water procesem. Zachovává si plnou chuť kolumbijské kávy bez kofeinu.\n\nPůvod: Kolumbie\nOdkofeinění: Swiss Water Process\nDruh: Arabica",
                'price' => 339,
                'stock' => 20,
                'category' => 'filter',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Chemex filtrační papíry',
                'slug' => 'chemex-filtracni-papiry',
                'short_description' => 'Originální filtrační papíry pro Chemex',
                'description' => "Originální filtrační papíry pro Chemex. Balení obsahuje 100 ks. Zajišťují čistou a jasnou chuť kávy bez zbytečných olejů.",
                'price' => 149,
                'stock' => 100,
                'category' => 'accessories',
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'Hario V60 Dripper',
                'slug' => 'hario-v60-dripper',
                'short_description' => 'Keramický dripper pro přípravu filtrované kávy',
                'description' => "Originální Hario V60 dripper velikost 02. Ideální pro přípravu 1-4 šálků výborné filtrované kávy.\n\nMateriál: Keramika\nVelikost: 02\nBarva: Bílá",
                'price' => 599,
                'stock' => 15,
                'category' => 'accessories',
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}


