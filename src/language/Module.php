<?php

namespace rokorolov\parus\language;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use Yii;

/**
 * This is the rokorolov\parus\language\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/language';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\language\controllers';

    /**
     * Language Module config.
     *
     * @var type
     */
    public $config = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->config = array_replace(
            [
                'defaultAppLanguage' => 'en',
                'enableIntl' => true,
                'language.statuses' => [],
                'language.defaultStatus' => null,
                'language.managePageSize' => 10,
            ],
            $this->config
        );

        $this->registerTranslation();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/language*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/language*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/language/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/language' => 'language.php',
                ]
            ];
        }
    }

    /**
     * Translates a message to the specified language.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('rokorolov/parus/' . $category, $message, $params, $language);
    }
}
