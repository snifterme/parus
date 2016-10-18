<?php

namespace rokorolov\parus\filemanager;

use Yii;
use yii\base\InvalidConfigException;

/**
 * gallery module definition class
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/filemanager';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\filemanager\controllers';

    /**
     * File Manager Module config.
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
                'responsiveFileManagerSrc' => null,
                'privateKey' => null,
            ],
            $this->config
        );

        if (!Yii::$container->has('rokorolov\parus\filemanager\widgets\FileManager')) {

            if (null === $src = $this->config['responsiveFileManagerSrc']) {
                throw new InvalidConfigException('The "responsiveFileManagerSrc" must be set.');
            }

            $url = Yii::getAlias($src);
            $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . 'lang=' . $this->config['language'];

            if (null !== $privateKey = $this->config['privateKey']) {
                $url .= '&akey=' . $privateKey;
            }

            Yii::$container->set('rokorolov\parus\filemanager\widgets\FileManager', 'rokorolov\parus\filemanager\widgets\ResponsiveFileManager');
            Yii::$container->set('rokorolov\parus\filemanager\widgets\ResponsiveFileManager', [
                'fileManagerSrc' => $url
            ]);
        }

        $this->registerTranslation();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/filemanager*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/filemanager*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/filemanager/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/filemanager' => 'filemanager.php',
                ]
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public static function t($photo, $message, $params = [], $language = null)
    {
        return \Yii::t('rokorolov/parus/' . $photo, $message, $params, $language);
    }
}
