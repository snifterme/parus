<?php

namespace rokorolov\parus\user;

use Yii;

/**
 * This is the rokorolov\parus\user\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/user';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\user\controllers';

    /**
     * User Module config.
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
                'enableIntl' => true,
                'user.managePageSize' => 10,
            ],
            $this->config
        );

        if (!Yii::$container->has('rokorolov\parus\admin\contracts\RbacServiceInterface')) {
            Yii::$container->set('rokorolov\parus\admin\contracts\RbacServiceInterface', 'rokorolov\parus\admin\rbac\RbacService');
        }

        \yii\base\Event::on(\yii\web\User::class, \yii\web\User::EVENT_BEFORE_LOGIN, function ($event) {
            $commandBus = Yii::createObject('rokorolov\parus\admin\contracts\CommandBusInterface');
            $commandBus->execute(new \rokorolov\parus\user\commands\AfterUserLoginCommand(
                $event->identity->getId()
            ));
        });

        $this->registerTranslation();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/user*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/user*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/user/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/user' => 'user.php',
                    'rokorolov/parus/user/authorization' => 'authorization.php',
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
