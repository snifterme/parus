<?php

use rokorolov\parus\admin\base\BaseMigration;
use rokorolov\parus\settings\contracts\DefaultInstallInterface;
use rokorolov\parus\settings\models;
use yii\console\Exception;

class m161121_160343_init_module_settings extends BaseMigration
{
    /**
     * @var DefaultInstallInterface
     */
    public $settings;
    
    public function init()
    {
        $this->settings = Yii::createObject('rokorolov\parus\settings\helpers\DefaultInstall');
        
        if (!$this->settings instanceof DefaultInstallInterface) {
            throw new Exception('Migration failed. Class rokorolov\parus\settings\helpers\DefaultInstall must be an instance of rokorolov\parus\settings\contracts\DefaultInstallInterface');
        }

        parent::init();
    }
    
    public function up()
    {
        $tableOptions = $this->tableOptions;
        
        $this->createTable(models\Settings::tableName(), [
            'id' => $this->primaryKey(10)->unsigned(),
            'param' => $this->string(128)->notNull(),
            'value' => $this->text()->notNull(),
            'default' => $this->text()->notNull(),
            'type' => $this->string(128)->notNull(),
            'order' => $this->integer(10)->unsigned()->notNull()->defaultValue('0'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ], $tableOptions);
        
        $this->createIndex('param_idx', models\Settings::tableName(), 'param', true);
        
        $this->createTable(models\SettingsLang::tableName(), [
            'settings_id' => $this->integer(10)->notNull()->unsigned(),
            'language' => $this->string(7)->notNull(),
            'label' => $this->string(255)->notNull(),
        ], $tableOptions);
        
        $this->createIndex('language_idx', models\SettingsLang::tableName(), 'language');
        
        $this->addPrimaryKey('', models\SettingsLang::tableName(), ['settings_id', 'language']);
        $this->addForeignKey('fk__settings_lang_settings_id__settings_id', models\SettingsLang::tableName(), 'settings_id', models\Settings::tableName(), 'id', 'CASCADE', 'NO ACTION');
    
        if ($this->settings->shouldInstallDefaults() === true) {
            $this->executeSettingsSql();
            $this->executeSettingsLangSql();
        }
    }

    private function executeSettingsSql()
    {
        $this->batchInsert('{{%settings}}', [
            'id',
            'param',
            'value',
            'default',
            'type',
            'order',
            'created_at',
            'updated_at',
        ],
        $this->settings->getSettingParams()
        );
    }
    
    private function executeSettingsLangSql()
    {
        $this->batchInsert('{{%settings_lang}}', [
            'settings_id',
            'language',
            'label',
        ],
        $this->settings->getSettingLangParams()
        );
    }
    
    public function down()
    {
        $this->dropForeignKey('fk__settings_lang_settings_id__settings_id', models\SettingsLang::tableName());
        
        $this->dropIndex('param_idx', models\Settings::tableName());
        $this->dropIndex('language_idx', models\SettingsLang::tableName());
        
        $this->dropTable(models\Settings::tableName());
        $this->dropTable(models\SettingsLang::tableName());
    }
}
