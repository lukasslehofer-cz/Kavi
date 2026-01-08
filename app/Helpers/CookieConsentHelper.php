<?php

namespace App\Helpers;

class CookieConsentHelper
{
    /**
     * Get the cookie consent configuration as JSON for the current locale
     *
     * @param string $locale
     * @return string
     */
    public static function getConfigJson(string $locale = 'cs'): string
    {
        $config = self::getConfig($locale);
        return json_encode($config, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
    }

    /**
     * Get the cookie consent configuration array
     *
     * @param string $locale
     * @return array
     */
    public static function getConfig(string $locale = 'cs'): array
    {
        $privacyUrl = localizedRoute('privacy-policy');

        $translations = __('cookies', [], $locale);
        
        // Replace privacy URL placeholder in descriptions
        $translations['consent_modal']['description'] = str_replace(
            ':privacy_url',
            $privacyUrl,
            $translations['consent_modal']['description']
        );
        
        if (isset($translations['settings_modal']['blocks'][0]['description'])) {
            $translations['settings_modal']['blocks'][0]['description'] = str_replace(
                ':privacy_url',
                $privacyUrl,
                $translations['settings_modal']['blocks'][0]['description']
            );
        }

        return [
            'guiOptions' => [
                'consentModal' => [
                    'layout' => 'box',
                    'position' => 'bottom right',
                    'equalWeightButtons' => false,
                    'flipButtons' => false,
                ],
                'preferencesModal' => [
                    'layout' => 'box',
                    'position' => 'right',
                    'equalWeightButtons' => true,
                    'flipButtons' => false,
                ],
            ],

            'categories' => [
                'necessary' => [
                    'enabled' => true,
                    'readOnly' => true,
                ],
                'analytics' => [
                    'enabled' => false,
                    'readOnly' => false,
                    'autoClear' => [
                        'cookies' => [
                            [
                                'name' => '/^_ga/',
                            ],
                            [
                                'name' => '_gid',
                            ],
                        ],
                    ],
                ],
                'marketing' => [
                    'enabled' => false,
                    'readOnly' => false,
                ],
            ],

            'language' => [
                'default' => $locale,
                'translations' => [
                    $locale => [
                        'consentModal' => [
                            'title' => $translations['consent_modal']['title'],
                            'description' => $translations['consent_modal']['description'],
                            'acceptAllBtn' => $translations['consent_modal']['accept_all_btn'],
                            'acceptNecessaryBtn' => $translations['consent_modal']['accept_necessary_btn'],
                            'showPreferencesBtn' => $translations['consent_modal']['show_settings_btn'],
                            'closeIconLabel' => $translations['consent_modal']['close_btn_label'],
                        ],
                        'preferencesModal' => [
                            'title' => $translations['settings_modal']['title'],
                            'acceptAllBtn' => $translations['settings_modal']['accept_all_btn'],
                            'acceptNecessaryBtn' => $translations['settings_modal']['reject_all_btn'],
                            'savePreferencesBtn' => $translations['settings_modal']['save_settings_btn'],
                            'closeIconLabel' => $translations['settings_modal']['close_btn_label'],
                            'sections' => self::buildSections($translations['settings_modal']['blocks']),
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Build sections array from translation blocks
     *
     * @param array $blocks
     * @return array
     */
    private static function buildSections(array $blocks): array
    {
        $sections = [];

        foreach ($blocks as $block) {
            $section = [
                'title' => $block['title'],
                'description' => $block['description'],
            ];

            if (isset($block['toggle'])) {
                $section['linkedCategory'] = $block['toggle']['value'];
            }

            $sections[] = $section;
        }

        return $sections;
    }
}
