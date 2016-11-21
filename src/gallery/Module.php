<?php

namespace rokorolov\parus\gallery;

use rokorolov\parus\gallery\helpers\Settings;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * gallery module definition class
 */
class Module extends \yii\base\Module
{
    const MODULE_ID = 'admin/gallery';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\gallery\controllers';

    /**
     * Gallery Module config.
     *
     * @var type
     */
    public $config = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->config['album.introImageConfig'] = array_replace(
            [
                'uploadPath' => '@webroot/uploads/gallery',
                'uploadSrc' => '@web/uploads/gallery',
                'allowedExtensions' => ['jpg', 'jpeg', 'png'],
                'allowedMimeTypes' => ['image/*'],
                'minSize' => null,
                'maxSize' => null,
                'minWidth' => null,
                'maxWidth' => null,
                'minHeight' => null,
                'maxHeight' => null,
                'extension' => 'jpg',
                'dir' => 'intro',
                'transformations' => [],
            ], ArrayHelper::getValue($this->config, 'album.introImageConfig', [])
        );
        
        $this->config['uploadImageConfig'] = array_replace(
            [
                'maxFileSize' => 0,
                'maxImageWidth' => 0,
                'maxImageHeight' => 0,
                'minFileSize' => 0,
                'minImageWidth' => 0,
                'minImageHeight' => 0,
                'maxFileCount' => 10,
                'resizeImageQuality' => 90,
                'resizeDefaultImageExtension' => 'jpg',
                'uploadFilePath' => '@webroot/uploads/gallery',
                'uploadFileSrc' => '@web/uploads/gallery',
                'nameFileCreator' => null,
                'pathFileCreator' => null,
                'allowedExtensions' => ['jpg', 'jpeg', 'gif', 'png', 'txt'],
                'allowedFileTypes' => ['image', 'html', 'text', 'video', 'audio', 'flash', 'object'],
                'allowedMimeTypes' => [],
                'allowedHtmlTags' => false,
                'imageTransformations' => [],
                'previewThumbDimensions' => [98, 98],
                'previewThumbName' => 'preview-thumb',
            ], ArrayHelper::getValue($this->config, 'uploadImageConfig', [])
        );
        
        $this->config['translatableConfig'] = array_replace(
            [
                'language' => isset($this->config['language']) ? $this->config['language'] : 'en',
                'languages' => isset($this->config['languages']) ? $this->config['languages'] : ['en' => 'English'],
                'defaultLanguage' => isset($this->config['defaultLanguage']) ? $this->config['defaultLanguage'] : 'en',
                'translationLanguageAttribute' => 'language',
                'automaticEmptyFiledsTranslation' => true,
                'automaticEmptyFiledsTranslationException' => [],
            ], ArrayHelper::getValue($this->config, 'translatableConfig', [])
        );
        
        $this->config = array_replace(
            [
                'panelLanguage' => 'en',
                'language' => 'en',
                'languages' => ['en' => 'English'],
                'defaultLanguage' => 'en',
                'enableIntl' => true,
                'album.statuses' => [],
                'album.defaultStatus' => null,
                'album.managePageSize' => 10,
                'album.introImageConfig' => [],
                'photo.statuses' => [],
                'photo.defaultStatus' => null,
                'photo.managePageSize' => 10,
                'translatableConfig' => [],
                'uploadImageConfig' => [],
                'uploadImageMapConfig' => []
            ],
            $this->config
        );

        $languageOptions = ArrayHelper::map($this->config['languages'], 'id', 'title');
        
        Yii::$container->set('rokorolov\parus\admin\theme\widgets\translatable\TranslatableSwithButton', [
            'defaultLanguage' => $this->config['defaultLanguage'],
            'languages' => $languageOptions,
        ]);

        Yii::$container->set('rokorolov\parus\admin\theme\widgets\translatable\Translatable', [
            'languages' => $languageOptions,
        ]);

        $this->registerTranslation();

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/gallery*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/gallery*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/gallery/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/gallery' => 'gallery.php',
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
