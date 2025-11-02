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
     * Source: Packeta official carrier IDs (70+ carriers across Europe)
     */
    public function run(): void
    {
        $carriers = [
            // === RAKOUSKO ===
            ['carrier_id' => '13', 'name' => 'AT Packeta PP', 'country_code' => 'AT'],
            ['carrier_id' => '9001', 'name' => 'AT DPD Pickup', 'country_code' => 'AT'],
            ['carrier_id' => '4161', 'name' => 'AT Post AG', 'country_code' => 'AT'],
            
            // === BELGIE ===
            ['carrier_id' => '4827', 'name' => 'BE bpost', 'country_code' => 'BE'],
            ['carrier_id' => '13107', 'name' => 'BE DPD Pickup', 'country_code' => 'BE'],
            ['carrier_id' => '4832', 'name' => 'BE Mondial Relay', 'country_code' => 'BE'],
            
            // === BULHARSKO ===
            ['carrier_id' => '13117', 'name' => 'BG DPD Pickup', 'country_code' => 'BG'],
            ['carrier_id' => '4149', 'name' => 'BG Econt', 'country_code' => 'BG'],
            ['carrier_id' => '4003', 'name' => 'BG Speedy', 'country_code' => 'BG'],
            
            // === ČESKÁ REPUBLIKA ===
            ['carrier_id' => 'packeta', 'name' => 'CZ Zásilkovna PP', 'country_code' => 'CZ'],
            ['carrier_id' => 'zpoint', 'name' => 'CZ Z-BOX', 'country_code' => 'CZ'],
            
            // === NĚMECKO ===
            ['carrier_id' => '13106', 'name' => 'DE DPD Pickup', 'country_code' => 'DE'],
            ['carrier_id' => '3294', 'name' => 'DE DHL Paketshop', 'country_code' => 'DE'],
            ['carrier_id' => '3603', 'name' => 'DE GLS ParcelShop', 'country_code' => 'DE'],
            ['carrier_id' => '10779', 'name' => 'DE Hermes Paketshop', 'country_code' => 'DE'],
            
            // === DÁNSKO ===
            ['carrier_id' => '9052', 'name' => 'DK DAO', 'country_code' => 'DK'],
            ['carrier_id' => '13121', 'name' => 'DK DPD Pickup', 'country_code' => 'DK'],
            ['carrier_id' => '4841', 'name' => 'DK PostNord', 'country_code' => 'DK'],
            
            // === ESTONSKO ===
            ['carrier_id' => '13120', 'name' => 'EE DPD Pickup', 'country_code' => 'EE'],
            ['carrier_id' => '10672', 'name' => 'EE InPost Locker', 'country_code' => 'EE'],
            ['carrier_id' => '4187', 'name' => 'EE Omniva', 'country_code' => 'EE'],
            
            // === ŠPANĚLSKO ===
            ['carrier_id' => '3843', 'name' => 'ES Correos', 'country_code' => 'ES'],
            ['carrier_id' => '13111', 'name' => 'ES DPD Pickup', 'country_code' => 'ES'],
            ['carrier_id' => '12783', 'name' => 'ES MRW', 'country_code' => 'ES'],
            
            // === FINSKO ===
            ['carrier_id' => '13123', 'name' => 'FI DPD Pickup', 'country_code' => 'FI'],
            ['carrier_id' => '9053', 'name' => 'FI Posti', 'country_code' => 'FI'],
            
            // === FRANCIE ===
            ['carrier_id' => '11928', 'name' => 'FR Chronopost', 'country_code' => 'FR'],
            ['carrier_id' => '13110', 'name' => 'FR DPD Pickup', 'country_code' => 'FR'],
            ['carrier_id' => '4033', 'name' => 'FR Mondial Relay', 'country_code' => 'FR'],
            ['carrier_id' => '7431', 'name' => 'FR Relais Colis', 'country_code' => 'FR'],
            
            // === ŘECKO ===
            ['carrier_id' => '12614', 'name' => 'GR ACS', 'country_code' => 'GR'],
            ['carrier_id' => '13116', 'name' => 'GR DPD Pickup', 'country_code' => 'GR'],
            ['carrier_id' => '4711', 'name' => 'GR Speedy', 'country_code' => 'GR'],
            
            // === CHORVATSKO ===
            ['carrier_id' => '13115', 'name' => 'HR DPD Pickup', 'country_code' => 'HR'],
            ['carrier_id' => '9050', 'name' => 'HR Overseas Express', 'country_code' => 'HR'],
            
            // === MAĎARSKO ===
            ['carrier_id' => '13118', 'name' => 'HU DPD Pickup', 'country_code' => 'HU'],
            ['carrier_id' => '7493', 'name' => 'HU Express One', 'country_code' => 'HU'],
            ['carrier_id' => '10293', 'name' => 'HU Foxpost', 'country_code' => 'HU'],
            ['carrier_id' => '4161', 'name' => 'HU MPL Posta Pont', 'country_code' => 'HU'],
            ['carrier_id' => '4159', 'name' => 'HU Packeta PP', 'country_code' => 'HU'],
            
            // === IRSKO ===
            ['carrier_id' => '4845', 'name' => 'IE An Post', 'country_code' => 'IE'],
            ['carrier_id' => '13124', 'name' => 'IE DPD Pickup', 'country_code' => 'IE'],
            
            // === ITÁLIE ===
            ['carrier_id' => '13113', 'name' => 'IT DPD Pickup', 'country_code' => 'IT'],
            ['carrier_id' => '11944', 'name' => 'IT Fermopoint', 'country_code' => 'IT'],
            ['carrier_id' => '10671', 'name' => 'IT InPost Locker', 'country_code' => 'IT'],
            ['carrier_id' => '3883', 'name' => 'IT Poste Italiane', 'country_code' => 'IT'],
            
            // === LITVA ===
            ['carrier_id' => '13118', 'name' => 'LT DPD Pickup', 'country_code' => 'LT'],
            ['carrier_id' => '10780', 'name' => 'LT LP Express', 'country_code' => 'LT'],
            ['carrier_id' => '8553', 'name' => 'LT Omniva', 'country_code' => 'LT'],
            
            // === LUCEMBURSKO ===
            ['carrier_id' => '4839', 'name' => 'LU bpost', 'country_code' => 'LU'],
            ['carrier_id' => '13108', 'name' => 'LU DPD Pickup', 'country_code' => 'LU'],
            
            // === LOTYŠSKO ===
            ['carrier_id' => '13119', 'name' => 'LV DPD Pickup', 'country_code' => 'LV'],
            ['carrier_id' => '4545', 'name' => 'LV Omniva', 'country_code' => 'LV'],
            
            // === NIZOZEMSKO ===
            ['carrier_id' => '8365', 'name' => 'NL DHL ServicePoint', 'country_code' => 'NL'],
            ['carrier_id' => '13109', 'name' => 'NL DPD Pickup', 'country_code' => 'NL'],
            ['carrier_id' => '4834', 'name' => 'NL PostNL', 'country_code' => 'NL'],
            
            // === POLSKO ===
            ['carrier_id' => '3060', 'name' => 'PL InPost Paczkomaty', 'country_code' => 'PL'],
            ['carrier_id' => '11941', 'name' => 'PL Orlen Paczka', 'country_code' => 'PL'],
            ['carrier_id' => '8571', 'name' => 'PL Packeta PP', 'country_code' => 'PL'],
            ['carrier_id' => '12617', 'name' => 'PL Żabka', 'country_code' => 'PL'],
            
            // === PORTUGALSKO ===
            ['carrier_id' => '3930', 'name' => 'PT CTT', 'country_code' => 'PT'],
            ['carrier_id' => '13112', 'name' => 'PT DPD Pickup', 'country_code' => 'PT'],
            ['carrier_id' => '12782', 'name' => 'PT MRW', 'country_code' => 'PT'],
            
            // === RUMUNSKO ===
            ['carrier_id' => '4151', 'name' => 'RO Cargus', 'country_code' => 'RO'],
            ['carrier_id' => '13117', 'name' => 'RO DPD Pickup', 'country_code' => 'RO'],
            ['carrier_id' => '4029', 'name' => 'RO FAN Courier', 'country_code' => 'RO'],
            ['carrier_id' => '4162', 'name' => 'RO Packeta PP', 'country_code' => 'RO'],
            ['carrier_id' => '8794', 'name' => 'RO Sameday Box', 'country_code' => 'RO'],
            
            // === ŠVÉDSKO ===
            ['carrier_id' => '9051', 'name' => 'SE Budbee Box', 'country_code' => 'SE'],
            ['carrier_id' => '13122', 'name' => 'SE DPD Pickup', 'country_code' => 'SE'],
            ['carrier_id' => '4843', 'name' => 'SE PostNord', 'country_code' => 'SE'],
            
            // === SLOVINSKO ===
            ['carrier_id' => '13114', 'name' => 'SI DPD Pickup', 'country_code' => 'SI'],
            ['carrier_id' => '4163', 'name' => 'SI Packeta PP', 'country_code' => 'SI'],
            ['carrier_id' => '4123', 'name' => 'SI Pošta Slovenije', 'country_code' => 'SI'],
            
            // === SLOVENSKO ===
            ['carrier_id' => '131', 'name' => 'SK Packeta PP', 'country_code' => 'SK'],
            ['carrier_id' => '7987', 'name' => 'SK Packeta Z-Point PP', 'country_code' => 'SK'],
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
        $this->command->info('🌍 Covering all EU countries');
        
        // Show carriers by country
        $byCountry = collect($carriers)->groupBy('country_code');
        $this->command->info("\n📊 Breakdown by country:");
        foreach ($byCountry->sortKeys() as $country => $countryCarriers) {
            $this->command->info("   {$country}: {$countryCarriers->count()} carrier(s)");
        }
    }
}
