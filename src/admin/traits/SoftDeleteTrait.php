<?php

namespace rokorolov\parus\admin\traits;

use yii\base\ModelEvent;

/**
 * SoftDeleteTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait SoftDeleteTrait
{
    /**
     * @var boolean 
     */
    private static $forceDelete = false;

    /**
     * @var string 
     */
    public static $eventBeforeSoftDelete = 'beforeSoftDelete';

    /**
     * @var string 
     */
    public static $eventAfterSoftDelete = 'afterSoftDelete';

    /**
     * @var string 
     */
    public static $eventBeforeForceDelete = 'beforeForceDelete';

    /**
     * @var string 
     */
    public static $eventAfterForceDelete = 'afterForceDelete';

    /**
     * @var string 
     */
    public static $eventBeforeRestore = 'beforeRestoreSoftDelete';

    /**
     * @var string 
     */
    public static $eventAfterRestore = 'afterRestoreSoftDelete';

    /**
     * 
     * @return type
     */
    public static function find()
    {
        $query = parent::find();
        if (self::isSoftDeleteEnable()) {
            $query->andWhere([self::getSoftDeleteAttribute() => null]);
        }
        return $query;
    }
    
    /**
     * 
     * @return type
     */
    public function withTrashed()
    {
        return parent::find();
    }
    
    /**
     * 
     * @return type
     */
    public function onlyTrashed()
    {
        return parent::find()->andWhere(['not', [self::getSoftDeleteAttribute() => null]]);
    }
    
    /**
     * 
     * @return boolean
     */
    public function softDelete()
    {
        $result = false;
        if ($this->beforeSoftDelete()) {
            $result = $this->softDeleteInternal();
 
            $this->afterSoftDelete();
        }
        return $result;
    }
    
    /**
     * 
     * @return type
     */
    protected function beforeSoftDelete()
    {
        $event = new ModelEvent;
        $this->trigger(self::$eventBeforeSoftDelete, $event);

        return $event->isValid;
    }
    
    /**
     * 
     * @return type
     */
    protected function afterSoftDelete()
    {
        $this->trigger(self::$eventAfterSoftDelete);
    }
    
    /**
     * 
     * @return type
     */
    public function restore()
    {
        $result = false;
        if ($this->beforeRestore()) {
            $result = $this->restoreInternal();
            $this->afterRestore();
            
        }
        return $result;
    }
    
    /**
     * 
     * @return type
     */
    protected function restoreInternal()
    {
        $attribute = self::getSoftDeleteAttribute();

        return $this->updateAttributes([$attribute => null]);
    }
    
    /**
     * 
     * @return type
     */
    protected function beforeRestore()
    {
        $event = new ModelEvent;
        $this->trigger(self::$eventBeforeRestore, $event);

        return $event->isValid;
    }
    
    /**
     * 
     * @return type
     */
    protected function afterRestore()
    {
        $this->trigger(self::$eventAfterRestore);
    }
    
    /**
     * 
     * @return type
     */
    protected function beforeForceDelete()
    {
        $event = new ModelEvent;
        $this->trigger(self::$eventBeforeForceDelete, $event);

        return $event->isValid;
    }
    
    /**
     * 
     * @return type
     */
    protected function afterForceDelete()
    {
        $this->trigger(self::$eventAfterForceDelete);
    }

    /**
     * 
     */
    public function forceDelete()
    {
        self::$forceDelete = true;
        $this->delete();
        self::$forceDelete = false;
    }
    
    /**
     * 
     * @return type
     */
    protected function softDeleteInternal()
    {
        $timestamp = date('Y:m:d H:i:s');
        $attribute = self::getSoftDeleteAttribute();
        return $this->updateAttributes([$attribute => $timestamp]);
    }
    
    /**
     * Get the name of the "deleted at" column.
     *
     * @return string
     */
    protected static function getSoftDeleteAttribute()
    {
        return 'deleted_at';
    }
    
    /**
     * Is Soft Delete Enabled
     * 
     * @return boolean
     */
    protected static function isSoftDeleteEnable()
    {
        return true;
    }
    
    /**
     *
     * @return string
     */
    protected function invokeDeleteEvents()
    {
        return true;
    }
    
    /**
     * 
     * @param type $event
     */
    public function beforeDelete()
    {
        if (!self::$forceDelete && self::isSoftDeleteEnable()) {
            $this->softDelete();
            return false;
        } else {
            if ($this->beforeForceDelete()) {
                return parent::beforeDelete();
            }
            return false;
        }
    }
    
    /**
     * 
     * @param type $event
     */
    public function afterDelete()
    {
        $this->afterForceDelete();
        
        return parent::afterDelete();
    }
}
