<?php

namespace rokorolov\parus\user\helpers;

use Yii;

/**
 * SecurityHelper
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class SecurityHelper
{
    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password, $passwordHash)
    {
        return Yii::$app->security->validatePassword($password, $passwordHash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function generatePasswordHash($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        return Yii::$app->security->generateRandomString() . '_' . time();
    }
}
