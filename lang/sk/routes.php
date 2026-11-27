<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Localized Route Paths - Slovak
    |--------------------------------------------------------------------------
    |
    | URL paths for Slovak locale (kavibox.sk). Cesty shodné s češtinou
    | (kosik, produkty, predplatne…) sdílejí českou routu (RouteServiceProvider::add).
    | Žádná cesta nesmí být shodná s anglickou, pokud není shodná i s českou.
    |
    */

    // Static pages
    'how-it-works' => 'ako-to-funguje',
    'about' => 'o-nas',
    'contact' => 'kontakt',
    'privacy-policy' => 'ochrana-osobnych-udajov',
    'terms-of-service' => 'obchodne-podmienky',

    // Products
    'products' => 'produkty',
    'product' => 'produkt',

    // Roasteries
    'roasteries' => 'praziarne',
    'roastery' => 'praziaren',

    // Monthly feature
    'monthly-feature' => 'kava-mesiaca',
    'landing-europe' => 'europa-v-boxe',

    // Gifts – kavibox.sk/darcek se tiskne na certifikát, po spuštění neměnit
    'gifts' => 'darceky',
    'gifts-add' => 'darceky/do-kosika',
    'gifts-remove' => 'darceky/odobrat/{line}',
    'gift-certificate' => 'darceky/certifikat/{gift}',
    'gift-redeem' => 'darcek',
    'gift-redeem-verify' => 'darcek/overit',
    'gift-redeem-prepare' => 'darcek/pokracovat',

    // Blog
    'blog' => 'blog',
    'blog-show' => 'blog/{post}',

    // Subscriptions
    'subscription' => 'predplatne',
    'subscription-configurator-checkout' => 'predplatne/konfigurator/checkout',
    'subscription-checkout' => 'predplatne/pokladna',
    'subscription-confirmation' => 'predplatne/{subscription}/potvrdenie',
    'subscription-pay-skipped' => 'predplatne/doplatit/{shipment}',
    'subscription-pay-skipped-done' => 'predplatne/doplatit/hotovo',
    'subscription-plan' => 'predplatne/{plan}',
    'subscription-subscribe' => 'predplatne/{plan}/registrovat',

    // Cart
    'cart' => 'kosik',
    'cart-add' => 'kosik/pridat/{product}',
    'cart-update' => 'kosik/aktualizovat/{product}',
    'cart-remove' => 'kosik/odobrat/{product}',
    'cart-clear' => 'kosik/vyprazdnit',

    // Checkout
    'checkout' => 'pokladna',
    'order-confirmation' => 'objednavka/{order}/potvrdenie',
    'order-pay' => 'objednavka/{order}/zaplatit',

    // Payment
    'payment-card' => 'platba/karta/{order}',

    // Coupons
    'coupon-validate' => 'kupony/overit',
    'coupon-remove' => 'kupony/odobrat',

    // Newsletter
    'newsletter-subscribe' => 'newsletter/prihlasit',

    // Contact
    'contact-send' => 'kontakt/odoslat',

    // Review
    'review-track' => 'hodnotenie/{token}',
    'review-track-rating' => 'hodnotenie/{token}/{rating}',

    // Auth
    'login' => 'prihlasenie',
    'logout' => 'odhlasenie',
    'register' => 'registracia',
    'password-request' => 'zabudnute-heslo',
    'password-reset' => 'obnova-hesla/{token}',
    'password-reset-post' => 'obnova-hesla',

    // Magic link
    'magic-link-send' => 'prihlasenie-odkazom/odoslat',
    'magic-link-verify' => 'prihlasenie-odkazom/overit/{token}',

    // Dashboard
    'dashboard' => 'moj-ucet',
    'dashboard-profile' => 'profil',
    'dashboard-profile-company' => 'profil/firma',
    'dashboard-password' => 'heslo',
    'dashboard-profile-delete' => 'profil/zmazat-ucet',
    'dashboard-payment-methods' => 'platobne-metody/spravovat',
    'dashboard-orders' => 'objednavky',
    'dashboard-order-detail' => 'objednavka/{order}',
    'dashboard-order-invoice' => 'objednavka/{order}/faktura',
    'dashboard-subscription' => 'predplatne',
    'dashboard-subscription-pause' => 'predplatne/pause',
    'dashboard-subscription-resume' => 'predplatne/resume',
    'dashboard-subscription-cancel' => 'predplatne/cancel',
    'dashboard-subscription-pay' => 'predplatne/{subscription}/zaplatit',
    'dashboard-subscription-invoice' => 'predplatne/platba/{payment}/faktura',
    'dashboard-subscription-packeta' => 'predplatne/update-packeta',
    'dashboard-subscription-configure' => 'predplatne/{subscription}/zmena',
    'dashboard-subscription-configure-cancel' => 'predplatne/{subscription}/zmena/zrusit',
    'dashboard-notifications' => 'notifikacie',
    'dashboard-affiliate' => 'affiliate',

];
