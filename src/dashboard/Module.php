<?php

namespace rokorolov\parus\dashboard;

use Yii;

/**
 * This is the rokorolov\parus\dashboard\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/dashboard';

    /**
     * Current app Language (target yii2 language)
     *
     * @var string
     */
    public $language;

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\dashboard\controllers';

    /**
     * Dashboard Module config.
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
                'panelLanguage' => 'en',
                'enableIntl' => true,
                'post.popularLimit' => 5,
                'post.lastAddedLimit' => 5,
            ],
            $this->config
        );

        $this->registerTranslation();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/dashboard*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/dashboard*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/dashboard/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/dashboard' => 'dashboard.php',
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
