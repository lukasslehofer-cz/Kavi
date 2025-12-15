-- Fix paused_until_date for subscription ID 29
-- 
-- Problem: User paused subscription for 3 iterations right after December payment was processed.
-- System calculated pause from December (wrongly treating it as unpaid) instead of January.
-- 
-- Current (wrong):  paused_until_date = 2026-02-19 (covers Dec, Jan, Feb)
-- Correct:          paused_until_date = 2026-03-20 (covers Jan, Feb, Mar)
--
-- First unpaid shipment should be January (since December is paid - next_billing_date is 2026-01-15)
-- Pause for 3 iterations: January, February, March
-- paused_until_date = March shipment date

-- First, check what shipment_schedules say for March 2026:
SELECT year, month, billing_date, shipment_date 
FROM shipment_schedules 
WHERE year = 2026 AND month IN (1, 2, 3, 4)
ORDER BY month;

-- Update the paused_until_date to March 2026 shipment date
-- If shipment_schedules has March 2026, use that date. Otherwise fallback to 2026-03-20.
UPDATE subscriptions 
SET paused_until_date = COALESCE(
    (SELECT shipment_date FROM shipment_schedules WHERE year = 2026 AND month = 3),
    '2026-03-20'
)
WHERE id = 29;

-- Verify the update:
SELECT id, subscription_number, paused_iterations, paused_until_date, next_billing_date
FROM subscriptions 
WHERE id = 29;

