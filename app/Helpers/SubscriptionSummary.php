<?php

namespace App\Helpers;

use App\Models\Subscription;

/**
 * Shrnutí obsahu předplatného (káva, množství, frekvence, výdejní místo) pro e-maily
 * a stránky. Texty z confirmation.subscription_details, ať sedí s potvrzením objednávky.
 */
class SubscriptionSummary
{
    /**
     * @return array<int, array{label: string, value: string}>
     */
    public static function rows(Subscription $subscription, ?string $locale = null): array
    {
        $config = is_string($subscription->configuration)
            ? json_decode($subscription->configuration, true)
            : ($subscription->configuration ?? []);
        $t = fn (string $key, array $replace = []) => __('confirmation.subscription_details.'.$key, $replace, $locale);

        $rows = [];

        $type = $config['type'] ?? null;
        if ($type) {
            $coffee = in_array($type, ['espresso', 'filter', 'mix'], true) ? $t($type) : ucfirst($type);

            if ($type === 'mix' && isset($config['mix'])) {
                $parts = array_filter([
                    ($config['mix']['espresso'] ?? 0) > 0 ? $config['mix']['espresso'].'× '.$t('espresso') : null,
                    ($config['mix']['filter'] ?? 0) > 0 ? $config['mix']['filter'].'× '.$t('filter') : null,
                ]);
                if ($parts) {
                    $coffee .= ' ('.implode(', ', $parts).')';
                }
            }

            if ($config['isDecaf'] ?? false) {
                $coffee .= ' '.$t('incl_decaf');
            }

            $rows[] = ['label' => $t('coffee_type'), 'value' => $coffee];
        }

        if (! empty($config['amount'])) {
            $rows[] = ['label' => $t('quantity'), 'value' => $t('bags_format', [
                'count' => (int) $config['amount'],
                'grams' => (int) $config['amount'] * 250,
            ])];
        }

        if (in_array((int) $subscription->frequency_months, [1, 2, 3], true)) {
            $rows[] = ['label' => $t('frequency'), 'value' => $t('frequency_'.(int) $subscription->frequency_months)];
        }

        if ($subscription->packeta_point_name) {
            $rows[] = ['label' => __('dashboard.pickup_point', [], $locale), 'value' => $subscription->packeta_point_name];
        }

        return $rows;
    }
}
