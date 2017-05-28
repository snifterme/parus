<?php

namespace rokorolov\parus\admin\base;

use yii\db\Migration;

/**
 * This is the BaseMigration
 *
 * @author jkmssoft
 * @package rokorolov\parus\admin\base
 */
class BaseMigration extends Migration
{
    /**
     * @var string
     */
    protected $tableOptions;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->db->driverName === 'mysql') {
            $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
    }
}