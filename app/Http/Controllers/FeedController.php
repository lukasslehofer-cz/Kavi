<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShippingRate;
use Illuminate\Http\Response;

class FeedController extends Controller
{
    /**
     * Mapování interních kategorií na Heureka kategorie
     */
    private array $categoryMapping = [
        'espresso' => 'Jídlo a nápoje | Káva | Zrnková káva',
        'filter' => 'Jídlo a nápoje | Káva | Zrnková káva',
        'decaf' => 'Jídlo a nápoje | Káva | Zrnková káva',
        'accessories' => 'Elektronika | Kuchyňské spotřebiče | Příslušenství na kávu',
    ];

    /**
     * Generování XML feedu pro Heureku
     */
    public function heureka(): Response
    {
        $products = Product::with('roastery')
            ->forShop()
            ->orderBy('id')
            ->get();

        $shippingRate = ShippingRate::getForCountry('CZ');

        $xml = $this->generateXml($products, $shippingRate);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Generování XML dokumentu
     */
    private function generateXml($products, ?ShippingRate $shippingRate): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        $shop = $dom->createElement('SHOP');
        $dom->appendChild($shop);

        foreach ($products as $product) {
            $shopItem = $this->createShopItem($dom, $product, $shippingRate);
            $shop->appendChild($shopItem);
        }

        return $dom->saveXML();
    }

    /**
     * Vytvoření SHOPITEM elementu pro produkt
     */
    private function createShopItem(\DOMDocument $dom, Product $product, ?ShippingRate $shippingRate): \DOMElement
    {
        $shopItem = $dom->createElement('SHOPITEM');

        // Povinné elementy
        $this->addElement($dom, $shopItem, 'ITEM_ID', (string) $product->id);
        $this->addElement($dom, $shopItem, 'PRODUCTNAME', $product->name);
        $this->addElement($dom, $shopItem, 'PRODUCT', $product->name);
        $this->addElement($dom, $shopItem, 'DESCRIPTION', $this->cleanDescription($product->description));
        $this->addElement($dom, $shopItem, 'URL', localizedRoute('products.show', $product));
        
        // Obrázek
        if ($product->image) {
            $this->addElement($dom, $shopItem, 'IMGURL', asset($product->image));
        }

        // Alternativní obrázky
        if (!empty($product->images) && is_array($product->images)) {
            foreach ($product->images as $image) {
                $this->addElement($dom, $shopItem, 'IMGURL_ALTERNATIVE', asset($image));
            }
        }

        // Cena s DPH
        $this->addElement($dom, $shopItem, 'PRICE_VAT', number_format((float) $product->price, 2, '.', ''));

        // Výrobce (pražírna)
        if ($product->roastery) {
            $this->addElement($dom, $shopItem, 'MANUFACTURER', $product->roastery->name);
        }

        // Kategorie
        $categoryText = $this->getCategoryText($product->category);
        if ($categoryText) {
            $this->addElement($dom, $shopItem, 'CATEGORYTEXT', $categoryText);
        }

        // Dostupnost (0 = skladem, jinak počet dní)
        $deliveryDate = $product->stock > 0 ? 0 : 14;
        $this->addElement($dom, $shopItem, 'DELIVERY_DATE', (string) $deliveryDate);

        // Doprava
        if ($shippingRate && $shippingRate->price_czk > 0) {
            $delivery = $dom->createElement('DELIVERY');
            $this->addElement($dom, $delivery, 'DELIVERY_ID', 'ZASILKOVNA');
            $this->addElement($dom, $delivery, 'DELIVERY_PRICE', number_format((float) $shippingRate->price_czk, 2, '.', ''));
            $shopItem->appendChild($delivery);
        }

        return $shopItem;
    }

    /**
     * Přidání elementu do DOM
     */
    private function addElement(\DOMDocument $dom, \DOMElement $parent, string $name, string $value): void
    {
        $element = $dom->createElement($name);
        $element->appendChild($dom->createCDATASection($value));
        $parent->appendChild($element);
    }

    /**
     * Vyčištění popisu od HTML tagů a zbytečných znaků
     */
    private function cleanDescription(?string $description): string
    {
        if (empty($description)) {
            return '';
        }

        // Odstranění HTML tagů
        $clean = strip_tags($description);
        
        // Normalizace bílých znaků
        $clean = preg_replace('/\s+/', ' ', $clean);
        
        // Trim
        return trim($clean);
    }

    /**
     * Získání Heureka kategorie z interních kategorií produktu
     */
    private function getCategoryText(?array $categories): string
    {
        if (empty($categories)) {
            return 'Jídlo a nápoje | Káva | Zrnková káva';
        }

        // Priorita: accessories má vlastní kategorii, jinak káva
        if (in_array('accessories', $categories)) {
            return $this->categoryMapping['accessories'];
        }

        // Pro všechny kávové kategorie vrátíme kávu
        foreach ($categories as $category) {
            if (isset($this->categoryMapping[$category])) {
                return $this->categoryMapping[$category];
            }
        }

        return 'Jídlo a nápoje | Káva | Zrnková káva';
    }
}

