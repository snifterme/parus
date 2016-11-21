<?php

namespace rokorolov\parus\settings\helpers;

use rokorolov\parus\settings\contracts\DefaultInstallInterface;

/**
 * DefaultInstall
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DefaultInstall implements DefaultInstallInterface
{
    public $installSettings = true;
    
    public function shouldInstallDefaults()
    {
        return $this->installSettings;
    }
    
    public function getSettingParams()
    {
        $language = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemId();
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        
        return [
            [
                'id' => 1,
                'param' => 'EMAIL.FOR_NOTIFICATION',
                'value' => '',
                'default' => '',
                'type' => 'string',
                'order' => 4,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 2,
                'param' => 'SITE.NAME',
                'value' => '',
                'default' => 'PageEasy',
                'type' => 'string',
                'order' => 1,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 3,
                'param' => 'SITE.OFFLINE',
                'value' => '0',
                'default' => '0',
                'type' => 'boolean',
                'order' => 6,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 4,
                'param' => 'SITE.OFFLINE_MESSAGE',
                'value' => '',
                'default' => '',
                'type' => 'text',
                'order' => 7,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 5,
                'param' => 'SITE.ADDRESS_URL',
                'value' => '',
                'default' => '',
                'type' => 'string',
                'order' => 2,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 6,
                'param' => 'EMAIL.FOR_CONTACT',
                'value' => '',
                'default' => '',
                'type' => 'string',
                'order' => 3,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 7,
                'param' => 'META.DESCRIPTION',
                'value' => '',
                'default' => '',
                'type' => 'text',
                'order' => 8,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 8,
                'param' => 'META.KEYWORDS',
                'value' => '',
                'default' => '',
                'type' => 'text',
                'order' => 9,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
            [
                'id' => 9,
                'param' => 'SITE.DEFAULT_LANGUAGE',
                'value' => $language,
                'default' => $language,
                'type' => 'dropdown',
                'order' => 5,
                'created_at' => $datetime,
                'modified_at' => $datetime,
            ],
        ];
    }
    
    public function getSettingLangParams()
    {
        return [
            [
                'settings_id' => '1',
                'language' => 'en',
                'label' => 'Email for notification',
            ],
            [
                'settings_id' => '1',
                'language' => 'lv',
                'label' => 'E-pasts paziņojumiem',
            ],
            [
                'settings_id' => '1',
                'language' => 'ru',
                'label' => 'E-mail для уведомления',
            ],
            [
                'settings_id' => '2',
                'language' => 'en',
                'label' => 'Site Name',
            ],
            [
                'settings_id' => '2',
                'language' => 'lv',
                'label' => 'Mājas lapas nosaukums',
            ],
            [
                'settings_id' => '2',
                'language' => 'ru',
                'label' => 'Название сайта',
            ],
            [
                'settings_id' => '3',
                'language' => 'en',
                'label' => 'Site Offline',
            ],
            [
                'settings_id' => '3',
                'language' => 'lv',
                'label' => 'Mājas lapa ir atslēgta',
            ],
            [
                'settings_id' => '3',
                'language' => 'ru',
                'label' => 'Сайт выключен (offline)',
            ],
            [
                'settings_id' => '4',
                'language' => 'en',
                'label' => 'Custom Message',
            ],
            [
                'settings_id' => '4',
                'language' => 'lv',
                'label' => 'Ziņa, ja ir atslēgta',
            ],
            [
                'settings_id' => '4',
                'language' => 'ru',
                'label' => 'Сообщение при выключенном сайте',
            ],
            [
                'settings_id' => '5',
                'language' => 'en',
                'label' => 'Site Address (URL)',
            ],
            [
                'settings_id' => '5',
                'language' => 'lv',
                'label' => 'Mājas lapa (URL)',
            ],
            [
                'settings_id' => '5',
                'language' => 'ru',
                'label' => 'Адрес сайта (URL)',
            ],
            [
                'settings_id' => '6',
                'language' => 'en',
                'label' => 'Email for contact',
            ],
            [
                'settings_id' => '6',
                'language' => 'lv',
                'label' => 'E-pasts kontaktiem',
            ],
            [
                'settings_id' => '6',
                'language' => 'ru',
                'label' => 'E-mail для контактов',
            ],
            [
                'settings_id' => '7',
                'language' => 'en',
                'label' => 'Site Meta Description',
            ],
            [
                'settings_id' => '7',
                'language' => 'lv',
                'label' => 'Meta apraksts',
            ],
            [
                'settings_id' => '7',
                'language' => 'ru',
                'label' => 'Мета описание',
            ],
            [
                'settings_id' => '8',
                'language' => 'en',
                'label' => 'Site Meta Keywords',
            ],
            [
                'settings_id' => '8',
                'language' => 'lv',
                'label' => 'Meta atslēgvārdi',
            ],
            [
                'settings_id' => '8',
                'language' => 'ru',
                'label' => 'Мета ключевые слова',
            ],
            [
                'settings_id' => '9',
                'language' => 'en',
                'label' => 'Default language',
            ],
            [
                'settings_id' => '9',
                'language' => 'lv',
                'label' => 'Vadības valoda',
            ],
            [
                'settings_id' => '9',
                'language' => 'ru',
                'label' => 'Язык админ. панели',
            ],
        ];
    }
}
