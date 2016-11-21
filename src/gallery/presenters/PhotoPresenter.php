<?php

namespace rokorolov\parus\gallery\presenters;

use rokorolov\parus\admin\base\BasePresenter;
use rokorolov\parus\gallery\helpers\Settings;
use Yii;

/**
 * PostPresenter
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class PhotoPresenter extends BasePresenter
{
    public function image_thumb()
    {
        if (!empty($this->wrappedObject->photo_name)) {
            $id = $this->wrappedObject->album_id;
            $config = Settings::uploadAlbumConfig($id);
            return Settings::uploadFileSrc($id) . '/' . $id  . '/' . $this->wrappedObject->photo_name . '-' . $config['previewThumbName'] . '.' . $config['resizeDefaultImageExtension'];
        }
        return null;
    }
    
    public function image_original()
    {
        if (!empty($this->wrappedObject->photo_name)) {
            $id = $this->wrappedObject->album_id;
            $config = Settings::uploadAlbumConfig($id);
            return Settings::uploadFileSrc($id) . '/' . $id  . '/' . $this->wrappedObject->photo_name . '.' . $config['resizeDefaultImageExtension'];
        }
        return null;
    }
    
    public function translate($language = null)
    {
        if (empty($this->wrappedObject->translations)) {
            return null;
        }
        
        $language === null && $language = Settings::language();
        
        foreach($this->wrappedObject->translations as $translation) {
            if ((string)$translation->language === (string)$language) {
                return $translation;
            }
        }
        return null;
    }
}
