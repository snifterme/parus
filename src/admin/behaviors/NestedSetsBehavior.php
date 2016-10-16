<?php

namespace rokorolov\parus\admin\behaviors;

 /**
 * This is the NestedSetsBehavior.
 * Managing trees stored in database as nested sets.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class NestedSetsBehavior extends \creocoder\nestedsets\NestedSetsBehavior
{
    const ORDER_FIRST = -1;
    
    const ORDER_LAST = -2;
    
    /**
     * @var boolean 
     */
    public $allowNewParent = true;
    
    /**
     * @var boolean 
     */
    public $allowNewOrder = true;
    
    /**
     * @var string 
     */
    public $newRecordAdd = 'prependTo';
    
    /**
     * @var string 
     */
    public $parentAttribute = 'parent_id';
    
    /**
     * @var string 
     */
    public $orderAttribute = 'position';
    
    /**
     * Managing nested sets.
     * 
     * @return boolean
     */
    public function saveWithNode()
    {
        return $this->modifyTree();
    }
    
    /**
     * @return boolean
     */
    protected function modifyTree()
    {
        $modelName = $this->owner->className();
        
        if ($this->owner->isNewRecord) {
            return $this->owner->{$this->newRecordAdd}($modelName::findOne($this->owner->{$this->parentAttribute}));
        } elseif ($this->allowNewParent && $this->isNewParent()) {
            return $this->owner->appendTo($modelName::findOne($this->owner->{$this->parentAttribute}));
        } elseif ($this->allowNewOrder && $this->isNewOrderPosition()) {
            switch ($this->owner->{$this->orderAttribute})
            {
                case self::ORDER_FIRST:
                    return $this->owner->prependTo($this->owner->parents(1)->one());
                    break;
                case self::ORDER_LAST:
                    return $this->owner->appendTo($this->owner->parents(1)->one());
                    break;
                default:
                    return $this->owner->insertAfter($modelName::findOne($this->owner->{$this->orderAttribute}));
            }
        }
        return $this->owner->save();
    }
    
    /**
     * Check if node has a new parent.
     * 
     * @return boolean
     */
    protected function isNewParent()
    {
        return (int)$this->owner->getOldAttribute($this->parentAttribute) !== (int)$this->owner->{$this->parentAttribute};
    }
    
    /**
     * Check if node has a new order position.
     * 
     * @return boolean
     */
    protected function isNewOrderPosition()
    {
        return (int)$this->owner->primaryKey !== (int)$this->owner->{$this->orderAttribute};
    }
}
