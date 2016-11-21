<?php

namespace rokorolov\parus\admin;

use rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status;
use Yii;
use yii\base\InvalidConfigException;

/**
 * This is the rokorolov\parus\admin\Module.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Module extends \yii\base\Module
{
    /**
     * @var string 
     */
    const MODULE_ID = 'admin';
    
    /**
     * @var string 
     */
    const APP_NAME = 'Parus';
     
    /**
     * @var string 
     */
    const BOOTSTRAP_CLASS = '\rokorolov\parus\admin\bootstrap\Bootstrap';
     
    /**
     * @var string 
     */
    const PANEL_DEFAULT_LANGUAGE = 'en';
    
    /**
     * @var string 
     */
    const PANEL_URL = 'admin';
    
    /**
     * @var string 
     */
    const FRONTEND_URL = '/';
    
    /**
     * @var boolean 
     */
    const ENABLE_INTL = true;
    
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'rokorolov\parus\admin\controllers';
    
    /**
     * 
     * @var @inheritdoc 
     */
    public $layout = '@rokorolov/parus/admin/theme/views/layouts/main';
    
    /**
     * Admin Module config.
     * 
     * @var array 
     */
    public $config = [];
    
    /**
     * 
     * @var array 
     */
    public $pageConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $blogConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $menuConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $galleryConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $languageConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $settingsConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $userConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $fileManagerConfig = [];
    
    /**
     * 
     * @var array 
     */
    public $dashboardConfig = [];
    
    /**
     * @var array 
     */
    protected $panelLanguages = [
        'en' => 'English',
        'ru' => 'Русский',
        'lv' => 'Latviešu'
    ];
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->config = array_replace(
            [
                'app.name' => self::APP_NAME,
                'bootstrapClass' => self::BOOTSTRAP_CLASS,
                'panel.defaultLanguage' => self::PANEL_DEFAULT_LANGUAGE,
                'panel.languages' => $this->panelLanguages,
                'panel.url' => self::PANEL_URL,
                'frontend.url' => self::FRONTEND_URL,
                'enableIntl' => self::ENABLE_INTL,
                'additionalComponents' => [],
                'additionalModules' => [],
            ],
            $this->config
        );
        
        $panelDefaultLanguage = $this->config['panel.defaultLanguage'];
        
        if (!array_key_exists($panelDefaultLanguage, $this->config['panel.languages'])) {
            throw new InvalidConfigException("Panel default language '$panelDefaultLanguage' does not match available languages.");
        }
        
        $languageComponent = new \rokorolov\parus\admin\components\Language();
        $languageComponent->defaultLanguage = $panelDefaultLanguage;
        $languageComponent->init();
        
        (new $this->config['bootstrapClass'])->init(Yii::$app);
        
        Yii::setAlias('@rokorolov/parus', dirname(__DIR__));
        
        Yii::$app->errorHandler->errorAction = 'admin/admin/error';

        Yii::$app->setComponents(array_replace([
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'rokorolov\parus\user\services\IdentityService',
                'loginUrl' => ['/admin/user/authorization/login'],
                'enableAutoLogin' => true,
                'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            ],
            'authManager' => [
                'class' => 'rokorolov\parus\admin\components\AuthManager',
                'itemFile' => '@rokorolov/parus/admin/rbac/items.php',
                'ruleFile' => '@rokorolov/parus/admin/rbac/rules.php',
                'assignmentFile' => '@rokorolov/parus/admin/rbac/assignments.php',
            ],
            'config' => [
                'class' => 'rokorolov\parus\settings\components\SettingsComponent'
            ],
            'assetManager' => [
                'class' => 'yii\web\AssetManager',
                'bundles' => [
                    'yii\bootstrap\BootstrapAsset' => [
                        'css' => []
                    ]
                ],
            ]
        ], $this->config['additionalComponents']));

        $panelLanguage = Yii::$app->language;
        $languagesHelper = Yii::createObject('rokorolov\parus\language\helpers\LanguageHelper');
        $languages = $languagesHelper->getLanguages();
        $defaultLanguage = Yii::$app->config->get('SITE.DEFAULT_LANGUAGE');
        $enableIntl = $this->config['enableIntl'];
        
        $contentLanguage = '';
        if (null !== Yii::$app->user->identity && null !== $userLocaleLanguage = Yii::$app->user->identity->language) {
            $contentLanguage = $userLocaleLanguage;
        } elseif (null !== $currentPanelLanguage = $languagesHelper->getKeyByCode(Yii::$app->language)) {
            $contentLanguage = $currentPanelLanguage;
        } else {
            $contentLanguage = $defaultLanguage;
        }
        
        $this->modules = array_replace([
            'blog' => [
                'class' => 'rokorolov\parus\blog\Module',
                'config' => array_replace([
                    'panelLanguage' => $panelLanguage,
                    'languages' => $languages,
                    'defaultLanguage' => $defaultLanguage,
                    'enableIntl' => $enableIntl,
                    'post.statuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED,
                        Status::STATUS_DRAFT,
                        Status::STATUS_ARCHIVED,
                    ],
                    'post.defaultStatus' => Status::STATUS_PUBLISHED,
                    'category.statuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED,
                    ],
                    'category.defaultStatus' => Status::STATUS_PUBLISHED,
                ], $this->blogConfig),
            ],
            'settings' => [
                'class' => 'rokorolov\parus\settings\Module',
                'config' => array_replace_recursive([
                    'panelLanguage' => $panelLanguage,
                    'configuration' => [
                        'SITE.DEFAULT_LANGUAGE' => [
                            'items' => $languagesHelper->getOptions(),
                        ]
                    ]
                ], $this->settingsConfig),
            ],
            'language' => [
                'class' => 'rokorolov\parus\language\Module',
                'config' => array_replace([
                    'defaultAppLanguage' => $defaultLanguage,
                    'enableIntl' => $enableIntl,
                    'language.statuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED
                    ],
                    'language.defaultStatus' => Status::STATUS_PUBLISHED,
                ], $this->languageConfig),
            ],
            'user' => [
                'class' => 'rokorolov\parus\user\Module',
                'config' => array_replace([
                    'enableIntl' => $enableIntl,
                ], $this->userConfig),
            ],
            'page' => [
                'class' => 'rokorolov\parus\page\Module',
                'config' => array_replace([
                    'panelLanguage' => $panelLanguage,
                    'languages' => $languages,
                    'defaultLanguage' => $defaultLanguage,
                    'enableIntl' => $enableIntl,
                    'pageStatuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED,
                        Status::STATUS_DRAFT,
                        Status::STATUS_ARCHIVED,
                    ],
                    'pageDefaultStatus' => Status::STATUS_PUBLISHED,
                ], $this->pageConfig),
            ],
            'menu' => [
                'class' => 'rokorolov\parus\menu\Module',
                'config' => array_replace([
                    'panelLanguage' => $panelLanguage,
                    'languages' => $languages,
                    'defaultLanguage' => $defaultLanguage,
                    'menu.statuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED,
                    ],
                    'menu.defaultStatus' => Status::STATUS_PUBLISHED,
                ], $this->menuConfig),
            ],
            'gallery' => [
                'class' => 'rokorolov\parus\gallery\Module',
                'config' => array_replace([
                    'panelLanguage' => $panelLanguage,
                    'language' => $contentLanguage,
                    'languages' => $languages,
                    'defaultLanguage' => $defaultLanguage,
                    'enableIntl' => $enableIntl,
                    'album.statuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED
                    ],
                    'album.defaultStatus' => Status::STATUS_PUBLISHED,
                    'photo.statuses' => [
                        Status::STATUS_PUBLISHED,
                        Status::STATUS_UNPUBLISHED,
                    ],
                    'photo.defaultStatus' => Status::STATUS_PUBLISHED,
                ], $this->galleryConfig),
            ],
            'filemanager' => [
                'class' => 'rokorolov\parus\filemanager\Module',
                'config' => array_replace([
                    'panelLanguage' => $panelLanguage,
                ], $this->fileManagerConfig),
            ],
            'dashboard' => [
                'class' => 'rokorolov\parus\dashboard\Module',
                'config' => array_replace([
                    'panelLanguage' => $panelLanguage,
                    'enableIntl' => $enableIntl,
                ], $this->dashboardConfig),
            ],
        ], $this->config['additionalModules']);
        
        $this->registerTranslation();
        
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    protected function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/parus/admin*'])) {
            Yii::$app->i18n->translations['rokorolov/parus/admin*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/admin/messages',
                'forceTranslation' => false,
                'fileMap' => [
                    'rokorolov/parus/admin' => 'admin.php',
                ]
            ];
        }
    }
    
    /**
     * Translates a message to the specified language.
     *
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('rokorolov/parus/' . $category, $message, $params, $language);
    }
}
