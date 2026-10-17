<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gifts: gift subscription and shop voucher
    |--------------------------------------------------------------------------
    |
    | Prices, order deadlines, shipping dates and URLs are filled in from the
    | database by the code.
    |
    */

    'months' => '{1} :count month|[2,*] :count months',
    'boxes' => '{1} :count box|[2,*] :count boxes',
    'months_word' => [3 => 'Three months', 6 => 'Six months'],
    'box_label' => ':box Box · :count bags a month',

    'item_name' => [
        'subscription' => 'Gift subscription :box Box, :months',
        'voucher' => 'Gift voucher :amount (multi-purpose, VAT on redemption)',
    ],

    'certificate' => [
        'eyebrow_subscription' => 'Gift certificate',
        'eyebrow_voucher' => 'Gift voucher',
        'chips_subscription' => ['Every month', 'Three European roasters'],
        'chips_voucher' => ['Specialty coffee', 'From European roasters'],
        'headline_middle' => 'of specialty',
        'headline_last' => 'coffee',
        'voucher_sub' => 'For specialty coffee',
        'to' => 'For',
        'from' => 'From',
        'box' => 'Box',
        'code' => 'Code',
        'quote_open' => '“',
        'quote_close' => '”',
        'footer_subscription' => 'Activate at :url · valid until :date',
        'footer_voucher' => 'For the whole :site shop · valid until :date',
        'sample' => [
            'recipient' => 'Anna',
            'giver' => 'Tom',
            'note' => 'Enjoy every morning. Merry Christmas.',
        ],
        'back' => [
            'eyebrow' => 'A gift from KAVI',
            'title' => 'How to',
            'title_accent' => 'redeem',
            'steps' => [
                ['t' => 'Open :url', 'd' => 'Or scan the QR code at the bottom right.'],
                ['t' => 'Enter the code', 'd' => 'You will find it on the front in the black strip.'],
                ['t' => 'Choose your coffee', 'd' => 'For a subscription, coffee type and pickup point. A voucher is deducted at checkout.'],
            ],
            'info_subscription' => 'Three European roasters every month, the first box ships in the next shipping run around the 20th. Shipping included. From every box we donate to Water.org.',
            'info_voucher' => 'Specialty coffee from European roasters: coffee boxes and single coffees in the shop. From every box we donate to Water.org.',
            'contact' => 'Questions: :email · :site',
        ],
    ],

    'page' => [
        'title' => 'Gift Subscriptions and Coffee Vouchers | KAVI',
        'meta_description' => 'Give specialty coffee: a gift subscription for 3 or 6 months, or a voucher for the whole shop. The certificate arrives by email straight away as a printable PDF.',
        'hero_title' => ['Give', 'coffee'],
        'hero_text' => 'A gift subscription for 3 or 6 months, or a voucher for the whole shop. The certificate arrives by email straight away as a printable PDF.',

        'preview_label' => 'Certificate preview',
        'preview_format' => 'PDF · A5 landscape',
        'total' => 'Total price',
        'total_note_subscription' => ':box Box :price × :months · shipping included',
        'total_note_voucher' => 'PDF certificate right after payment',
        'cta' => 'Buy the gift',
        'bullets' => ['Certificate straight away', 'No renewal'],

        'step_type' => 'What would you like to give',
        'step_type_sub' => 'A subscription, or a voucher for anything in the shop',
        'type_subscription' => 'Gift subscription',
        'type_subscription_desc' => 'A box of coffee every month, for 3 or 6 months',
        'type_voucher' => 'Gift voucher',
        'type_voucher_desc' => 'For anything in the shop, they choose',
        'from' => 'from :price',

        'step_length' => 'For how long',
        'step_length_sub' => 'One box every month, paid once upfront',
        'length_desc' => ':boxes · paid once',

        'step_size' => 'Amount of coffee',
        'step_size_sub' => 'How much coffee should arrive each month',
        'size_desc' => [
            2 => 'For two cups a day',
            3 => 'For three cups a day',
            4 => 'For a household or an office',
        ],

        'step_amount' => 'Voucher value',
        'step_amount_sub' => 'Valid for the whole shop, boxes and single coffees',
        'amount_desc_all' => 'For coffee from the shop or the first payment of any box',
        'amount_desc_some' => 'For the :boxes Box, or an order from the shop',
        'amount_desc_none' => 'For a bigger order from the shop, several coffees at once',
        'boxes_or' => ' or ',
        'amount_note' => 'The voucher is used in full on one order worth at least its value. Valid for 12 months from purchase.',

        'step_whom' => 'Who is it for',
        'step_whom_sub' => 'The names and message will be printed on the certificate',
        'recipient_name' => 'Recipient\'s name',
        'giver_name' => 'From',
        'note' => 'Message, optional',
        'note_placeholder' => 'Enjoy every morning',
        'delivery_buyer' => 'Send it to me',
        'delivery_buyer_desc' => 'I will hand over or print the PDF certificate',
        'delivery_recipient' => 'Send it to the recipient',
        'delivery_recipient_desc' => 'By email on the day you choose',
        'recipient_email' => 'Recipient\'s email',
        'recipient_email_placeholder' => 'anna@email.com',
        'send_at' => 'When to send',

        'gets_title' => 'What they will get',
        'gets_lead' => 'Every month a box of specialty coffee from European roasters they rarely find in local shops.',
        'gets_arrows' => [
            '2 to 4 bags of 250 g, depending on the box size',
            'A postcard with the coffee and roaster profile for every coffee',
            'Their own choice of espresso, filter or mix, and pickup point',
            'No commitment, the gift ends with the last box',
        ],
        'gets_coffee' => 'Coffee of the Month',
        'gets_self' => 'A box for yourself',

        'how_title' => 'How it works',
        'how_lead' => 'From payment to the first box',
        'how_steps' => [
            ['t' => 'Choose and pay', 'd' => 'Subscription or voucher, a message, and who should get the certificate.'],
            ['t' => 'Certificate straight away', 'd' => 'A printable PDF, or we send it straight to the recipient on the day you choose.'],
            ['t' => 'They activate it', 'd' => 'At :url they enter the code, coffee type and pickup point.'],
            ['t' => 'First box on day :day', 'd' => 'It ships in the next shipping run. Orders always close on day :cutoff_day of the month.'],
        ],

        'corporate' => 'Corporate gifts from 10 pieces, invoiced to your company with a shared message',
        'corporate_cta' => 'Write to :email',

        'faq_title' => 'Gift FAQ',
        'faq' => [
            ['q' => 'Does the subscription renew automatically?', 'a' => 'No. The gift ends with the last box. We then offer the recipient to continue, nothing is charged automatically.'],
            ['q' => 'Will it arrive in time for Christmas?', 'a' => 'The certificate does, it arrives right after payment. The coffee box then ships according to the activation date, in the next shipping run.'],
            ['q' => 'What if they don\'t drink espresso?', 'a' => 'They choose the coffee type when activating: espresso, filter or a mix of both.'],
            ['q' => 'What can the voucher be used for?', 'a' => 'For coffee and accessories in the shop and for the first payment of a coffee box. It is used in full at checkout, the order must be worth at least the voucher value and any unused balance is not refunded. Valid for 12 months from purchase.'],
        ],
    ],

    'redeem' => [
        'title' => 'Redeem a Gift | KAVI',
        'meta_description' => 'Got coffee from KAVI? Enter the code from your certificate and choose your coffee.',
        'hero_title' => ['Redeem', 'a gift'],
        'hero_text' => 'Got coffee from KAVI? Enter the code from your certificate and choose your coffee. You add the pickup point and contact at checkout, you pay nothing.',

        'intro_eyebrow' => 'A gift from KAVI',
        'intro_title' => ['Your code is', 'on the certificate'],
        'intro_text' => 'On the front, in the black strip. It looks like XXXX-XXXX, upper and lower case do not matter.',
        'intro_steps' => [
            ['t' => 'Enter the code', 'd' => 'We check it and show you what you got and from whom.'],
            ['t' => 'Choose your coffee', 'd' => 'Espresso, filter or mix, and when to start.'],
            ['t' => 'Finish at checkout', 'd' => 'Pickup point and contact. You pay €0.'],
        ],

        'verified' => 'Code verified',
        'title_subscription' => ['Subscription', 'for :months'],
        'title_voucher' => ['Voucher', 'for :amount'],
        'wish' => 'Message from the giver',
        'from' => 'From',
        'row_for' => 'For',
        'row_box' => 'Box',
        'row_count' => 'Number of boxes',
        'row_count_value' => ':count, one every month',
        'row_valid_on' => 'Valid for',
        'row_valid_on_value' => 'the whole :site shop',
        'row_valid_until' => 'Valid until',

        'step_code' => 'Code from the certificate',
        'step_code_sub' => 'Upper and lower case do not matter',
        'code_label' => 'Gift code',
        'verify' => 'Verify code',
        'ok_subscription' => 'The code is valid. Choose your coffee and when to start.',
        'ok_voucher' => 'The code is valid. You redeem the voucher at checkout.',
        'error_unknown' => 'We don\'t recognise this code. Please check it against the certificate, or write to :email.',
        'error_redeemed' => 'This gift was already redeemed on :date. If it wasn\'t you, please write to :email.',
        'error_expired' => 'This gift expired on :date. Write to us at :email and we will sort it out.',
        'error_other_domain' => 'This gift is redeemed at :url.',
        'error_throttled' => 'Too many attempts. Please try again in a moment.',
        'error_generic' => 'The check didn\'t work. Please try again.',
        'error_not_verified' => 'Please verify the code from your certificate first.',
        'error_sold_out' => 'This coffee combination is no longer available for the chosen month. Please choose another coffee type or a later start.',

        'step_type' => 'Coffee type',
        'step_type_sub' => 'Applies to every box of the gift',
        'types' => [
            'espresso' => ['name' => 'Espresso', 'desc' => 'Fuller body, darker roast'],
            'filter' => ['name' => 'Filter', 'desc' => 'Lighter body, lighter roast'],
            'mix' => ['name' => 'Mix', 'desc' => 'Espresso and filter'],
        ],
        'mix_title' => 'Coffee split',
        'mix_less' => 'Less',
        'mix_more' => 'More',

        'step_start' => 'When to start',
        'step_start_sub' => 'Orders always close on day :cutoff_day, boxes ship around day :ship_day of the month',
        'starts' => [
            ['name' => 'In the next shipping run', 'desc' => 'Orders until :cutoff at midnight'],
            ['name' => 'One month later', 'desc' => 'Handy if you still have coffee at home'],
            ['name' => 'In two months', 'desc' => 'The gift waits, nothing expires'],
        ],

        'summary' => 'Summary',
        'summary_box' => 'Box',
        'summary_type' => 'Coffee type',
        'summary_count' => 'Number of boxes',
        'summary_first' => 'First box',
        'summary_last' => 'Last box',
        'continue' => 'Continue to checkout',
        'continue_note' => 'At checkout you choose a pickup point and add your contact. The code will already be applied, you pay €0.',

        'voucher_step' => 'How to use the voucher',
        'voucher_step_sub' => 'Enter the code at checkout in the discount code field, the amount is deducted automatically',
        'voucher_box' => 'Build a coffee box',
        'voucher_box_desc' => 'A subscription or a one-off box, from :price',
        'voucher_box_link' => 'Configurator',
        'voucher_shop' => 'Choose coffee in the shop',
        'voucher_shop_desc' => 'Single coffees from the roasters we work with',
        'voucher_shop_link' => 'Shop',
        'voucher_rules' => 'The voucher is used in full on one order worth at least :amount. Any unused balance is not refunded. Valid until :date.',

        'faq' => [
            ['q' => 'I lost my code', 'a' => 'Write to :email with the giver\'s name or the email they used to buy the gift. We will find the code.'],
            ['q' => 'Can I change the coffee or pickup point later?', 'a' => 'You can change the pickup point in your account anytime before a box\'s order deadline. You choose the coffee type once, when you redeem the code, and it applies to every box of the gift.'],
            ['q' => 'I already have a subscription. What about the gift?', 'a' => 'Your gift runs as a separate subscription alongside yours. Your paid subscription stays exactly as it is and the boxes arrive separately. If you\'d like the gift to start later, choose a start one or two months out.'],
            ['q' => 'What happens after the last box?', 'a' => 'Nothing renews and nothing is charged. We will ask you whether you would like to continue.'],
        ],
    ],

    'checkout' => [
        'title' => 'Activate your gift',
        'summary_title' => 'Your gift',
        'cta' => 'Activate gift',
        'country' => 'Pickup point country',
        'free' => 'You pay €0',
        'free_note' => 'The gift is already paid. Nothing is charged or renewed.',
        'login_required' => 'An account with :email already exists. Please sign in, your gift will wait.',
        'error_invalid' => 'The gift could not be redeemed. Please verify the code again.',
        'confirmation_title' => 'Your gift is activated',
        'confirmation_text' => 'The first box ships on :first, the last on :last. We sent a confirmation to :email.',
        'confirmation_shipment' => 'We ship the first box on :date to your pickup point, then one every month.',
        'confirmation_manage' => 'You can change the pickup point in your account before each deadline. The gift ends on its own after the last box; nothing renews.',
    ],

    'cart' => [
        'section' => 'Gifts',
        'delivery' => 'The certificate is sent by email as a PDF',
        'remove' => 'Remove',
        'for' => 'For: :name',
        'send_to' => 'We will send it to :email on :date',
        'coupon_not_for_gifts' => 'Discount codes and vouchers cannot be used for gifts.',
        'subscription_code_redeem_only' => 'This code is a gift subscription. Redeem it at :url.',
        'voucher_other_currency' => 'This voucher is only valid on :site.',
        'zero_total' => 'An order of €0 cannot be paid by card. Please add another item to your cart.',
        'added' => 'The gift is in your cart.',
        'removed' => 'We removed the gift from your cart.',
        'too_many' => 'One order can contain at most :max gifts.',
        'currency_changed' => 'We removed a gift in another currency from your cart.',
        'confirmation' => 'We are sending the certificate to :email. You can also download it here:',
        'download' => 'Download certificate (PDF)',
        'preparing' => 'The certificate is being prepared, it will be in your inbox in a few minutes.',
    ],

    'mail' => [
        'buyer_subject' => 'Your KAVI gift is ready',
        'buyer_title' => 'Your gift is ready',
        'buyer_intro' => 'Thank you for order :order. The certificate for :recipient is attached as a printable PDF.',
        'buyer_scheduled' => 'We will also email it to the recipient on :date at :email.',
        'buyer_code' => 'Gift code',
        'buyer_valid' => 'Valid until :date. Redeem at :url.',
        'recipient_subject' => ':giver sent you a gift from KAVI',
        'recipient_title' => 'You have a gift from KAVI',
        'recipient_intro' => ':giver sent you specialty coffee from KAVI. The certificate is attached.',
        'recipient_cta' => 'Redeem gift',
        'activated_subject' => 'Your gift is activated, the first box ships on :date',
        'activated_title' => 'Your gift is activated',
        'activated_intro' => 'Your :box gift subscription is active. The first box ships on :first, the last on :last.',
        'activated_note' => 'You can change the pickup point in your account before each box\'s order deadline. The coffee type you chose applies to every box of the gift.',
        'activated_cta' => 'Go to my account',
        'ending_subject' => 'The last box of your gift ships on :date',
        'ending_title' => 'The last gift box is coming',
        'ending_intro' => 'The last box of your gift subscription ships on :date. After that nothing renews and nothing is charged.',
        'ending_offer' => 'Did you enjoy it? You can set up a subscription that continues right the next month.',
        'ending_cta' => 'Continue with a subscription',
    ],

    'subscription' => [
        'label' => 'Gift subscription',
        'progress' => 'Box :current of :total',
        'action_not_allowed' => 'A gift subscription cannot be paused or cancelled. It ends by itself with the last box.',
    ],

];
