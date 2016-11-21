<?php

use rokorolov\parus\language\models;
use rokorolov\parus\language\contracts\DefaultInstallInterface;
use yii\db\Migration;

class m160722_131746_init_module_language extends Migration
{
    public $settings;
    
    public function init()
    {
        $this->settings = Yii::createObject('rokorolov\parus\language\helpers\DefaultInstall');
        
        if (!$this->settings instanceof DefaultInstallInterface) {
            throw new Exception("Migration failed. Class rokorolov\parus\language\helpers\DefaultInstall must be an instance of rokorolov\parus\language\contracts\DefaultInstallInterface");
        }

        parent::init();
    }
    
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable(models\Language::tableName(), [
            'id' => $this->primaryKey(10)->unsigned(),
            'title' => $this->string(128)->notNull(),
            'status' => $this->smallInteger(2)->notNull(),
            'order' => $this->integer(11)->notNull()->unsigned()->defaultValue(0),
            'lang_code' => $this->string(7)->notNull(),
            'image' => $this->string(64)->notNull(),
            'date_format' => $this->string(32)->notNull()->defaultValue('Y-m-d'),
            'date_time_format' => $this->string(32)->notNull()->defaultValue('Y-m-d H:i:s'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);
        
        $this->createIndex('lang_code_idx', models\Language::tableName(), 'lang_code', true);
        
        if ($this->settings->shouldInstallDefaults() === true) {
            $this->executeLanguageSql();
        }
    }
    
    private function executeLanguageSql()
    {
        $datetime = (new \DateTime())->format('Y-m-d H:i:s');
        
        $this->insert(models\Language::tableName(), $this->settings->getLanguageParams());
    }
    
    public function down()
    {
        $this->dropIndex('lang_code_idx', models\Language::tableName());
        
        $this->dropTable(models\Language::tableName());
    }
}
