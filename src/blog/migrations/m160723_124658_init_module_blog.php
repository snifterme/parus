<?php

use rokorolov\parus\blog\models;
use rokorolov\parus\blog\contracts\DefaultInstallInterface;
use rokorolov\parus\user\models\User;
use rokorolov\parus\language\models\Language;
use yii\db\Migration;

/**
 * m160723_124658_init_module_blog
 *
 * @author Roman Korolov <rokorolov@gmail.com>
 */
class m160723_124658_init_module_blog extends Migration
{
    public $settings;
    
    public function init()
    {
        $this->settings = Yii::createObject('rokorolov\parus\blog\helpers\DefaultInstall');
        
        if (!$this->settings instanceof DefaultInstallInterface) {
            throw new Exception("Migration failed. Class rokorolov\parus\blog\helpers\DefaultInstall must be an instance of rokorolov\parus\blog\contracts\DefaultInstallInterface");
        }

        parent::init();
    }
    
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(models\Category::tableName(), [
            'id' => $this->primaryKey(10),
            'status' => $this->smallInteger(2)->notNull(),
            'parent_id' => $this->integer(10)->notNull(),
            'image' => $this->string(64)->defaultValue(null),
            'title' => $this->string(512)->notNull(),
            'slug' => $this->string(512)->notNull(),
            'description' => $this->text()->notNull(),
            'depth' => $this->smallInteger(4)->notNull()->defaultValue('0'),
            'lft' => $this->integer(10)->notNull()->defaultValue('0'),
            'rgt' => $this->integer(10)->notNull()->defaultValue('0'),
            'language' => $this->string(7)->notNull(),
            'reference' => $this->string()->defaultValue(null),
            'created_by' => $this->integer(10)->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'modified_by' => $this->integer(10)->notNull(),
            'modified_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'meta_title' => $this->string(128)->defaultValue(null),
            'meta_keywords' => $this->string(255)->defaultValue(null),
            'meta_description' => $this->string(255)->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('slug_idx', models\Category::tableName(), 'slug');
        $this->addForeignKey('fk__category_created_by__user_id', models\Category::tableName(), 'created_by', User::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__category_modified_by__user_id', models\Category::tableName(), 'modified_by', User::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__category_language__language_lang_code', models\Category::tableName(), 'language', Language::tableName(), 'lang_code', 'CASCADE', 'CASCADE');

        $this->createTable(models\Post::tableName(), [
            'id' => $this->primaryKey(10),
            'category_id' => $this->integer(10)->notNull(),
            'status' => $this->smallInteger(2)->notNull(),
            'title' => $this->string(512)->notNull(),
            'slug' => $this->string(512)->notNull(),
            'introtext' => $this->text()->notNull(),
            'fulltext' => $this->text()->notNull(),
            'hits' => $this->integer(10)->unsigned()->notNull()->defaultValue('0'),
            'image' => $this->string(64)->defaultValue(null),
            'post_type' => $this->string(20)->defaultValue('post'),
            'published_at' => $this->dateTime()->notNull()->defaultValue('0000-00-00 00:00:00'),
            'publish_up' => $this->dateTime()->null()->defaultValue(null),
            'publish_down' => $this->dateTime()->null()->defaultValue(null),
            'language' => $this->string(7)->notNull(),
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

        $this->createIndex('slug_idx', models\Post::tableName(), 'slug');
        $this->addForeignKey('fk__post_category_id__category_id', models\Post::tableName(), 'category_id', models\Category::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__post_created_by__user_id', models\Post::tableName(), 'created_by', User::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__post_modified_by__user_id', models\Post::tableName(), 'modified_by', User::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        $this->addForeignKey('fk__post_language__language_lang_code', models\Post::tableName(), 'language', Language::tableName(), 'lang_code', 'CASCADE', 'CASCADE');

        if ($this->settings->shouldInstallDefaults() === true) {
            $this->executeCategorySql();
        }
    }
    
    private function executeCategorySql()
    {
        $this->batchInsert(models\Category::tableName(), [
            'id',
            'status',
            'parent_id',
            'image',
            'title',
            'slug',
            'description',
            'created_by',
            'created_at',
            'modified_by',
            'modified_at',
            'depth',
            'lft',
            'rgt',
            'language',
            'reference',
            'meta_title',
            'meta_keywords',
            'meta_description',
        ],
        $this->settings->getCategoryParams()
        );
    }
    
    public function down()
    {
        $this->dropIndex('slug_idx', models\Category::tableName());
        $this->dropIndex('slug_idx', models\Post::tableName());
        
        $this->dropForeignKey('fk__category_created_by__user_id', models\Category::tableName());
        $this->dropForeignKey('fk__category_modified_by__user_id', models\Category::tableName());
        $this->dropForeignKey('fk__category_language__language_lang_code', models\Category::tableName());
        $this->dropForeignKey('fk__post_category_id__category_id', models\Post::tableName());
        $this->dropForeignKey('fk__post_created_by__user_id', models\Post::tableName());
        $this->dropForeignKey('fk__post_modified_by__user_id', models\Post::tableName());
        $this->dropForeignKey('fk__post_language__language_lang_code', models\Post::tableName());
        
        $this->dropTable(models\Post::tableName());
        $this->dropTable(models\Category::tableName());
    }

}
