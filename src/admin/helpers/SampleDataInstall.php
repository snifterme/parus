<?php

namespace rokorolov\parus\admin\helpers;

use rokorolov\parus\menu\models;
use rokorolov\parus\blog\models\Category;
use Yii;

/**
 * SampleDataInstall
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SampleDataInstall
{
    public function init()
    {
        Yii::$app->db->createCommand()->insert(models\MenuType::tableName(), [
            'id' => 2,
            'menu_type_alias' => 'main_menu',
            'title' => 'Main Menu',
            'description' => ''
        ])->execute();
        
        Yii::$app->db->createCommand()->batchInsert(models\Menu::tableName(), [
            'status',
            'title',
            'parent_id',
            'menu_type_id',
            'link',
            'note',
            'language',
            'depth',
            'lft',
            'rgt',
        ],
        [
            [
                1,
                'Home',
                1,
                2,
                '/site/index',
                '',
                Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemCode(),
                1,
                2,
                3,
            ],
            [
                1,
                'About',
                1,
                2,
                '/site/about',
                '',
                Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemCode(),
                1,
                4,
                5,
            ],
            [
                1,
                'Blog',
                1,
                2,
                '/blog',
                '',
                Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemCode(),
                1,
                6,
                7,
            ],
            [
                1,
                'Contact',
                1,
                2,
                '/site/contact',
                '',
                Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemCode(),
                1,
                8,
                9,
            ],
        ])->execute();
        
        Yii::$app->db->createCommand()->update(models\Menu::tableName(), ['rgt' => 10], 'id = 1')->execute();
        
        $userId = Yii::createObject('rokorolov\parus\user\helpers\DefaultInstall')->getSystemId();
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        
        Yii::$app->db->createCommand()->batchInsert(Category::tableName(), [
            'status',
            'parent_id',
            'image',
            'title',
            'slug',
            'description',
            'created_by',
            'created_at',
            'modified_by',
            'modified_at',
            'depth',
            'lft',
            'rgt',
            'language',
            'reference',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ],
        [
            [
                'status' => 1,
                'parent_id' => Yii::createObject('rokorolov\parus\blog\helpers\DefaultInstall')->getSystemRootId(),
                'image' => null,
                'title' => 'Blog',
                'slug' => 'blog',
                'description' => '',
                'created_by' => $userId,
                'created_at' => $datetime,
                'modified_by' => $userId,
                'modified_at' => $datetime,
                'depth' => 1,
                'lft' => 4,
                'rgt' => 5,
                'language' => Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall')->getSystemCode(),
                'reference'  => null,
                'meta_title' => '',
                'meta_keywords' => '',
                'meta_description' => '',
            ]
        ])->execute();
        
        Yii::$app->db->createCommand()->update(Category::tableName(), ['rgt' => 6], 'id = 1')->execute();
    }
}
