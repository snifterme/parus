<?php

namespace rokorolov\parus\admin\bootstrap;

use Yii;
use yii\helpers\Url;

/**
 * This is the Bootstrap of rokorolov\parus\admin\bootstrap\Bootstrap.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Bootstrap
{
    public function init()
    {
        // Basic dependencies
        Yii::$container->set('rokorolov\parus\admin\contracts\CommandBusInterface', 'rokorolov\parus\admin\commands\CommandBus');
        Yii::$container->set('rokorolov\parus\admin\theme\widgets\statusaction\contracts\StatusInterface', 'rokorolov\parus\admin\theme\widgets\statusaction\helpers\Status');
        Yii::$container->set('StatusService', 'rokorolov\parus\admin\theme\widgets\statusaction\services\StatusService');
        
        // Inject presenters to read repositories
        Yii::$container->set('rokorolov\parus\menu\repositories\MenuReadRepository', ['presenter' => 'rokorolov\parus\menu\presenters\MenuPresenter']);
        Yii::$container->set('rokorolov\parus\menu\repositories\MenuTypeReadRepository', ['presenter' => 'rokorolov\parus\menu\presenters\MenuTypePresenter']);
        Yii::$container->set('rokorolov\parus\blog\repositories\PostReadRepository', ['presenter' => 'rokorolov\parus\blog\presenters\PostPresenter']);
        Yii::$container->set('rokorolov\parus\blog\repositories\CategoryReadRepository', ['presenter' => 'rokorolov\parus\blog\presenters\CategoryPresenter']);
        Yii::$container->set('rokorolov\parus\gallery\repositories\AlbumReadRepository', ['presenter' => 'rokorolov\parus\gallery\presenters\AlbumPresenter']);
        Yii::$container->set('rokorolov\parus\gallery\repositories\PhotoReadRepository', ['presenter' => 'rokorolov\parus\gallery\presenters\PhotoPresenter']);
        Yii::$container->set('rokorolov\parus\page\repositories\PageReadRepository', ['presenter' => 'rokorolov\parus\page\presenters\PagePresenter']);
        Yii::$container->set('rokorolov\parus\user\repositories\UserReadRepository', ['presenter' => 'rokorolov\parus\user\presenters\UserPresenter']);
        Yii::$container->set('rokorolov\parus\language\repositories\LanguageReadRepository', ['presenter' => 'rokorolov\parus\language\presenters\LanguagePresenter']);
        
        // Settings Module dependencies
        Yii::$container->set('rokorolov\parus\settings\contracts\SettingsServiceInterface', 'rokorolov\parus\settings\services\SettingsService');
        
        // Gallery Module dependencies
        Yii::$container->set('rokorolov\parus\gallery\contracts\GalleryServiceInterface', 'rokorolov\parus\gallery\services\GalleryService');
        
        // User Module dependencies
        Yii::$container->set('yii\web\IdentityInterface', 'rokorolov\parus\user\services\IdentityService');
        
        // Widget dependencies
        Yii::$container->set('rokorolov\parus\admin\theme\widgets\actioncolumn\ActionColumn', [
            'mode' => 'custom',
        ]);
        
        // Image Manager
        if (!Yii::$container->has('ImageManipulationManager')) {
            Yii::$container->set('ImageManipulationManager', function() {
                return new \Intervention\Image\ImageManager(['driver' => 'gd']);
            });
        }
        
        if (!Yii::$container->has(\rokorolov\parus\admin\theme\widgets\Redactor::class)) {
            Yii::$container->set('rokorolov\parus\admin\theme\widgets\Redactor', 'vova07\imperavi\Widget');
            Yii::$container->set('vova07\imperavi\Widget', [
                'settings' => [
                    'buttonSource' => true,
                    'minHeight' => 250,
                    'toolbarFixed' => false,
                    'replaceDivs' => false,
                    'formatting' => ['p', 'blockquote', 'pre', 'h1', 'h2', 'h3', 'h4', 'h5', 'div'],
                    'plugins' => [
                        'imagemanager',
                        'fullscreen',
                    ],
                    'imageUpload' => Url::to('imperavi-image-upload'),
                    'imageManagerJson' => Url::to('imperavi-get')
                ]
            ]);
        }
        
        \yii\base\Event::on(\rokorolov\parus\language\models\Language::class, \rokorolov\parus\language\models\Language::EVENT_AFTER_INSERT, function ($event) {
            $commandBus = Yii::createObject('rokorolov\parus\admin\contracts\CommandBusInterface');
            $commandBus->execute(new \rokorolov\parus\gallery\commands\CreateAlbumTranslationCommand(
                $event->sender->id
            ));
            $commandBus->execute(new \rokorolov\parus\gallery\commands\CreatePhotoTranslationCommand(
                $event->sender->id
            ));
        });
    }
}
