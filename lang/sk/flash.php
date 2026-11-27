<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Flash Messages - Slovak
    |--------------------------------------------------------------------------
    |
    | Flash messages used across the application
    |
    */

    // Authentication & Magic Link
    'auth' => [
        'email_send_failed' => 'Nepodarilo sa odoslať e-mail. Skúste to prosím znova.',
        'magic_link_sent' => 'Ak účet s týmto e-mailom existuje, bol naň odoslaný prihlasovací odkaz. Platnosť odkazu je 15 minút.',
        'magic_link_invalid' => 'Prihlasovací odkaz je neplatný alebo jeho platnosť vypršala. Požiadajte o nový odkaz.',
        'user_not_found' => 'Používateľ sa nenašiel.',
        'login_success' => 'Boli ste úspešne prihlásení!',
        'magic_link_login_success' => 'Boli ste úspešne prihlásení pomocou magic linku!',
        'please_login_subscription' => 'Na aktiváciu predplatného sa prosím prihláste.',
        'account_exists' => 'Účet s týmto e-mailom už existuje. Prosím, prihláste sa.',
        'account_exists_or_use_other' => 'Účet s týmto e-mailom už existuje. Prosím, prihláste sa alebo použite iný e-mail.',
    ],

    // Subscription
    'subscription' => [
        'config_validation_error' => 'Chyba pri overovaní konfigurácie. Skúste to prosím znova.',
        'order_processing' => 'Ďakujeme za objednávku! Spracúvame vašu platbu a čoskoro vám pošleme potvrdenie na e-mail.',
        'config_not_found' => 'Konfigurácia predplatného sa nenašla. Prosím, nakonfigurujte si predplatné znova.',
        'config_missing' => 'Konfigurácia sa nenašla.',
        'created_pending_payment' => 'Predplatné bolo vytvorené! Po prijatí platby bude aktivované.',
        'created_email_sent' => 'Ďakujeme za objednávku! Na e-mail :email vám pošleme platobné údaje.',
        'creation_error' => 'Pri vytváraní predplatného nastala chyba. Skúste to prosím znova.',
        'activated_free' => 'Ďakujeme! Vaše predplatné bolo aktivované so 100% zľavou.',
        'no_unpaid_invoice' => 'Toto predplatné nemá neuhradenú faktúru.',
        'payment_session_error' => 'Nie je možné vytvoriť platobnú reláciu. Kontaktujte prosím podporu.',
        'payment_error' => 'Pri vytváraní platby nastala chyba. Skúste to prosím neskôr.',
        'no_active_subscription' => 'Nemáte žiadne aktívne predplatné.',
        'paused' => 'Predplatné bolo pozastavené.',
        'pause_extended' => 'Pauza bola predĺžená.',
        'pause_not_allowed' => 'Toto predplatné teraz nie je možné pozastaviť.',
        'pause_billing_in_progress' => 'Práve spracúvame platbu za ďalší box. Pozastaviť predplatné bude možné zajtra.',
        'resumed' => 'Predplatné bolo obnovené.',
        'cancelled' => 'Predplatné bolo zrušené.',
        'resume_no_date' => 'Predplatné nie je možné obnoviť - nepodarilo sa určiť dátum ďalšej zásielky.',
        'resume_out_of_stock' => 'Predplatné nie je možné obnoviť na :month - kávy (:coffees) sú vypredané. Pauza sa skončí pri ďalšej zásielke.',
        'resume_not_paused' => 'Toto predplatné nie je pozastavené.',
        'resume_admin_locked' => 'Toto predplatné pozastavil správca a obnoviť ho môže iba on. Napíšte nám prosím na podporu.',
        'resume_payment_failure' => 'Predplatné je pozastavené pre neúspešnú platbu a po vynechanej zásielke bude pokračovať samo. Pred ďalšou platbou prosím vymeňte platobnú kartu.',
        'not_found' => 'Predplatné sa nenašlo.',
        'pickup_point_updated' => 'Výdajné miesto bolo úspešne zmenené.',
        'pickup_point_carrier_not_allowed' => 'Toto výdajné miesto nie je možné pre vaše predplatné použiť. Vyberte si prosím iné.',

        // Zmena konfigurácie existujúceho predplatného
        'config_changed' => 'Konfigurácia predplatného bola zmenená.',
        'config_change_scheduled' => 'Zmena konfigurácie je naplánovaná.',
        'config_change_applies_from' => 'Platí od boxu :date.',
        'config_change_scheduled_cancelled' => 'Naplánovaná zmena konfigurácie bola zrušená.',
        'config_change_nothing_scheduled' => 'Nie je naplánovaná žiadna zmena konfigurácie.',
        'config_unchanged' => 'Konfigurácia zostáva bez zmeny.',
        'config_change_not_allowed' => 'Pri tomto predplatnom teraz nie je možné zmeniť konfiguráciu.',
        'config_change_contact_support' => 'Toto predplatné nie je možné zmeniť v účte. Napíšte nám prosím na podporu, upravíme ho ručne.',
        'config_change_unpaid' => 'Najprv prosím uhraďte nezaplatenú platbu. Konfiguráciu bude možné zmeniť hneď potom.',
        'config_change_payment_failure' => 'Predplatné je pozastavené pre neúspešnú platbu. Konfiguráciu bude možné zmeniť, keď bude opäť aktívne.',
        'config_change_billing_in_progress' => 'Práve spracúvame platbu za ďalší box. Konfiguráciu bude možné zmeniť zajtra.',
        'config_change_out_of_stock' => 'Pre zásielku :date už táto kombinácia nie je k dispozícii (:coffees). Zvoľte si prosím inú.',
        'config_change_unavailable' => 'Pre zásielku :date túto kombináciu nevieme zostaviť. Zvoľte si prosím inú.',
        'config_change_addons_attached' => 'K najbližšiemu boxu máte pribalenú objednávku. Frekvenciu bude možné zmeniť po jeho odoslaní.',
        'config_change_frequency_paused' => 'Frekvenciu bude možné zmeniť po obnovení predplatného.',
    ],

    // One-time box
    'onetime_box' => [
        'created_pending' => 'Objednávka jednorazového boxu bola vytvorená! Po prijatí platby bude spracovaná.',
        'created_email_sent' => 'Ďakujeme za objednávku! Na e-mail :email vám pošleme platobné údaje.',
        'creation_error' => 'Pri vytváraní objednávky nastala chyba. Skúste to prosím znova.',
    ],

    // Payment
    'payment' => [
        'session_error' => 'Nepodarilo sa vytvoriť platobnú reláciu: :error Skúste to prosím znova nižšie.',
        'management_error' => 'Nepodarilo sa otvoriť správu platobných metód. Skúste to prosím neskôr.',
    ],

    // Coupon
    'coupon' => [
        'activated' => 'Kupón :code bol aktivovaný! Pri objednávke sa automaticky uplatní.',
        'invalid' => 'Kupón ":code" nie je platný: :message',
    ],

    // Profile & Account
    'profile' => [
        'updated' => 'Profil bol úspešne aktualizovaný.',
        'pickup_point_applied' => 'Výdajné miesto bolo zmenené aj pri vašom predplatnom.',
        'pickup_point_not_applied' => 'Pri niektorom z vašich predplatných nie je možné nové výdajné miesto použiť. Vyberte ho prosím v sekcii Predplatné pri danom predplatnom.',
        'company_updated' => 'Firemné údaje sú uložené. Použijú sa pri ďalších nákupoch aj na faktúrach vašich aktívnych predplatných.',
        'company_removed' => 'Firemné údaje boli odstránené. Ďalšie faktúry vystavíme bez firmy.',
        'password_changed' => 'Heslo bolo úspešne zmenené.',
        'account_deleted' => 'Váš účet bol úspešne zmazaný. Na zadaný e-mail sme vám poslali potvrdenie.',
        'account_delete_error' => 'Pri mazaní účtu došlo k chybe. Skúste to prosím neskôr alebo nás kontaktujte.',
        'account_delete_error_contact' => 'Pri mazaní účtu došlo k chybe. Kontaktujte nás prosím na info@kavibox.sk',
    ],

    // Checkout & Cart
    'checkout' => [
        'cart_empty' => 'Váš košík je prázdny.',
        'order_created' => 'Objednávka bola úspešne vytvorená!',
        'order_error' => 'Pri vytváraní objednávky došlo k chybe: :error',
        'addon_limit_exceeded' => 'Prekročili ste limit doplnkového tovaru alebo predplatné nie je dostupné.',
        'order_not_unpaid' => 'Táto objednávka nie je v stave neuhradená.',
    ],

    // Admin - Products
    'admin_product' => [
        'created' => 'Produkt bol úspešne vytvorený.',
        'updated' => 'Produkt bol úspešne aktualizovaný.',
        'deleted' => 'Produkt bol úspešne zmazaný.',
    ],

    // Admin - Roasteries
    'admin_roastery' => [
        'created' => 'Pražiareň bola úspešne vytvorená.',
        'updated' => 'Pražiareň bola úspešne aktualizovaná.',
        'deleted' => 'Pražiareň bola úspešne zmazaná.',
    ],

    // Admin - Orders
    'admin_order' => [
        'status_updated' => 'Stav objednávky bol úspešne aktualizovaný.',
        'address_updated' => 'Doručovacia adresa bola úspešne aktualizovaná.',
        'cancel_pending_only' => 'Zrušiť je možné iba objednávky v stave "Čaká".',
        'cancelled' => 'Objednávka bola zrušená.',
    ],

    // Admin - Subscriptions
    'admin_subscription' => [
        'status_updated' => 'Stav predplatného bol úspešne aktualizovaný.',
        'address_updated' => 'Dodacia adresa bola úspešne aktualizovaná.',
        'cancel_failed' => 'Nepodarilo sa zrušiť predplatné. Skúste to prosím znova.',
    ],

    // Admin - Config
    'admin_config' => [
        'settings_updated' => 'Nastavenia boli úspešne aktualizované.',
        'schedule_updated' => 'Harmonogram rozosielania bol úspešne aktualizovaný.',
        'schedule_created' => 'Harmonogram na rok :year bol úspešne vytvorený.',
        'cannot_edit_past' => 'Minulé rozosielanie nie je možné upraviť.',
        'shipment_updated' => 'Rozosielanie na :month :year bolo úspešne aktualizované.',
    ],

    // Admin - Coupons
    'admin_coupon' => [
        'created' => 'Kupón bol úspešne vytvorený!',
        'updated' => 'Kupón bol úspešne aktualizovaný!',
        'deleted' => 'Kupón bol úspešne zmazaný!',
    ],

    // Admin - Newsletter
    'admin_newsletter' => [
        'subscription_removed' => 'Prihlásenie na odber newslettera bolo odstránené.',
        'synced' => 'Synchronizované zákaznícke e-maily: :count.',
    ],

];
