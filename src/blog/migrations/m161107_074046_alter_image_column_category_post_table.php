<?php

use rokorolov\parus\blog\models;
use yii\db\Migration;

class m161107_074046_alter_image_column_category_post_table extends Migration
{
    public function up()
    {
        $this->alterColumn(models\Post::tableName(), 'image', $this->string(255)->defaultValue(null));
        $this->alterColumn(models\Category::tableName(), 'image', $this->string(255)->defaultValue(null));
    }

    public function down()
    {
        $this->alterColumn(models\Post::tableName(), 'image', $this->string(64)->defaultValue(null));
        $this->alterColumn(models\Category::tableName(), 'image', $this->string(64)->defaultValue(null));
    }
}
