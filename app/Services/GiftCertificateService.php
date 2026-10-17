<?php

namespace App\Services;

use App\Models\GiftPurchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * PDF certifikát dárku (A5 na šířku, přední + zadní strana) s QR kódem.
 *
 * dompdf je čisté PHP, na serveru nepotřebuje Chromium ani Node. Šablona proto
 * používá jen absolutní pozice a tabulky (resources/views/gifts/certificate).
 * PDF se ukládá do storage/app/gifts (v .gitignore) a když soubor chybí,
 * vyrenderuje se znovu, takže smazání při deployi nic nerozbije.
 */
class GiftCertificateService
{
    public function render(GiftPurchase $gift): string
    {
        $data = $gift->certificateData();

        return $this->renderData($data);
    }

    /**
     * @param  array<string, mixed>  $data  výstup GiftPurchase::certificateData()
     */
    public function renderData(array $data): string
    {
        File::ensureDirectoryExists(storage_path('app/gifts/font-cache'));

        return Pdf::setOptions([
            'dpi' => 96,
            'default_font' => 'Roboto',
            'enable_remote' => false,
            'enable_font_subsetting' => true,
            'font_dir' => storage_path('app/gifts/font-cache'),
            'font_cache' => storage_path('app/gifts/font-cache'),
            // Soubory jen z public (fotky, logo) a z fontů pro PDF
            'chroot' => [public_path(), resource_path('fonts/pdf')],
        ], true)
            ->loadView('gifts.certificate.pdf', [
                'c' => $data,
                'qr' => $this->qrDataUri($data['redeem_url']),
            ])
            ->setPaper('a5', 'landscape')
            ->output();
    }

    /**
     * Cesta k PDF na disku "local". Chybějící soubor se vyrenderuje znovu.
     */
    public function ensurePdf(GiftPurchase $gift): string
    {
        $disk = Storage::disk('local');

        if ($gift->pdf_path && $disk->exists($gift->pdf_path)) {
            return $gift->pdf_path;
        }

        $path = 'gifts/' . $gift->id . '/' . $this->fileName($gift);
        $disk->put($path, $this->render($gift));
        $gift->forceFill(['pdf_path' => $path])->save();

        return $path;
    }

    /**
     * Vyrenderuje PDF znovu (např. po opravě jména v adminu)
     */
    public function regenerate(GiftPurchase $gift): string
    {
        if ($gift->pdf_path) {
            Storage::disk('local')->delete($gift->pdf_path);
        }
        $gift->forceFill(['pdf_path' => null])->save();

        return $this->ensurePdf($gift);
    }

    public function fileName(GiftPurchase $gift): string
    {
        $prefix = $gift->locale === 'en' ? 'kavi-gift-' : 'kavi-darek-';

        return $prefix . strtolower($gift->code ?? (string) $gift->id) . '.pdf';
    }

    /**
     * QR kód jako PNG data URI (4× rozlišení kvůli tisku, na certifikátu má 92 px)
     */
    public function qrDataUri(string $url): string
    {
        $result = (new Builder(
            writer: new PngWriter(),
            data: $url,
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 368,
            margin: 0,
        ))->build();

        return $result->getDataUri();
    }
}
