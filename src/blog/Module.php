<?php

namespace rokorolov\parus\blog;

use rokorolov\parus\blog\helpers\Settings;
use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use Yii;

/**
 * This is the rokorolov\parus\blog\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/blog';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\blog\controllers';

    /**
     * Blog Module config.
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
                'enableIntl' => true,
                'post.introImageUploadPath' => '@webroot/uploads/post',
                'post.introImageUploadSrc' => '@web/uploads/post',
                'post.introImageAllowedExtensions' => ['jpg', 'jpeg', 'png'],
                'post.imageUploadPath' => '@webroot/uploads/media',
                'post.imageUploadSrc' => '@web/uploads/media',
                'post.imageExtension' => 'jpg',
                'post.imageTransformations' => [],
                'post.statuses' => [],
                'post.defaultStatus' => null,
                'post.managePageSize' => 10,
                'category.introImageUploadPath' => '@webroot/uploads/category',
                'category.introImageUploadSrc' => '@web/uploads/category',
                'category.introImageAllowedExtensions' => ['jpg', 'jpeg', 'png'],
                'category.imageUploadPath' => '@webroot/uploads/media',
                'category.imageUploadSrc' => '@web/uploads/media',
                'category.imageExtension' => 'jpg',
                'category.imageTransformations' => [],
                'category.statuses' => [],
                'category.defaultStatus' => null,
                'category.managePageSize' => 10,
            ],
            $this->config
        );

        \yii\base\Event::on(\rokorolov\parus\blog\models\Post::class, \rokorolov\parus\blog\models\Post::EVENT_AFTER_DELETE, function ($event) {
            if (!empty($event->sender->image)) {
                $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                    null,
                    null,
                    Settings::postIntroImageUploadPath() . DIRECTORY_SEPARATOR . $event->sender->id
                ]);
                $imageManager->deleteAll();
            }
        });

        \yii\base\Event::on(\rokorolov\parus\blog\models\Category::class, \rokorolov\parus\blog\models\Category::EVENT_AFTER_DELETE, function ($event) {
            if (!empty($event->sender->image)) {
                $imageManager = Yii::createObject('rokorolov\parus\admin\services\ImageManager', [
                    null,
                    null,
                    Settings::categoryIntroImageUploadPath() . DIRECTORY_SEPARATOR . $event->sender->id
                ]);
                $imageManager->deleteAll();
            }
        });

        $this->registerTranslation();

        parent::init();
    }

    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/blog*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/blog*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/blog/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/blog' => 'blog.php',
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
