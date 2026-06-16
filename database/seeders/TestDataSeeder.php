<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Roastery;
use App\Models\Coupon;
use App\Models\ShipmentSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    /**
     * Seed testovací data pro testování Kavi aplikace.
     * 
     * Spustit: php artisan db:seed --class=TestDataSeeder
     */
    public function run(): void
    {
        $this->command->info('🌱 Seedování testovacích dat pro Kavi...');

        // 1. Vytvořit testovací uživatele
        $this->seedUsers();

        // 2. Vytvořit pražírny
        $this->seedRoasteries();

        // 3. Vytvořit produkty (kávy, příslušenství)
        $this->seedProducts();

        // 4. Vytvořit kupóny
        $this->seedCoupons();

        // 5. Vytvořit harmonogram rozesílek
        $this->seedShipmentSchedules();

        $this->command->info('✅ Testovací data byla úspěšně vytvořena!');
        $this->command->newLine();
        $this->command->info('📝 Přihlašovací údaje:');
        $this->command->info('   Admin: admin@kavi.cz / password123');
        $this->command->info('   Zákazník: test@kavi.cz / password123');
        $this->command->newLine();
        $this->command->info('🎫 Testovací kupóny:');
        $this->command->info('   TEST10 - 10% sleva');
        $this->command->info('   SUB100 - 100 Kč sleva na předplatné (3 měsíce)');
        $this->command->info('   FREESHIP - Doprava zdarma');
        $this->command->info('   WELCOME20 - 20% sleva (aktivace přes link)');
    }

    private function seedUsers(): void
    {
        $this->command->info('👤 Vytváření uživatelů...');

        // Admin uživatel
        User::updateOrCreate(
            ['email' => 'admin@kavi.cz'],
            [
                'name' => 'Admin Kavi',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        )->forceFill(['is_admin' => true])->save();

        // Testovací zákazník
        User::updateOrCreate(
            ['email' => 'test@kavi.cz'],
            [
                'name' => 'Test Zákazník',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'phone' => '+420 123 456 789',
                'billing_street' => 'Testovací 123',
                'billing_city' => 'Praha',
                'billing_zip' => '110 00',
                'billing_country' => 'CZ',
            ]
        );

        // Další testovací zákazníci
        User::updateOrCreate(
            ['email' => 'user1@example.com'],
            [
                'name' => 'Jan Novák',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'phone' => '+420 111 222 333',
                'billing_street' => 'Hlavní 1',
                'billing_city' => 'Brno',
                'billing_zip' => '602 00',
                'billing_country' => 'CZ',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'Marie Svobodová',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'phone' => '+420 444 555 666',
                'billing_street' => 'Dlouhá 25',
                'billing_city' => 'Ostrava',
                'billing_zip' => '702 00',
                'billing_country' => 'CZ',
            ]
        );

        $this->command->info('   ✓ Vytvořeno 4 uživatelů');
    }

    private function seedRoasteries(): void
    {
        $this->command->info('🏭 Vytváření pražíren...');

        $roasteries = [
            [
                'name' => 'Doubleshot',
                'slug' => 'doubleshot',
                'description' => 'Pražírna specialty kávy z Prahy.',
                'city' => 'Praha',
                'website' => 'https://doubleshot.cz',
            ],
            [
                'name' => 'Coffee Source',
                'slug' => 'coffee-source',
                'description' => 'Brněnská pražírna s tradicí.',
                'city' => 'Brno',
                'website' => 'https://coffeesoruce.cz',
            ],
            [
                'name' => 'Nordbeans',
                'slug' => 'nordbeans',
                'description' => 'Severská inspirace v pražení kávy.',
                'city' => 'Liberec',
                'website' => 'https://nordbeans.cz',
            ],
        ];

        foreach ($roasteries as $data) {
            Roastery::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('   ✓ Vytvořeno ' . count($roasteries) . ' pražíren');
    }

    private function seedProducts(): void
    {
        $this->command->info('☕ Vytváření produktů...');

        $roasteryIds = Roastery::pluck('id')->toArray();
        $currentMonth = now()->format('Y-m-01');
        $nextMonth = now()->addMonth()->format('Y-m-01');

        $products = [
            // Espresso kávy
            [
                'name' => 'Honduras El Puente',
                'slug' => 'honduras-el-puente',
                'description' => 'Sladká espresso káva s tóny karamelu a čokolády. Vhodná pro přípravu espressa i cappuccina.',
                'price' => 250,
                'category' => 'coffee',
                'type' => 'espresso',
                'weight' => 250,
                'roastery_id' => $roasteryIds[0] ?? null,
                'stock_quantity' => 100,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $currentMonth,
            ],
            [
                'name' => 'Brazil Santos',
                'slug' => 'brazil-santos',
                'description' => 'Čokoládové espresso s ořechovými tóny. Perfektní základ pro mléčné nápoje.',
                'price' => 240,
                'category' => 'coffee',
                'type' => 'espresso',
                'weight' => 250,
                'roastery_id' => $roasteryIds[1] ?? null,
                'stock_quantity' => 80,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $currentMonth,
            ],
            [
                'name' => 'Colombia Supremo',
                'slug' => 'colombia-supremo',
                'description' => 'Vyvážená káva s citrusovou kyselinou a karamelovou sladkostí.',
                'price' => 260,
                'category' => 'coffee',
                'type' => 'espresso',
                'weight' => 250,
                'roastery_id' => $roasteryIds[2] ?? null,
                'stock_quantity' => 90,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $nextMonth,
            ],

            // Filtr kávy
            [
                'name' => 'Ethiopia Yirgacheffe',
                'slug' => 'ethiopia-yirgacheffe',
                'description' => 'Květinová filtrační káva s tóny jasmínu, bergamotu a citrusů. Ideální pro pour over.',
                'price' => 280,
                'category' => 'coffee',
                'type' => 'filter',
                'weight' => 250,
                'roastery_id' => $roasteryIds[0] ?? null,
                'stock_quantity' => 70,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $currentMonth,
            ],
            [
                'name' => 'Kenya AA',
                'slug' => 'kenya-aa',
                'description' => 'Intenzivní filtrační káva s tóny černého rybízu a červených plodů.',
                'price' => 290,
                'category' => 'coffee',
                'type' => 'filter',
                'weight' => 250,
                'roastery_id' => $roasteryIds[1] ?? null,
                'stock_quantity' => 60,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $currentMonth,
            ],
            [
                'name' => 'Guatemala Antigua',
                'slug' => 'guatemala-antigua',
                'description' => 'Komplexní chuť s tóny hořké čokolády, koření a citrusů.',
                'price' => 270,
                'category' => 'coffee',
                'type' => 'filter',
                'weight' => 250,
                'roastery_id' => $roasteryIds[2] ?? null,
                'stock_quantity' => 75,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $nextMonth,
            ],

            // Decaf
            [
                'name' => 'Colombia Decaf',
                'slug' => 'colombia-decaf',
                'description' => 'Bezkofeinová káva vhodná na espresso i filtr. Sladká s tóny karamelu a kakaa.',
                'price' => 270,
                'category' => 'coffee',
                'type' => 'decaf',
                'weight' => 250,
                'roastery_id' => $roasteryIds[0] ?? null,
                'stock_quantity' => 50,
                'is_active' => true,
                'is_coffee_of_month' => true,
                'coffee_of_month_date' => $currentMonth,
            ],

            // Příslušenství
            [
                'name' => 'Aeropress',
                'slug' => 'aeropress',
                'description' => 'Kompaktní kavárna do ruky. Snadná příprava výborné kávy.',
                'price' => 890,
                'category' => 'accessories',
                'type' => null,
                'weight' => 300,
                'roastery_id' => null,
                'stock_quantity' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Hario V60',
                'slug' => 'hario-v60',
                'description' => 'Klasický keramický dripper pro přípravu filtrační kávy.',
                'price' => 490,
                'category' => 'accessories',
                'type' => null,
                'weight' => 200,
                'roastery_id' => null,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Kávomlýnek Comandante',
                'slug' => 'kavomlynek-comandante',
                'description' => 'Ruční mlýnek s precizním mletím.',
                'price' => 5990,
                'category' => 'accessories',
                'type' => null,
                'weight' => 800,
                'roastery_id' => null,
                'stock_quantity' => 5,
                'is_active' => true,
            ],

            // Accessories (dříve Merch)
            [
                'name' => 'Kavi tričko',
                'slug' => 'kavi-tricko',
                'description' => 'Bavlněné tričko s logem Kavi.',
                'price' => 390,
                'category' => 'accessories',
                'type' => null,
                'weight' => 200,
                'roastery_id' => null,
                'stock_quantity' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Kavi hrnek',
                'slug' => 'kavi-hrnek',
                'description' => 'Keramický hrnek pro domácí použití.',
                'price' => 290,
                'category' => 'accessories',
                'type' => null,
                'weight' => 400,
                'roastery_id' => null,
                'stock_quantity' => 30,
                'is_active' => true,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('   ✓ Vytvořeno ' . count($products) . ' produktů');
    }

    private function seedCoupons(): void
    {
        $this->command->info('🎫 Vytváření kupónů...');

        $coupons = [
            [
                'code' => 'TEST10',
                'name' => 'Testovací sleva 10%',
                'description' => 'Sleva 10% na celý nákup. Pro testování.',
                'type' => 'percentage',
                'value' => 10,
                'applies_to' => 'both',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(3),
                'status' => 'active',
            ],
            [
                'code' => 'SUB100',
                'name' => 'Sleva na předplatné',
                'description' => '100 Kč sleva na první 3 měsíce předplatného.',
                'type' => 'fixed',
                'value' => 100,
                'applies_to' => 'subscription',
                'subscription_discount_months' => 3,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(6),
                'status' => 'active',
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Doprava zdarma',
                'description' => 'Testovací kupón pro dopravu zdarma.',
                'type' => 'free_shipping',
                'value' => 0,
                'applies_to' => 'order',
                'valid_from' => now(),
                'valid_to' => now()->addMonths(3),
                'status' => 'active',
            ],
            [
                'code' => 'WELCOME20',
                'name' => 'Uvítací sleva 20%',
                'description' => 'Sleva 20% pro nové zákazníky. Aktivace přes link.',
                'type' => 'percentage',
                'value' => 20,
                'applies_to' => 'both',
                'min_order_value' => 500,
                'valid_from' => now(),
                'valid_to' => now()->addMonths(6),
                'max_uses' => 100,
                'max_uses_per_user' => 1,
                'status' => 'active',
            ],
            [
                'code' => 'BLACKFRIDAY',
                'name' => 'Black Friday sleva',
                'description' => '30% sleva na celý nákup nad 1000 Kč.',
                'type' => 'percentage',
                'value' => 30,
                'applies_to' => 'order',
                'min_order_value' => 1000,
                'valid_from' => now()->addMonth(),
                'valid_to' => now()->addMonths(2),
                'max_uses' => 500,
                'status' => 'active',
            ],
        ];

        foreach ($coupons as $data) {
            Coupon::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }

        $this->command->info('   ✓ Vytvořeno ' . count($coupons) . ' kupónů');
    }

    private function seedShipmentSchedules(): void
    {
        $this->command->info('📅 Vytváření harmonogramu rozesílek...');

        // Získat kávy pro přiřazení
        $espressos = Product::where('type', 'espresso')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', now()->format('Y-m-01'))
            ->get();

        $filters = Product::where('type', 'filter')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', now()->format('Y-m-01'))
            ->get();

        $decaf = Product::where('type', 'decaf')
            ->where('is_coffee_of_month', true)
            ->first();

        // Aktuální měsíc
        $currentSchedule = ShipmentSchedule::updateOrCreate(
            [
                'year' => now()->year,
                'month' => now()->month,
            ],
            [
                'shipment_date' => now()->addDays(10)->format('Y-m-d'),
                'coffee_slot_e1' => $espressos[0]->id ?? null,
                'coffee_slot_e2' => $espressos[1]->id ?? $espressos[0]->id ?? null,
                'coffee_slot_e3' => $espressos[2]->id ?? $espressos[0]->id ?? null,
                'coffee_slot_f1' => $filters[0]->id ?? null,
                'coffee_slot_f2' => $filters[1]->id ?? $filters[0]->id ?? null,
                'coffee_slot_f3' => $filters[2]->id ?? $filters[0]->id ?? null,
                'coffee_slot_d' => $decaf->id ?? null,
            ]
        );

        // Příští měsíc
        $nextEspressos = Product::where('type', 'espresso')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', now()->addMonth()->format('Y-m-01'))
            ->get();

        $nextFilters = Product::where('type', 'filter')
            ->where('is_coffee_of_month', true)
            ->where('coffee_of_month_date', now()->addMonth()->format('Y-m-01'))
            ->get();

        $nextSchedule = ShipmentSchedule::updateOrCreate(
            [
                'year' => now()->addMonth()->year,
                'month' => now()->addMonth()->month,
            ],
            [
                'shipment_date' => now()->addMonth()->addDays(10)->format('Y-m-d'),
                'coffee_slot_e1' => $nextEspressos[0]->id ?? $espressos[0]->id ?? null,
                'coffee_slot_e2' => $nextEspressos[1]->id ?? $espressos[1]->id ?? null,
                'coffee_slot_e3' => $nextEspressos[2]->id ?? $espressos[0]->id ?? null,
                'coffee_slot_f1' => $nextFilters[0]->id ?? $filters[0]->id ?? null,
                'coffee_slot_f2' => $nextFilters[1]->id ?? $filters[1]->id ?? null,
                'coffee_slot_f3' => $nextFilters[2]->id ?? $filters[0]->id ?? null,
                'coffee_slot_d' => $decaf->id ?? null,
            ]
        );

        $this->command->info('   ✓ Vytvořeny harmonogramy pro ' . now()->format('F Y') . ' a ' . now()->addMonth()->format('F Y'));
    }
}

