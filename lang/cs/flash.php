<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flash Messages - Czech
    |--------------------------------------------------------------------------
    |
    | Flash messages used across the application
    |
    */

    // Authentication & Magic Link
    'auth' => [
        'email_send_failed' => 'Nepodařilo se odeslat email. Zkuste to prosím znovu.',
        'magic_link_sent' => 'Pokud účet s tímto emailem existuje, byl na něj odeslán přihlašovací odkaz. Platnost odkazu je 15 minut.',
        'magic_link_invalid' => 'Přihlašovací odkaz je neplatný nebo vypršel. Požádejte o nový odkaz.',
        'user_not_found' => 'Uživatel nebyl nalezen.',
        'login_success' => 'Byli jste úspěšně přihlášeni!',
        'magic_link_login_success' => 'Byli jste úspěšně přihlášeni pomocí magic linku!',
        'please_login_subscription' => 'Pro aktivaci předplatného se prosím přihlaste.',
        'account_exists' => 'Účet s tímto emailem již existuje. Prosím přihlaste se.',
        'account_exists_or_use_other' => 'Účet s tímto emailem již existuje. Prosím přihlaste se nebo použijte jiný email.',
    ],

    // Subscription
    'subscription' => [
        'config_validation_error' => 'Chyba při validaci konfigurace. Zkuste to prosím znovu.',
        'order_processing' => 'Děkujeme za objednávku! Zpracováváme vaši platbu a brzy vám zašleme potvrzení na email.',
        'config_not_found' => 'Konfigurace předplatného nenalezena. Prosím nakonfigurujte si předplatné znovu.',
        'config_missing' => 'Konfigurace nenalezena.',
        'created_pending_payment' => 'Předplatné bylo vytvořeno! Po přijetí platby bude aktivováno.',
        'created_email_sent' => 'Děkujeme za objednávku! Na email :email vám zašleme platební údaje.',
        'creation_error' => 'Nastala chyba při vytváření předplatného. Zkuste to prosím znovu.',
        'activated_free' => 'Děkujeme! Vaše předplatné bylo aktivováno s 100% slevou.',
        'no_unpaid_invoice' => 'Toto předplatné nemá neuhrazenou fakturu.',
        'payment_session_error' => 'Nelze vytvořit platební session. Kontaktujte prosím podporu.',
        'payment_error' => 'Nastala chyba při vytváření platby. Zkuste to prosím později.',
        'no_active_subscription' => 'Nemáte žádné aktivní předplatné.',
        'paused' => 'Předplatné bylo pozastaveno.',
        'resumed' => 'Předplatné bylo obnoveno.',
        'cancelled' => 'Předplatné bylo zrušeno.',
        'resume_no_date' => 'Nelze obnovit předplatné - nepodařilo se určit datum další rozesílky.',
        'resume_out_of_stock' => 'Nelze obnovit předplatné pro :month - kávy (:coffees) jsou vyprodány. Pauza bude ukončena v další rozesílce.',
        'resume_not_paused' => 'Toto předplatné není pozastavené.',
        'resume_admin_locked' => 'Toto předplatné pozastavil správce a obnovit ho může jen on. Napište nám prosím na podporu.',
    ],

    // One-time box
    'onetime_box' => [
        'created_pending' => 'Objednávka jednorázového boxu byla vytvořena! Po přijetí platby bude zpracována.',
        'created_email_sent' => 'Děkujeme za objednávku! Na email :email vám zašleme platební údaje.',
        'creation_error' => 'Nastala chyba při vytváření objednávky. Zkuste to prosím znovu.',
    ],

    // Payment
    'payment' => [
        'session_error' => 'Nepodařilo se vytvořit platební session: :error Zkuste to prosím znovu níže.',
        'management_error' => 'Nepodařilo se otevřít správu platebních metod. Zkuste to prosím později.',
    ],

    // Coupon
    'coupon' => [
        'activated' => 'Kupón :code byl aktivován! Při objednávce bude automaticky aplikován.',
        'invalid' => 'Kupón ":code" není platný: :message',
    ],

    // Profile & Account
    'profile' => [
        'updated' => 'Profil byl úspěšně aktualizován.',
        'password_changed' => 'Heslo bylo úspěšně změněno.',
        'account_deleted' => 'Váš účet byl úspěšně smazán. Na zadaný email jsme vám poslali potvrzení.',
        'account_delete_error' => 'Při mazání účtu došlo k chybě. Zkuste to prosím později nebo nás kontaktujte.',
        'account_delete_error_contact' => 'Při mazání účtu došlo k chybě. Kontaktujte nás prosím na info@kavi.cz',
    ],

    // Checkout & Cart
    'checkout' => [
        'cart_empty' => 'Váš košík je prázdný.',
        'order_created' => 'Objednávka byla úspěšně vytvořena!',
        'order_error' => 'Při vytváření objednávky došlo k chybě: :error',
        'addon_limit_exceeded' => 'Překročili jste limit doplňkového zboží nebo předplatné není dostupné.',
        'order_not_unpaid' => 'Tato objednávka není v neuhrazeném stavu.',
    ],

    // Admin - Products
    'admin_product' => [
        'created' => 'Produkt byl úspěšně vytvořen.',
        'updated' => 'Produkt byl úspěšně aktualizován.',
        'deleted' => 'Produkt byl úspěšně smazán.',
    ],

    // Admin - Roasteries
    'admin_roastery' => [
        'created' => 'Pražírna byla úspěšně vytvořena.',
        'updated' => 'Pražírna byla úspěšně aktualizována.',
        'deleted' => 'Pražírna byla úspěšně smazána.',
    ],

    // Admin - Orders
    'admin_order' => [
        'status_updated' => 'Stav objednávky byl úspěšně aktualizován.',
        'address_updated' => 'Doručovací adresa byla úspěšně aktualizována.',
        'cancel_pending_only' => 'Lze zrušit pouze objednávky ve stavu "Čeká".',
        'cancelled' => 'Objednávka byla zrušena.',
    ],

    // Admin - Subscriptions
    'admin_subscription' => [
        'status_updated' => 'Stav předplatného byl úspěšně aktualizován.',
        'address_updated' => 'Dodací adresa byla úspěšně aktualizována.',
        'cancel_failed' => 'Nepodařilo se zrušit předplatné. Zkuste to prosím znovu.',
    ],

    // Admin - Config
    'admin_config' => [
        'settings_updated' => 'Nastavení bylo úspěšně aktualizováno.',
        'schedule_updated' => 'Harmonogram rozesílek byl úspěšně aktualizován.',
        'schedule_created' => 'Harmonogram pro rok :year byl úspěšně vytvořen.',
        'cannot_edit_past' => 'Nelze editovat minulou rozesílku.',
        'shipment_updated' => 'Rozesílka pro :month :year byla úspěšně aktualizována.',
    ],

    // Admin - Coupons
    'admin_coupon' => [
        'created' => 'Kupón byl úspěšně vytvořen!',
        'updated' => 'Kupón byl úspěšně aktualizován!',
        'deleted' => 'Kupón byl úspěšně smazán!',
    ],

    // Admin - Newsletter
    'admin_newsletter' => [
        'subscription_removed' => 'Přihlášení k newsletteru bylo odstraněno.',
        'synced' => 'Synchronizováno :count zákaznických emailů.',
    ],

];

