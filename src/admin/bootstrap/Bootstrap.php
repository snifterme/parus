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
        
        \yii\base\Event::on(\rokorolov\parus\language\models\Language::class, \rokorolov\parus\blog\models\Post::EVENT_AFTER_INSERT, function ($event) {
            $commandBus = Yii::createObject('rokorolov\parus\admin\contracts\CommandBusInterface');
            $commandBus->execute(new \rokorolov\parus\gallery\commands\CreateAlbumTranslationCommand(
                $event->sender->lang_code
            ));
            $commandBus->execute(new \rokorolov\parus\gallery\commands\CreatePhotoTranslationCommand(
                $event->sender->lang_code
            ));
        });
    }
}
