<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checkout Page Translations
    |--------------------------------------------------------------------------
    */

    // Page titles
    'page_title' => 'Pokladna - KAVI.cz',
    'page_title_subscription' => 'Pokladna - Kávové předplatné',
    'title' => 'Dokončení objednávky',
    'title_subscription' => 'Dokončení objednávky předplatného',
    'subtitle' => 'Ještě pár informací a vaše káva bude na cestě k vám',
    'subtitle_subscription' => 'Ještě pár informací a vaše káva bude pravidelně na cestě k vám',
    
    // Order summary
    'order_summary' => 'Souhrn objednávky',
    'subscription_summary' => 'Souhrn předplatného',
    'subtotal' => 'Mezisoučet',
    'subtotal_without_vat' => 'Mezisoučet (bez DPH)',
    'shipping' => 'Doprava',
    'shipping_free' => 'Zdarma',
    'shipping_free_subscription' => 'Zdarma (předplatné)',
    'shipping_free_coupon' => 'Zdarma (kupón)',
    'shipping_free_digital' => 'Zdarma (digitální produkt)',
    'shipping_calculating' => 'Počítám...',
    'shipping_at_checkout' => 'Bude dopočítána v pokladně',
    'shipping_unavailable' => 'Nedostupné',
    'shipping_error' => 'Chyba',
    'discount' => 'Sleva',
    'vat' => 'DPH (21%)',
    'total' => 'Celkem',
    'total_per_month' => 'Celkem / měsíc',
    'incl_vat' => '(včetně DPH)',
    'plus_shipping' => '+ doprava',
    
    // Contact information
    'contact_info' => 'Kontaktní údaje',
    'have_account' => 'Máte již účet?',
    'login_faster' => 'Přihlaste se pro rychlejší dokončení objednávky nebo vám pošleme přihlašovací odkaz.',
    'login_faster_subscription' => 'Přihlaste se pro rychlejší dokončení objednávky.',
    'login' => 'Přihlásit se',
    'send_magic_link' => 'Poslat přihlašovací odkaz',
    'magic_link_title' => 'Poslat přihlašovací odkaz',
    'magic_link_description' => 'Zadejte váš email a my vám pošleme přihlašovací odkaz.',
    'email_confirmation_note' => 'Na tento email vám zašleme potvrzení a odkaz pro dokončení registrace.',
    'phone_helps' => 'Telefon pomůže doručovací službě při řešení případných problémů.',
    
    // Billing address
    'billing_address' => 'Fakturační adresa',
    
    // Form fields
    'fields' => [
        'name' => 'Jméno a příjmení',
        'email' => 'Email',
        'phone' => 'Telefon',
        'phone_optional' => 'Telefon (volitelné)',
        'street' => 'Ulice a číslo popisné',
        'street_placeholder' => 'Např. Karlova 123',
        'city' => 'Město',
        'city_placeholder' => 'Např. Praha',
        'postal_code' => 'PSČ',
        'postal_code_placeholder' => '123 45',
        'country' => 'Země',
        'select_country' => 'Vyberte zemi',
        'notes' => 'Poznámka',
        'notes_optional' => '(volitelné)',
        'notes_placeholder' => 'Např. \'Prosím zvonit na 2. patro\' nebo \'Nechat u vrátnice\'',
    ],
    
    // Pickup point
    'pickup_point' => [
        'title' => 'Výběr výdejního místa',
        'selected' => 'Vybrané výdejní místo:',
        'change' => 'Změnit',
        'select' => 'Vybrat výdejní místo Zásilkovna',
        'info' => 'Káva vám bude doručena na vybrané výdejní místo',
    ],
    
    // Digital product
    'digital_product' => [
        'title' => 'Digitální produkt',
        'description' => 'Váš produkt bude doručen elektronicky na zadaný email. Není potřeba vybírat výdejní místo.',
    ],
    
    // Subscription addon
    'subscription_addon' => [
        'title' => 'Možnost dopravy s předplatným',
        'checkbox_label' => 'Zařadit do příští rozesílky předplatného',
        'free_shipping' => 'Doprava zdarma 🎉',
        'capacity_full' => '⚠️ Kapacita doplňkového zboží je vyčerpána pro všechna vaše předplatná.',
        'select_subscription' => 'Vyberte předplatné:',
        'capacity_label' => 'Kapacita doplňkového zboží:',
        'slots_available' => 'volných slotů',
        'used_slot' => 'Použitý slot',
        'cart_slot' => 'Košík',
        'free_slot' => 'Volný slot',
        'planned_delivery' => '📦 Plánované doručení:',
        'capacity_warning' => '❌ Kapacita doplňkového zboží byla naplněna pro toto předplatné.',
        'try_another' => 'Zkuste vybrat jiné předplatné.',
        'cart_has' => 'Košík obsahuje',
        'items' => 'kusů',
        'but_only' => 'ale k dispozici jsou pouze',
        'slot_available' => 'slot',
        'slots_available_plural' => 'sloty',
        'info' => 'Pokud zaškrtnete tuto možnost, zboží bude odesláno společně s vaším předplatným a neplatíte dopravné. Maximálně můžete přidat 3 kusy zboží na jednu rozesílku. <strong>Zboží bude doručeno na výdejní místo nastavené u vašeho předplatného.</strong>',
    ],
    
    // Payment
    'payment' => [
        'title' => 'Způsob platby',
        'card' => 'Platební kartou',
        'card_description' => 'Po odeslání objednávky budete přesměrováni na bezpečnou platební bránu',
        'we_accept' => 'Akceptujeme:',
    ],
    
    // Coupon
    'coupon' => [
        'title' => 'Mám slevový kupón',
        'placeholder' => 'SLEVOVYKOD',
        'apply' => 'Použít',
        'remove' => 'Odebrat',
        'applied' => 'Kupón aplikován',
        'discount_label' => 'Sleva',
    ],
    
    // 100% discount notice
    'full_discount' => [
        'title' => '🎉 100% sleva!',
        'description' => 'Vaše předplatné je <strong>zcela zdarma</strong> díky kupónu :code. Po dokončení objednávky bude předplatné okamžitě aktivováno.',
    ],
    
    // Subscription details
    'subscription' => [
        'quantity' => 'Množství:',
        'bags' => 'balení',
        'coffee_type' => 'Typ kávy:',
        'espresso' => 'Espresso',
        'filter' => 'Filtr',
        'mix' => 'Kombinace',
        'incl_decaf' => '(vč. 1× decaf)',
        'frequency' => 'Frekvence:',
        'every_month' => 'Každý měsíc',
        'every_2_months' => 'Jednou za 2 měsíce',
        'every_3_months' => 'Jednou za 3 měsíce',
        'bags_without_vat' => 'balení kávy (bez DPH):',
    ],
    
    // Delivery info
    'delivery_info' => [
        'title' => 'Informace o dodání',
        'shipping_note' => 'Rozesílka probíhá vždy <strong>20. den v měsíci</strong>. Objednávky do 15. v měsíci jsou zahrnuty v aktuální rozesílce, objednávky od 16. dne jsou zahrnuty až v následující rozesílce.',
    ],
    
    // Buttons
    'buttons' => [
        'complete_order' => 'Dokončit objednávku',
        'back_to_cart' => 'Zpět do košíku',
        'back_to_configurator' => 'Zpět na konfigurátor',
    ],
    
    // Terms
    'terms' => [
        'agree' => 'Souhlasím s',
        'terms_of_service' => 'obchodními podmínkami',
        'and' => 'a',
        'privacy_policy' => 'zásadami ochrany osobních údajů',
    ],
    
    // Trust badges
    'trust' => [
        'secure_payment' => 'Bezpečná platba',
        'eco_packaging' => 'Ekologické balení',
        'coffee_from_europe' => 'Káva z celé Evropy',
        'no_commitment' => 'Bez závazků - kdykoli zrušte',
        'fresh_coffee' => 'Čerstvě pražená káva',
        'free_shipping_always' => 'Doprava vždy zdarma',
    ],
    
    // Errors
    'errors' => [
        'country_unavailable' => 'Do vybrané země momentálně nedoručujeme.',
        'subscription_country_unavailable' => 'Do vybrané země momentálně nedoručujeme předplatné.',
    ],
    
    // Messages
    'messages' => [
        'cart_empty' => 'Váš košík je prázdný.',
        'payment_cancelled' => 'Platba byla zrušena. Můžete pokračovat v objednávce nebo upravit košík.',
    ],

];
