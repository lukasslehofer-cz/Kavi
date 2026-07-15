<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment retry / dunning
    |--------------------------------------------------------------------------
    |
    | payment_retry_attempts:
    |   How many charge attempts (reminders) are made for a single shipment
    |   before it is abandoned and the subscription is paused for one cycle.
    |
    | unpaid_shipments_before_cancel:
    |   How many shipments in a row may go unpaid before the subscription is
    |   automatically cancelled.
    */
    'payment_retry_attempts' => (int) env('SUBSCRIPTION_PAYMENT_RETRY_ATTEMPTS', 3),
    'unpaid_shipments_before_cancel' => (int) env('SUBSCRIPTION_UNPAID_SHIPMENTS_BEFORE_CANCEL', 3),
];
