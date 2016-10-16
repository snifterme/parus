<?php

namespace rokorolov\parus\admin\traits;

use rokorolov\parus\admin\contracts\HasTagDependency;
use rokorolov\parus\admin\helpers\TagDependencyNamingHelper;
use rokorolov\parus\admin\exceptions\TagDependencyException;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;

/**
 * This is the TagDependencyTrait.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait TagDependencyTrait
{
    protected $tagId;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this instanceof HasTagDependency) {
            throw new TagDependencyException('Class ' . self::class . ' must be an instance of the ' . HasTagDependency::class);
        }
        
        $this->tagId = $this->getDependencyTagId();
        
        $this->setEvents();
    }

    /**
     * @inheritdoc
     */
    protected function setEvents()
    {
        $this->on(ActiveRecord::EVENT_AFTER_DELETE, [$this, 'invalidateTags']);
        $this->on(ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'invalidateTags']);
        $this->on(ActiveRecord::EVENT_AFTER_INSERT, [$this, 'invalidateTags']);
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
                TagDependencyNamingHelper::getCommonTag($this->tagId),
                TagDependencyNamingHelper::getObjectTag($this->tagId, $this->primaryKey),
            ]
        );
        return true;
    }
}
