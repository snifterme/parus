<?php

namespace rokorolov\parus\gallery\services;

use rokorolov\parus\gallery\contracts\GalleryServiceInterface;
use rokorolov\parus\gallery\helpers\Settings;
use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use yii\web\UploadedFile;

/**
 * GalleryService
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class GalleryService implements GalleryServiceInterface
{
    public function getAlbumWithPhotos($id)
    {
        $album = Yii::createObject('rokorolov\parus\gallery\repositories\AlbumReadRepository')
            ->where(['al.language' => Settings::language()])
            ->findById($id, ['translation', 'photo.translations']);
        
        $models = [];
        foreach($album->photos as $photo) {
            $models[$photo->id] = Yii::createObject('rokorolov\parus\gallery\models\form\PhotoForm')->setData($photo);
        }
        
        $album->photos = $models;
        
        return $album;
    }
    
    public function loadPhotos($album, $post)
    {
        $success = false;

        if (empty($post) || !isset($post['PhotoLangForm'])) {
            return $success;
        }
        $translations = $post['PhotoLangForm'];
        
        foreach($album->photos as $index => $photo) {
            if (!empty($translations[$index])) {
                $photo->load(['PhotoLangForm' => $translations[$index]]) && $success = true;
            }
        }
            
        return $success;
    }
    
    public function validatePhotos($album)
    {
        $success = true;
        
        foreach ($album->photos as $photo) {
            !$photo->validate() && $success = false;
        }
        
        return $success;
    }

    public function validateImage($imageFile, $rules)
    {
        if (!$imageFile instanceof UploadedFile) {
            throw new InvalidParamException("'imageFile' must be an instance of " . UploadedFile::class);
        }
        
        if ($imageFile->getHasError()) {
            return [
                'error' => true,
                'message' => 'Upload failed with error code ' . $imageFile->error
            ];
        }

        $model = new DynamicModel(compact('imageFile'));

        $rules['allowedExtensions'] && $model->addRule('imageFile', 'file', ['extensions' => $rules['allowedExtensions']]);
        $rules['allowedMimeTypes'] && $model->addRule('mimeTypes', 'file', ['mimeTypes' => $rules['allowedMimeTypes']]);
        $rules['maxFileSize'] && $model->addRule('imageFile', 'file', ['maxSize' => $rules['maxFileSize']]);
        $rules['minFileSize'] && $model->addRule('imageFile', 'file', ['minSize' => $rules['minFileSize']]);
        $rules['maxImageWidth'] && $model->addRule('imageFile', 'image', ['maxWidth' => $rules['maxImageWidth']]);
        $rules['maxImageHeight'] && $model->addRule('imageFile', 'image', ['maxHeight' => $rules['maxImageHeight']]);
        $rules['minImageWidth'] && $model->addRule('imageFile', 'image', ['minWidth' => $rules['minImageWidth']]);
        $rules['minImageHeight'] && $model->addRule('imageFile', 'image', ['minHeight' => $rules['minImageHeight']]);

        $model->validate();

        if ($model->hasErrors()) {
            return [
                'error' => true,
                'message' => $model->getFirstError('imageFile')
            ];
        }

        return [
            'error' => false,
        ];
    }
}
