<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class GoogleMerchantFeedController extends Controller
{
    /**
     * Google Merchant Center XML feed
     * Detects domain and generates feed in appropriate language/currency
     */
    public function index(): Response
    {
        $host = request()->getHost();
        $isEur = str_contains($host, 'kavibox.com') || str_contains($host, 'kavibox.local');
        
        $locale = $isEur ? 'en' : 'cs';
        $currency = $isEur ? 'EUR' : 'CZK';
        $baseUrl = $isEur ? 'https://kavibox.com' : 'https://kavi.cz';
        $shopName = $isEur ? 'Kavibox' : 'Kavi';
        
        // Set locale for translations and currency for price calculations
        app()->setLocale($locale);
        session(['currency' => $currency, 'region' => $isEur ? 'com' : 'cz']);

        // Get active products with stock > 0, valid price in currency, excluding coffee of month
        $products = Product::with('roastery')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('is_coffee_of_month', false)
            ->when($isEur, function ($query) {
                return $query->whereNotNull('price_eur')->where('price_eur', '>', 0);
            }, function ($query) {
                return $query->whereNotNull('price')->where('price', '>', 0);
            })
            ->orderBy('id')
            ->get();

        $xml = $this->generateXml($products, $locale, $currency, $baseUrl, $shopName);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Generate RSS 2.0 XML for Google Merchant Center
     */
    private function generateXml($products, string $locale, string $currency, string $baseUrl, string $shopName): string
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = true;

        // Create RSS root element with Google namespace
        $rss = $dom->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $rss->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
        $dom->appendChild($rss);

        // Create channel
        $channel = $dom->createElement('channel');
        $rss->appendChild($channel);

        // Channel metadata
        $this->addElement($dom, $channel, 'title', $shopName);
        $this->addElement($dom, $channel, 'link', $baseUrl);
        $this->addElement($dom, $channel, 'description', $locale === 'en' 
            ? 'Premium specialty coffee from European roasteries' 
            : 'Prémiová výběrová káva z evropských pražíren');

        // Add products
        foreach ($products as $product) {
            $item = $this->createItem($dom, $product, $locale, $currency, $baseUrl);
            $channel->appendChild($item);
        }

        return $dom->saveXML();
    }

    /**
     * Create item element for a product
     */
    private function createItem(\DOMDocument $dom, Product $product, string $locale, string $currency, string $baseUrl): \DOMElement
    {
        $item = $dom->createElement('item');

        // Required attributes
        // g:id - Unique product identifier
        $this->addGoogleElement($dom, $item, 'id', (string) $product->id);

        // g:title - Product name with roastery
        $productName = $locale === 'en' && !empty($product->name_en) ? $product->name_en : $product->name;
        $title = $productName;
        if ($product->roastery) {
            $roasteryName = $locale === 'en' && !empty($product->roastery->name_en) 
                ? $product->roastery->name_en 
                : $product->roastery->name;
            $title = $productName . ' - ' . $roasteryName;
        }
        $this->addGoogleElement($dom, $item, 'title', $title);

        // g:description - Product description
        $description = $locale === 'en' && !empty($product->description_en) 
            ? $product->description_en 
            : $product->description;
        $this->addGoogleElement($dom, $item, 'description', $this->cleanDescription($description));

        // g:link - Product URL
        $productPath = $locale === 'en' ? '/product/' : '/produkt/';
        $this->addGoogleElement($dom, $item, 'link', $baseUrl . $productPath . $product->slug);

        // g:image_link - Main product image
        if ($product->image) {
            $imageUrl = $this->getAbsoluteImageUrl($product->image, $baseUrl);
            $this->addGoogleElement($dom, $item, 'image_link', $imageUrl);
        }

        // g:additional_image_link - Additional images
        if (!empty($product->images) && is_array($product->images)) {
            foreach (array_slice($product->images, 0, 10) as $image) {
                $imageUrl = $this->getAbsoluteImageUrl($image, $baseUrl);
                $this->addGoogleElement($dom, $item, 'additional_image_link', $imageUrl);
            }
        }

        // g:price - Original product price with currency
        $originalPrice = $currency === 'EUR' ? $product->price_eur : $product->price;
        $this->addGoogleElement($dom, $item, 'price', number_format((float) $originalPrice, 2, '.', '') . ' ' . $currency);

        // g:sale_price - Sale price if product is on sale
        if ($product->isOnSale()) {
            $salePrice = $product->getSalePrice();
            $this->addGoogleElement($dom, $item, 'sale_price', number_format((float) $salePrice, 2, '.', '') . ' ' . $currency);
        }

        // g:availability - Stock status
        $this->addGoogleElement($dom, $item, 'availability', 'in_stock');

        // g:condition - Product condition (always new for coffee)
        $this->addGoogleElement($dom, $item, 'condition', 'new');

        // g:brand - Roastery name
        if ($product->roastery) {
            $roasteryName = $locale === 'en' && !empty($product->roastery->name_en) 
                ? $product->roastery->name_en 
                : $product->roastery->name;
            $this->addGoogleElement($dom, $item, 'brand', $roasteryName);
        }

        // g:product_type - Category hierarchy
        $productType = $this->getProductType($product->category, $locale);
        if ($productType) {
            $this->addGoogleElement($dom, $item, 'product_type', $productType);
        }

        // g:google_product_category - Google taxonomy ID
        $googleCategory = $this->getGoogleCategory($product->category, $locale);
        $this->addGoogleElement($dom, $item, 'google_product_category', $googleCategory);

        return $item;
    }

    /**
     * Add element with Google namespace (g:)
     */
    private function addGoogleElement(\DOMDocument $dom, \DOMElement $parent, string $name, string $value): void
    {
        $element = $dom->createElement('g:' . $name);
        $element->appendChild($dom->createCDATASection($value));
        $parent->appendChild($element);
    }

    /**
     * Add regular element
     */
    private function addElement(\DOMDocument $dom, \DOMElement $parent, string $name, string $value): void
    {
        $element = $dom->createElement($name);
        $element->appendChild($dom->createCDATASection($value));
        $parent->appendChild($element);
    }

    /**
     * Clean description from HTML tags
     */
    private function cleanDescription(?string $description): string
    {
        if (empty($description)) {
            return '';
        }

        $clean = strip_tags($description);
        $clean = preg_replace('/\s+/', ' ', $clean);
        
        return trim($clean);
    }

    /**
     * Get absolute image URL
     */
    private function getAbsoluteImageUrl(string $imagePath, string $baseUrl): string
    {
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            return $imagePath;
        }

        $imagePath = ltrim($imagePath, '/');
        return $baseUrl . '/' . $imagePath;
    }

    /**
     * Get product type hierarchy based on categories
     */
    private function getProductType(?array $categories, string $locale): string
    {
        if (empty($categories)) {
            return $locale === 'en' 
                ? 'Food & Beverages > Coffee > Whole Bean Coffee' 
                : 'Jídlo a nápoje > Káva > Zrnková káva';
        }

        if (in_array('accessories', $categories)) {
            return $locale === 'en'
                ? 'Home & Garden > Kitchen > Coffee Accessories'
                : 'Dům a zahrada > Kuchyně > Příslušenství na kávu';
        }

        if (in_array('espresso', $categories)) {
            return $locale === 'en'
                ? 'Food & Beverages > Coffee > Espresso Coffee Beans'
                : 'Jídlo a nápoje > Káva > Espresso zrnková káva';
        }

        if (in_array('filter', $categories)) {
            return $locale === 'en'
                ? 'Food & Beverages > Coffee > Filter Coffee Beans'
                : 'Jídlo a nápoje > Káva > Filtrovaná zrnková káva';
        }

        if (in_array('decaf', $categories)) {
            return $locale === 'en'
                ? 'Food & Beverages > Coffee > Decaf Coffee Beans'
                : 'Jídlo a nápoje > Káva > Bezkofeinová zrnková káva';
        }

        return $locale === 'en'
            ? 'Food & Beverages > Coffee > Whole Bean Coffee'
            : 'Jídlo a nápoje > Káva > Zrnková káva';
    }

    /**
     * Get Google Product Category ID
     * @see https://www.google.com/basepages/producttype/taxonomy.en-US.txt
     */
    private function getGoogleCategory(?array $categories, string $locale): string
    {
        // Check if accessories
        if (!empty($categories) && in_array('accessories', $categories)) {
            // Kitchen > Coffee Makers & Espresso Machines > Coffee Maker & Espresso Machine Accessories
            return '3988';
        }

        // Default: Food, Beverages & Tobacco > Beverages > Coffee
        return '1868';
    }
}

