<?php

namespace Database\Seeders;

use App\Models\PacketaCarrier;
use Illuminate\Database\Seeder;

class PacketaCarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * NOTE: Only pick-up points and boxes - NO home delivery (HD) carriers!
     * Source: Packeta API carrier/json endpoint (carriers with pickupPoints: true)
     * Last updated: 2025-11-02
     */
    public function run(): void
    {
        $carriers = [
            // === BELGIE ===
            ['carrier_id' => '7910', 'name' => 'BE Belgická pošta PP', 'country_code' => 'BE'],
            
            // === BULHARSKO ===
            ['carrier_id' => '33777', 'name' => 'BG BoxNow Box', 'country_code' => 'BG'],
            ['carrier_id' => '19470', 'name' => 'BG Econt Box', 'country_code' => 'BG'],
            ['carrier_id' => '19471', 'name' => 'BG Econt PP', 'country_code' => 'BG'],
            ['carrier_id' => '26067', 'name' => 'BG Sameday Box', 'country_code' => 'BG'],
            ['carrier_id' => '4017', 'name' => 'BG Speedy PP', 'country_code' => 'BG'],
            
            // === KYPR ===
            ['carrier_id' => '35369', 'name' => 'CY BoxNow Box', 'country_code' => 'CY'],
            
            // === ČESKÁ REPUBLIKA ===
            ['carrier_id' => 'packeta', 'name' => 'CZ Zásilkovna PP', 'country_code' => 'CZ'],
            ['carrier_id' => 'zpoint', 'name' => 'CZ Z-BOX', 'country_code' => 'CZ'],
            
            // === NĚMECKO ===
            ['carrier_id' => '6828', 'name' => 'DE Hermes PP', 'country_code' => 'DE'],
            
            // === DÁNSKO ===
            ['carrier_id' => '4994', 'name' => 'DK Post Nord PP', 'country_code' => 'DK'],
            
            // === ESTONSKO ===
            ['carrier_id' => '5062', 'name' => 'EE Omniva Box', 'country_code' => 'EE'],
            ['carrier_id' => '5061', 'name' => 'EE Omniva PP', 'country_code' => 'EE'],
            ['carrier_id' => '25989', 'name' => 'EE Venipak Box', 'country_code' => 'EE'],
            ['carrier_id' => '25985', 'name' => 'EE Venipak PP', 'country_code' => 'EE'],
            
            // === ŠPANĚLSKO ===
            ['carrier_id' => '4654', 'name' => 'ES MRW PP', 'country_code' => 'ES'],
            
            // === FINSKO ===
            ['carrier_id' => '26987', 'name' => 'FI Matkahuolto Box', 'country_code' => 'FI'],
            ['carrier_id' => '26986', 'name' => 'FI Matkahuolto PP', 'country_code' => 'FI'],
            ['carrier_id' => '4828', 'name' => 'FI Post Nord PP', 'country_code' => 'FI'],
            
            // === FRANCIE ===
            ['carrier_id' => '4307', 'name' => 'FR Colissimo PP', 'country_code' => 'FR'],
            ['carrier_id' => '12889', 'name' => 'FR Mondial Relay PP', 'country_code' => 'FR'],
            
            // === ŘECKO ===
            ['carrier_id' => '17467', 'name' => 'GR ACS PP', 'country_code' => 'GR'],
            ['carrier_id' => '20409', 'name' => 'GR BoxNow Box', 'country_code' => 'GR'],
            ['carrier_id' => '27955', 'name' => 'GR Elta Courier PP', 'country_code' => 'GR'],
            
            // === CHORVATSKO ===
            ['carrier_id' => '35319', 'name' => 'HR BoxNow Box', 'country_code' => 'HR'],
            ['carrier_id' => '4635', 'name' => 'HR Chorvatská pošta PP', 'country_code' => 'HR'],
            ['carrier_id' => '10619', 'name' => 'HR Overseas PP', 'country_code' => 'HR'],
            
            // === MAĎARSKO ===
            ['carrier_id' => '32970', 'name' => 'HU FoxPost Box', 'country_code' => 'HU'],
            ['carrier_id' => '29760', 'name' => 'HU Maďarská pošta Box', 'country_code' => 'HU'],
            ['carrier_id' => '4539', 'name' => 'HU Maďarská pošta PP', 'country_code' => 'HU'],
            
            // === ITÁLIE ===
            ['carrier_id' => '29678', 'name' => 'IT Bartolini Box', 'country_code' => 'IT'],
            ['carrier_id' => '9104', 'name' => 'IT Bartolini PP', 'country_code' => 'IT'],
            ['carrier_id' => '32528', 'name' => 'IT Italian Post Punto Poste PP', 'country_code' => 'IT'],
            ['carrier_id' => '29660', 'name' => 'IT Italská pošta PP', 'country_code' => 'IT'],
            
            // === LITVA ===
            ['carrier_id' => '18809', 'name' => 'LT Lithuanian Post Box', 'country_code' => 'LT'],
            ['carrier_id' => '5066', 'name' => 'LT Omniva Box', 'country_code' => 'LT'],
            ['carrier_id' => '25992', 'name' => 'LT Venipak Box', 'country_code' => 'LT'],
            ['carrier_id' => '25988', 'name' => 'LT Venipak PP', 'country_code' => 'LT'],
            
            // === LOTYŠSKO ===
            ['carrier_id' => '5064', 'name' => 'LV Omniva Box', 'country_code' => 'LV'],
            ['carrier_id' => '25990', 'name' => 'LV Venipak Box', 'country_code' => 'LV'],
            ['carrier_id' => '25987', 'name' => 'LV Venipak PP', 'country_code' => 'LV'],
            
            // === NIZOZEMSKO ===
            ['carrier_id' => '8001', 'name' => 'NL DHL PP', 'country_code' => 'NL'],
            
            // === POLSKO ===
            ['carrier_id' => '3060', 'name' => 'PL InPost Paczkomaty Box', 'country_code' => 'PL'],
            ['carrier_id' => '14052', 'name' => 'PL Polská pošta PP', 'country_code' => 'PL'],
            
            // === PORTUGALSKO ===
            ['carrier_id' => '4656', 'name' => 'PT MRW PP', 'country_code' => 'PT'],
            
            // === RUMUNSKO ===
            ['carrier_id' => '32428', 'name' => 'RO FAN Box', 'country_code' => 'RO'],
            ['carrier_id' => '7455', 'name' => 'RO Sameday Box', 'country_code' => 'RO'],
            
            // === ŠVÉDSKO ===
            ['carrier_id' => '4826', 'name' => 'SE Post Nord PP', 'country_code' => 'SE'],
            
            // === SLOVINSKO ===
            ['carrier_id' => '25005', 'name' => 'SI Express One PP', 'country_code' => 'SI'],
            ['carrier_id' => '19517', 'name' => 'SI Post Box', 'country_code' => 'SI'],
            ['carrier_id' => '19516', 'name' => 'SI Post PP', 'country_code' => 'SI'],
            
            // === SLOVENSKO ===
            ['carrier_id' => '131', 'name' => 'SK Packeta PP', 'country_code' => 'SK'],
            ['carrier_id' => '7987', 'name' => 'SK Packeta Z-Point PP', 'country_code' => 'SK'],
            
            // === UKRAJINA ===
            ['carrier_id' => '3616', 'name' => 'UA Nova Poshta PP', 'country_code' => 'UA'],
        ];

        foreach ($carriers as $index => $carrier) {
            PacketaCarrier::updateOrCreate(
                [
                    'carrier_id' => $carrier['carrier_id'],
                    'country_code' => $carrier['country_code'],
                ],
                [
                    'name' => $carrier['name'],
                    'is_active' => true,
                    'sort_order' => 0, // 0 = alphabetical sorting
                ]
            );
        }

        $this->command->info('✅ Packeta carriers seeded successfully!');
        $this->command->info('📦 Total carriers: ' . count($carriers));
        $this->command->info('🚫 No HD (Home Delivery) carriers - only pick-up points and boxes');
        $this->command->info('🌍 Source: Packeta API carrier/json endpoint (pickupPoints: true)');
        $this->command->info('📅 Last updated: 2025-11-02');
        
        // Show carriers by country
        $byCountry = collect($carriers)->groupBy('country_code');
        $this->command->info("\n📊 Breakdown by country:");
        foreach ($byCountry->sortKeys() as $country => $countryCarriers) {
            $this->command->info("   {$country}: {$countryCarriers->count()} carrier(s)");
        }
    }
}
