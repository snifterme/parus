<?php

namespace rokorolov\parus\admin\theme\widgets\statusaction\helpers;

use rokorolov\helpers\Html;
use rokorolov\parus\admin\theme\widgets\statusaction\contracts\StatusInterface;
use Yii;

/**
 * Status
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class Status implements StatusInterface
{
    const STATUS_UNPUBLISHED = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_DRAFT = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_TRASHED = -1;

    public function getStatusOptions()
    {
        self::registerTranslation();
        
        return [
            self::STATUS_PUBLISHED => Yii::t('rokorolov/statusaction', 'Published'),
            self::STATUS_UNPUBLISHED => Yii::t('rokorolov/statusaction', 'Unpublished'),
            self::STATUS_DRAFT => Yii::t('rokorolov/statusaction', 'Draft'),
            self::STATUS_ARCHIVED => Yii::t('rokorolov/statusaction', 'Archived'),
            self::STATUS_TRASHED => Yii::t('rokorolov/statusaction', 'Trashed'),
        ];
    }

    public function getStatusActions()
    {
        self::registerTranslation();
        
        return  [
            self::STATUS_PUBLISHED => [
                'label' => Yii::t('rokorolov/statusaction', 'Publish'),
                'icon' => 'check fa-fw',
                'type' => $this->getStatusHtmlTypes()[self::STATUS_PUBLISHED],
                'changeTo' => self::STATUS_UNPUBLISHED,
            ],
            self::STATUS_UNPUBLISHED => [
                'label' => Yii::t('rokorolov/statusaction', 'Unpublish'),
                'icon' => 'times fa-fw',
                'type' => $this->getStatusHtmlTypes()[self::STATUS_UNPUBLISHED],
                'changeTo' => self::STATUS_PUBLISHED,
            ],
            self::STATUS_DRAFT => [
                'label' => Yii::t('rokorolov/statusaction', 'Draft'),
                'icon' => 'pencil fa-fw',
                'type' => $this->getStatusHtmlTypes()[self::STATUS_DRAFT],
                'changeTo' => self::STATUS_PUBLISHED,
            ],
            self::STATUS_ARCHIVED => [
                'label' => Yii::t('rokorolov/statusaction', 'Archive'),
                'icon' => 'archive fa-fw',
                'type' => $this->getStatusHtmlTypes()[self::STATUS_ARCHIVED],
                'changeTo' => self::STATUS_PUBLISHED,
            ],
            self::STATUS_TRASHED => [
                'label' => Yii::t('rokorolov/statusaction', 'Trash'),
                'icon' => 'trash fa-fw',
                'type' => $this->getStatusHtmlTypes()[self::STATUS_TRASHED],
                'changeTo' => self::STATUS_PUBLISHED,
            ],
        ];
    }

    public function getStatusHtmlTypes()
    {
        return [
            self::STATUS_PUBLISHED => Html::TYPE_SUCCESS,
            self::STATUS_UNPUBLISHED => Html::TYPE_WARNING,
            self::STATUS_DRAFT => Html::TYPE_INFO,
            self::STATUS_ARCHIVED => Html::TYPE_DEFAULT,
            self::STATUS_TRASHED => Html::TYPE_DANGER
        ];
    }
    
    public static function registerTranslation()
    {
        if (!isset(Yii::$app->i18n->translations['rokorolov/statusaction'])) {
            Yii::$app->i18n->translations['rokorolov/statusaction'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@rokorolov/parus/admin/theme/widgets/statusaction/messages',
                'forceTranslation' => true,
                'fileMap' => [
                    'rokorolov/statusaction' => 'statusaction.php',
                ]
            ];
        }
    }
}
