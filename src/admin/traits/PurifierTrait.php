<?php

namespace rokorolov\parus\admin\traits;

use yii\helpers\HtmlPurifier;

/**
 * PurifierTrait
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
trait PurifierTrait
{
    /**
     * @var array Text attributes array
     */
    public $textPurifierSettings = [
        'HTML.AllowedElements' => '',
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
    ];
    
    /**
     * @var array Purifier settings
     */
    public $purifierSettings = [
        'AutoFormat.RemoveEmpty' => true,
        'AutoFormat.RemoveEmpty.RemoveNbsp' => true,
        'AutoFormat.Linkify' => true,
        'HTML.Nofollow' => true
    ];
    
    /**
     * @var Purifier
     */
    protected $purifier;
    
    
    /**
     * Purify attributes
     */
    public function purify($attribute)
    {
        return $this->getPurifier()->process($attribute, $this->purifierSettings);
    }
    
    /**
     * Purify text attributes
     */
    public function textPurify($attribute)
    {
        return $this->getPurifier()->process($attribute, $this->textPurifierSettings);
    }
    
    protected function getPurifier()
    {
        if (null === $this->purifier) {
            $this->purifier = new HtmlPurifier;
        }
        return $this->purifier;
    }
}