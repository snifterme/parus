<?php

use rokorolov\parus\menu\models;
use rokorolov\parus\menu\contracts\DefaultInstallInterface;
use rokorolov\parus\language\models\Language;
use yii\db\Migration;

class m161121_160420_init_module_menu extends Migration
{
    public $settings;

    public function init()
    {
        $this->settings = Yii::createObject('rokorolov\parus\menu\helpers\DefaultInstall');

        if (!$this->settings instanceof DefaultInstallInterface) {
            throw new Exception("Migration failed. Class rokorolov\parus\menu\helpers\DefaultInstall must be an instance of rokorolov\parus\menu\contracts\DefaultInstallInterface");
        }

        parent::init();
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(models\MenuType::tableName(), [
            'id' => $this->primaryKey(10)->unsigned(),
            'menu_type_alias' => $this->string(128)->notNull(),
            'title' => $this->string(128)->notNull(),
            'description' => $this->string(128)->notNull()
        ], $tableOptions);

        $this->createTable(models\Menu::tableName(), [
            'id' => $this->primaryKey(10)->unsigned(),
            'status' => $this->smallInteger(2)->notNull(),
            'parent_id' => $this->integer(10)->unsigned()->notNull(),
            'title' => $this->string(128)->notNull(),
            'link' => $this->string(1024)->notNull(),
            'note' => $this->string(255)->notNull(),
            'menu_type_id' => $this->integer(10)->notNull()->unsigned(),
            'language' => $this->integer(10)->notNull()->unsigned(),
            'depth' => $this->smallInteger(4)->notNull()->unsigned()->defaultValue('0'),
            'lft' => $this->integer(10)->notNull()->unsigned()->defaultValue('0'),
            'rgt' => $this->integer(10)->notNull()->unsigned()->defaultValue('0'),
        ], $tableOptions);

        $this->addForeignKey('fk__menu_language__language_id', models\Menu::tableName(), 'language', Language::tableName(), 'id', 'CASCADE', 'CASCADE');

        if ($this->settings->shouldInstallDefaults() === true) {
            $this->executeMenuSql();
        }
    }

    private function executeMenuSql()
    {
        $this->batchInsert(models\Menu::tableName(), [
            'id',
            'status',
            'title',
            'parent_id',
            'menu_type_id',
            'link',
            'note',
            'language',
            'depth',
            'lft',
            'rgt',
        ],
        $this->settings->getMenuParams()
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk__menu_language__language_id', models\Menu::tableName());

        $this->dropTable(models\Menu::tableName());
        $this->dropTable(models\MenuType::tableName());
    }

}

