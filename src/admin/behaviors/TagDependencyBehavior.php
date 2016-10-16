<?php

namespace rokorolov\parus\admin\behaviors;

use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * This is the TagDependencyBehavior.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class TagDependencyBehavior extends \yii\base\Behavior
{
    public $tagName;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->tagName === null) {
            throw new \yii\base\InvalidConfigException("You must setup the 'tagName' property.");
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'invalidateTags',
            ActiveRecord::EVENT_AFTER_UPDATE => 'invalidateTags',
            ActiveRecord::EVENT_AFTER_INSERT => 'invalidateTags',
        ];
    }
    
    /**
     * Invalidates all of the cached data items that are associated with any of the specified tags.
     * 
     * @param type $insert
     */
    public function invalidateTags() 
    {
        TagDependency::invalidate(
            Yii::$app->cache,
            [
                self::getCommonTag($this->tagName),
                self::getObjectTag($this->tagName, $this->owner->primaryKey),
            ]
        );
        return true;
    }
    
    /**
     * Get common tag key.
     * 
     * @param string $class
     * @return string
     */
    public static function getCommonTag($class)
    {
        return $class . ':CommonTag';
    }
    
    /**
     * Get object tag key.
     * 
     * @param string $class
     * @param integer $id
     * @return string
     */
    public static function getObjectTag($class, $id)
    {
        return $class . ':ObjectTag:' . $id;
    }
}
