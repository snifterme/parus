<?php

namespace rokorolov\parus\menu;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use Yii;

/**
 * This is the rokorolov\parus\menu\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/menu';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\menu\controllers';

    /**
     * Menu Module config.
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
                'language' => 'en',
                'languages' => ['en' => 'English'],
                'defaultLanguage' => 'en',
                'linkPickers' => [
                    'page' => 'rokorolov\parus\menu\services\LinkPagePicker',
                    'category' => 'rokorolov\parus\menu\services\LinkCategoryPicker',
                    'post' => 'rokorolov\parus\menu\services\LinkPostPicker',
                ],
                'urlRules' => [],
                'menu.statuses' => [],
                'menu.defaultStatus' => null,
                'menu.managePageSize' => 10
            ],
            $this->config
        );

        if (!empty($rules = $this->config['urlRules'])) {
            Yii::$app->urlManager->addRules($rules);
        }

        $this->registerTranslation();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/menu*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/menu*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/menu/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/menu' => 'menu.php',
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
