<?php

use rokorolov\parus\gallery\models;
use rokorolov\parus\language\models\Language;

use yii\db\Migration;

class m160914_124800_init_module_gallery extends Migration
{
    public function up()
    {
        $this->createTable(models\Album::tableName(), [
            'id' => $this->primaryKey(10),
            'status' => $this->string(32)->notNull(),
            'album_aliase' => $this->string(128)->notNull(),
            'modified_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        
        $this->createTable(models\AlbumLang::tableName(), [
            'album_id' => $this->integer(10)->notNull(),
            'name' => $this->string(128)->notNull(),
            'description' => $this->string(),
            'language' => $this->integer(10)->notNull(),
        ]);
        
        $this->addPrimaryKey('', models\AlbumLang::tableName(), ['album_id', 'language']);
        $this->addForeignKey('fk__album_lang_album_id__album_id', models\AlbumLang::tableName(), 'album_id', models\Album::tableName(), 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk__album_lang_language__language_id', models\AlbumLang::tableName(), 'language', Language::tableName(), 'id', 'CASCADE', 'CASCADE');
    
        $this->createTable(models\Photo::tableName(), [
            'id' => $this->primaryKey(10),
            'status' => $this->string(32)->notNull(),
            'order' => $this->smallInteger(2)->notNull()->defaultValue(0),
            'album_id' => $this->integer(10)->notNull(),
            'photo_name' => $this->string(128)->notNull(),
            'photo_size' => $this->string(10)->notNull(),
            'photo_extension' => $this->string(128)->notNull(),
            'photo_mime' => $this->string(50)->notNull(),
            'photo_path' => $this->string(),
            'modified_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        
        $this->addForeignKey('fk__photo_album_id__album_id', models\Photo::tableName(), 'album_id', models\Album::tableName(), 'id', 'NO ACTION', 'NO ACTION');
        
        $this->createTable(models\PhotoLang::tableName(), [
            'photo_id' => $this->integer(10)->notNull(),
            'caption' => $this->string(512)->defaultValue(null),
            'description' => $this->string(512)->defaultValue(null),
            'language' => $this->integer(10)->notNull(),
        ]);
        
        $this->addPrimaryKey('', models\PhotoLang::tableName(), ['photo_id', 'language']);
        $this->addForeignKey('fk__photo_lang_photo_id__photo_id', models\PhotoLang::tableName(), 'photo_id', models\Photo::tableName(), 'id', 'CASCADE', 'NO ACTION');
        $this->addForeignKey('fk__photo_lang_language__language_id', models\PhotoLang::tableName(), 'language', Language::tableName(), 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk__photo_album_id__album_id', models\Photo::tableName());
        $this->dropForeignKey('fk__album_lang_album_id__album_id', models\AlbumLang::tableName());
        $this->dropForeignKey('fk__album_lang_language__language_id', models\AlbumLang::tableName());
        $this->dropForeignKey('fk__photo_lang_photo_id__photo_id', models\PhotoLang::tableName());
        $this->dropForeignKey('fk__photo_lang_language__language_id', models\PhotoLang::tableName());
        
        $this->dropTable(models\Album::tableName());
        $this->dropTable(models\AlbumLang::tableName());
        $this->dropTable(models\Photo::tableName());
        $this->dropTable(models\PhotoLang::tableName());
    }
}
