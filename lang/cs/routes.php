<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Localized Route Paths - Czech
    |--------------------------------------------------------------------------
    |
    | URL paths for Czech locale (kavi.cz)
    |
    */

    // Static pages
    'how-it-works' => 'jak-to-funguje',
    'about' => 'o-nas',
    'contact' => 'kontakt',
    'privacy-policy' => 'ochrana-osobnich-udaju',
    'terms-of-service' => 'obchodni-podminky',

    // Products
    'products' => 'produkty',
    'product' => 'produkt',

    // Roasteries
    'roasteries' => 'prazirny',
    'roastery' => 'prazirna',

    // Monthly feature
    'monthly-feature' => 'kava-mesice',

    // Blog
    'blog' => 'blog',
    'blog-show' => 'blog/{post}',

    // Subscriptions
    'subscription' => 'predplatne',
    'subscription-configurator-checkout' => 'predplatne/konfigurator/checkout',
    'subscription-checkout' => 'predplatne/pokladna',
    'subscription-confirmation' => 'predplatne/{subscription}/potvrzeni',
    'subscription-plan' => 'predplatne/{plan}',
    'subscription-subscribe' => 'predplatne/{plan}/registrovat',

    // Cart
    'cart' => 'kosik',
    'cart-add' => 'kosik/pridat/{product}',
    'cart-update' => 'kosik/aktualizovat/{product}',
    'cart-remove' => 'kosik/odebrat/{product}',
    'cart-clear' => 'kosik/vyprazdnit',

    // Checkout
    'checkout' => 'pokladna',
    'order-confirmation' => 'objednavka/{order}/potvrzeni',
    'order-pay' => 'objednavka/{order}/zaplatit',

    // Payment
    'payment-card' => 'platba/karta/{order}',

    // Coupons
    'coupon-validate' => 'kupony/validovat',
    'coupon-remove' => 'kupony/odebrat',

    // Newsletter
    'newsletter-subscribe' => 'newsletter/prihlasit',

    // Contact
    'contact-send' => 'kontakt/odeslat',

    // Review
    'review-track' => 'hodnoceni/{token}',
    'review-track-rating' => 'hodnoceni/{token}/{rating}',

    // Auth
    'login' => 'prihlaseni',
    'logout' => 'odhlaseni',
    'register' => 'registrace',
    'password-request' => 'zapomenute-heslo',
    'password-reset' => 'reset-hesla/{token}',
    'password-reset-post' => 'reset-hesla',

    // Magic link
    'magic-link-send' => 'prihlaseni-odkazem/odeslat',
    'magic-link-verify' => 'prihlaseni-odkazem/overit/{token}',

    // Dashboard
    'dashboard' => 'nastenka',
    'dashboard-profile' => 'profil',
    'dashboard-password' => 'heslo',
    'dashboard-profile-delete' => 'profil/smazat-ucet',
    'dashboard-payment-methods' => 'platebni-metody/spravovat',
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
    'dashboard-notifications' => 'notifikace',

];

