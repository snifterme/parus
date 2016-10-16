<?php

namespace rokorolov\parus\admin\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

 /**
 * This is the DateTimeBehavior.
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class DateTimeBehavior extends \yii\base\Behavior
{
    /**
     *
     * @var array 
     */
    public $dateTimeAttributes;
    
    /**
     *
     * @var string 
     */
    public $dateTimeFormatForSave = 'php:Y-m-d H:i:s';
    
    /**
     *
     * @var string 
     */
    public $dateTimeFormatForOutput = 'php:d-m-Y H:i:s';
    
    /**
     *
     * @var array 
     */
    protected $_events = [
        ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
        ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->dateTimeAttributes === null) {
            throw new InvalidConfigException('The "dateTimeAttributes" property must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return $this->_events;
    }
    
    /**
     * 
     * @param type $insert
     */
    public function beforeSave($insert) 
    {
        foreach (array_keys($this->dateTimeAttributes) as $dateAttribute) {
            if ($this->owner->$dateAttribute != null && $this->isValidDate($this->owner->$dateAttribute) == true) {
                $this->owner->{$this->dateTimeAttributes[$dateAttribute]} = Yii::$app->formatter->asDatetime($this->owner->$dateAttribute, $this->dateTimeFormatForSave);
            } else {
                $this->owner->{$this->dateTimeAttributes[$dateAttribute]} = '0000-00-00 00:00:00';
            }
        }
    }
       
    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
         return array_key_exists($name, $this->dateTimeAttributes) ?: parent::canGetProperty($name, $checkVars);
    }
    
    /**
    * @inheritdoc
    */
    public function canSetProperty($name, $checkVars = true)
    {
        return array_key_exists($name, $this->dateTimeAttributes) ?: parent::canSetProperty($name, $checkVars);
    }
    
    /**
    * @inheritdoc
    */
    public function __get($name)
    {
        return $this->isValidDate($this->owner->{$this->dateTimeAttributes[$name]}) !== false ? Yii::$app->formatter->asDatetime($this->owner->{$this->dateTimeAttributes[$name]}, $this->dateTimeFormatForOutput) : null;
    }
    
    /**
    * @inheritdoc
    */
    public function __set($name, $value)
    {
        $this->owner->$name = $value;
    }
    
    /**
     * Validate date.
     * 
     * @param type $value
     * @return boolean
     */
    public function isValidDate($value)
    {
        $date = \DateTime::createFromFormat('Y-m-d', $value);
        
        if (\DateTime::getLastErrors()['warning_count'] > 0) {
            return false;
        }
        
        return true;
    }
}
