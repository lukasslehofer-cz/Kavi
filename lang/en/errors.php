<?php

/*
|--------------------------------------------------------------------------
| Error pages (resources/views/errors)
|--------------------------------------------------------------------------
|
| Error pages are often rendered without the middleware that sets the locale,
| so the templates pass the locale explicitly: __('errors.x', [], $locale).
|
*/

return [
    'layout' => [
        'title' => 'Error | KAVI',
    ],

    'links' => [
        'home' => 'Home',
        'coffee_boxes' => 'Coffee Boxes',
        'shop' => 'Shop',
        'gifts' => 'Gifts',
        'contact' => 'Contact',
        'terms' => 'Terms',
        'privacy' => 'Privacy',
        'roasteries' => 'Roasteries',
        'coffee_of_the_month' => 'Coffee of the Month',
        'how_it_works' => 'How It Works',
        'back_home' => 'Back to Home',
    ],

    'common' => [
        'need_help' => 'Need Help?',
        'email' => 'Email',
        'contact_form' => 'Contact Form',
        'send_message' => 'Send us a message',
        'error_code' => 'Error Code',
    ],

    'forbidden' => [
        'title' => 'Access Denied | KAVI',
        'heading_1' => 'Access',
        'heading_2' => 'Denied',
        'description' => 'You don\'t have permission to access this page. If you believe you should have access, please log in or contact support.',
        'sign_in_button' => 'Sign In',
        'reasons_title' => 'Common reasons for this error',
        'reason_login' => 'You need to be logged in',
        'reason_session' => 'Your session has expired',
        'reason_restricted' => 'The content is restricted',
        'sign_in_label' => 'Sign In',
        'sign_in_link' => 'Log into your account',
        'code_label' => 'Forbidden',
    ],

    'not_found' => [
        'title' => 'Page Not Found | KAVI',
        'heading_1' => 'Page',
        'heading_2' => 'Not Found',
        'description' => 'The page you\'re looking for doesn\'t exist or has been moved. You may have mistyped the address, or the link is no longer valid.',
        'maybe_looking_for' => 'Maybe you\'re looking for',
        'learn_more' => 'Learn More',
        'code_label' => 'Not Found',
    ],

    'page_expired' => [
        'title' => 'Page Expired | KAVI',
        'heading_1' => 'Page',
        'heading_2' => 'Expired',
        'description' => 'Your session has expired for security reasons. Please refresh the page and try again.',
        'refresh' => 'Refresh Page',
        'why_title' => 'Why did this happen?',
        'why_text' => 'This error occurs when a page has been open for too long without activity. It\'s a security feature to protect your data. Simply refresh the page to continue.',
        'quick_fix' => 'Quick Fix',
        'step_1' => 'Click the "Refresh Page" button above',
        'step_2' => 'Or press Ctrl+R (Cmd+R on Mac)',
        'step_3' => 'Fill out the form again',
        'code_label' => 'Session Expired',
    ],

    'server_error' => [
        'title' => 'Something Went Wrong | KAVI',
        'heading_1' => 'Something',
        'heading_2' => 'Went Wrong',
        'description' => 'Sorry, but there was a server error. Our team is already working on it. Please try again in a moment.',
        'try_again' => 'Try Again',
        'what_to_do' => 'What can you do?',
        'tip_refresh' => 'Try refreshing the page',
        'tip_later' => 'Come back later',
        'tip_contact' => 'Contact us if the problem persists',
        'contact_us' => 'Contact Us',
        'on_it_label' => 'We\'re On It',
        'on_it_text' => 'Our team has been notified',
        'code_label' => 'Internal Server Error',
    ],

    'maintenance' => [
        'title' => 'Maintenance | KAVI',
        'heading_1' => 'Under',
        'heading_2' => 'Maintenance',
        'description' => 'Our website is currently undergoing maintenance to bring you even better services. We\'ll be back soon with fresh coffee!',
        'estimated_return_label' => 'Estimated Return',
        'estimated_return' => 'Within a few minutes',
        'check_availability' => 'Check Availability',
        'stay_updated' => 'Stay Updated',
        'newsletter_text' => 'Subscribe to our newsletter and be the first to know when we\'re back online.',
        'email_placeholder' => 'YOUR EMAIL',
        'subscribe' => 'Subscribe',
        'sending' => 'SENDING...',
        'subscribe_error' => 'An error occurred. Please try again later.',
        'whats_happening' => 'What\'s Happening?',
        'update_performance' => 'Improving site performance',
        'update_features' => 'Adding new features',
        'update_security' => 'Updating security',
        'questions' => 'Questions?',
        'status' => 'Status',
        'code_label' => 'Service Unavailable',
    ],
];
