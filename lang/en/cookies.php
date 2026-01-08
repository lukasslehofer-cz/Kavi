<?php

return [
    // Cookie consent banner
    'consent_modal' => [
        'title' => 'We use cookies',
        'description' => 'Hi! This website uses cookies to ensure proper functionality, analyze traffic, and provide relevant content. Some cookies are essential for the website to work properly, while others can be declined. <a href=":privacy_url" class="cc-link">Privacy Policy</a>',
        'accept_all_btn' => 'Accept all',
        'accept_necessary_btn' => 'Reject all',
        'show_settings_btn' => 'Manage preferences',
        'close_btn_label' => 'Close',
    ],

    'settings_modal' => [
        'title' => 'Cookie settings',
        'save_settings_btn' => 'Save settings',
        'accept_all_btn' => 'Accept all',
        'reject_all_btn' => 'Reject all',
        'close_btn_label' => 'Close',
        'blocks' => [
            [
                'title' => 'Cookie usage',
                'description' => 'We use cookies to ensure the basic functionality of the website and to enhance your online experience. You can choose to enable or disable each category. For more information, please see our <a href=":privacy_url" class="cc-link">privacy policy</a>.',
            ],
            [
                'title' => 'Strictly necessary cookies',
                'description' => 'These cookies are essential for the proper functioning of the website. Without them, the website would not work correctly. They include security tokens and language settings.',
                'toggle' => [
                    'value' => 'necessary',
                    'enabled' => true,
                    'readonly' => true,
                ],
            ],
            [
                'title' => 'Analytics cookies',
                'description' => 'These cookies help us understand how visitors use our website. We collect data anonymously and use it to improve our services.',
                'toggle' => [
                    'value' => 'analytics',
                    'enabled' => false,
                    'readonly' => false,
                ],
            ],
            [
                'title' => 'Marketing cookies',
                'description' => 'These cookies are used to display ads that are relevant to you and tailored to your interests.',
                'toggle' => [
                    'value' => 'marketing',
                    'enabled' => false,
                    'readonly' => false,
                ],
            ],
        ],
    ],
];
