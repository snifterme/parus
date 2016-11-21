<?php

namespace rokorolov\parus\settings\models\form;

use rokorolov\parus\settings\helpers\Settings;
use Yii;
use yii\base\Model;

/**
 * SettingsUpdateForm
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SettingsUpdateForm extends Model
{
    public $id;
    public $param;
    public $value;
    public $default;
    public $type;
    public $order;
    public $created_at;
    public $updated_at;
    
    public $isValueChanged = true;

    private $oldValue;
    private $wrappedObject;
    
    public function __construct(
        $config = []
    ) {
        parent::__construct($config);
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'default'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'id',
            'param',
            'value',
            'default'
        ];
    }

    /**
     * 
     * @return type
     */
    public function afterValidate()
    {
        if (strcmp($this->oldValue, $this->value) === 0) {
            $this->isValueChanged = false;
        }
        return parent::afterValidate();
    }

    /**
     * 
     * @param type $options
     * @return type
     */
    public function getOptions($options = [])
    {
        $config = Settings::configuration();
        
        if (isset($config[$this->param]['items'])) {
            $options = $config[$this->param]['items'];
        }
        
        return $options;
    }

    /**
     * 
     * @return type
     */
    public function getLabel()
    {
        return $this->wrappedObject->translation->label;
    }
    
    /**
     * 
     * @return type
     */
    public function getData()
    {
        return $this->reverseTransform();
    }
    
    /**
     * 
     * @param type $data
     * @return type
     */
    public function setData($data = null)
    {
        return $this->transform($data);
    }

    /**
     * 
     * @param type $data
     * @return 
     */
    protected function transform($data = null)
    {
        if ($data !== null) {
            $this->wrappedObject = $data;
            $this->id = $this->wrappedObject->id;
            $this->param = $this->wrappedObject->param;
            $this->value = $this->wrappedObject->value;
            $this->oldValue = $this->wrappedObject->value;
            $this->default = $this->wrappedObject->default;
            $this->type = $this->wrappedObject->type;
            $this->order = $this->wrappedObject->order;
            $this->created_at = $this->wrappedObject->created_at;
            $this->updated_at = $this->wrappedObject->updated_at;
        }
        
        return $this;
    }

    protected function reverseTransform()
    {
        return $this->getAttributes();
    }
}