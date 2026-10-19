<?php

namespace App\Console\Commands;

use App\Services\GiftDeliveryService;
use App\Services\GiftFulfilmentService;
use Illuminate\Console\Command;

/**
 * Pojistka k vystavení dárků po zaplacení:
 * - dovystaví dárky zaplacených objednávek, které se nevystavily (výpadek, chyba),
 * - doručí certifikát kupujícímu, pokud se e-mail neodeslal,
 * - pošle certifikát obdarovanému ve dni, který zvolil kupující (8:00).
 */
class DeliverGiftCertificates extends Command
{
    protected $signature = 'gifts:deliver';

    protected $description = 'Vystaví a doručí dárkové certifikáty (pojistka + odložené odeslání obdarovanému)';

    public function handle(GiftDeliveryService $delivery, GiftFulfilmentService $fulfilment): int
    {
        $result = $delivery->runDue($fulfilment);

        if ($result['issued'] || $result['delivered']) {
            $this->info("Vystaveno: {$result['issued']}, doručováno: {$result['delivered']}");
        }

        return self::SUCCESS;
    }
}
