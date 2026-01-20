-- Fix subscription #38 after pause ended on 2026-01-20
-- 
-- Issues:
-- 1. next_billing_date was set to 2026-01-15 (in the past) - ALREADY FIXED to 2026-02-15
-- 2. Missing pending shipment for February 2026
--
-- Run this on production database to fix the issue.

-- Step 1: Ensure next_billing_date is correct (2026-02-15)
UPDATE subscriptions 
SET next_billing_date = '2026-02-15',
    updated_at = NOW()
WHERE id = 38 
  AND next_billing_date != '2026-02-15';

-- Step 2: Create pending shipment for February 2026 (if not exists)
-- Get shipment_schedule_id for February 2026
INSERT INTO subscription_shipments (
    subscription_id,
    shipment_schedule_id,
    shipment_date,
    package_weight,
    package_length,
    package_width,
    package_height,
    status,
    created_at,
    updated_at
)
SELECT 
    38,
    (SELECT id FROM shipment_schedules WHERE YEAR(shipment_date) = 2026 AND MONTH(shipment_date) = 2 LIMIT 1),
    '2026-02-20',
    1.00,
    32.00,
    19.00,
    9.00,
    'pending',
    NOW(),
    NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM subscription_shipments 
    WHERE subscription_id = 38 
    AND shipment_date = '2026-02-20'
);

-- Verify the updates
SELECT id, subscription_number, status, next_billing_date, last_shipment_date, frequency_months
FROM subscriptions 
WHERE id = 38;

SELECT id, subscription_id, shipment_date, status, subscription_payment_id
FROM subscription_shipments 
WHERE subscription_id = 38 
ORDER BY shipment_date DESC
LIMIT 5;
