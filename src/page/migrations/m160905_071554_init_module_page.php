<?php

use rokorolov\parus\page\models;
use rokorolov\parus\user\models\User;
use rokorolov\parus\language\models\Language;
use yii\db\Migration;

/**
 * m160905_071554_init_module_page
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class m160905_071554_init_module_page extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(models\Page::tableName(), [
            'id' => $this->primaryKey(10),
            'status' => $this->smallInteger(2)->notNull(),
            'language' => $this->string(7)->notNull(),
            'title' => $this->string(512)->notNull(),
            'slug' => $this->string(512)->notNull(),
            'content' => $this->text()->notNull(),
            'hits' => $this->integer(10)->unsigned()->notNull()->defaultValue('0'),
            'home' => $this->integer(3)->unsigned()->defaultValue('0'),
            'view' => $this->string(128),
            'version' => $this->integer(10)->unsigned()->defaultValue('1'),
            'reference' => $this->string(),
            'created_by' => $this->integer(11)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'modified_by' => $this->integer(11)->notNull(),
            'modified_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'meta_title' => $this->string(128)->defaultValue(null),
            'meta_keywords' => $this->string(255)->defaultValue(null),
            'meta_description' => $this->string(255)->defaultValue(null),
            'deleted_at' => $this->timestamp()->null()->defaultValue(null)
        ], $tableOptions);

        $this->createIndex('slug_idx', models\Page::tableName(), 'slug');
        $this->addForeignKey('fk__page_created_by__user_id', models\Page::tableName(), 'created_by', User::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__page_modified_by__user_id', models\Page::tableName(), 'modified_by', User::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__page_language__language_lang_code', models\Page::tableName(), 'language', Language::tableName(), 'lang_code', 'CASCADE', 'CASCADE');
    }
    
    public function down()
    {
        $this->dropIndex('slug_idx', models\Page::tableName());
        
        $this->dropForeignKey('fk__page_created_by__user_id', models\Page::tableName());
        $this->dropForeignKey('fk__page_modified_by__user_id', models\Page::tableName());
        $this->dropForeignKey('fk__page_language__language_lang_code', models\Page::tableName());
        
        $this->dropTable(models\Page::tableName());
    }
}
