<?php

use rokorolov\parus\gallery\models\Album;
use yii\db\Migration;

/**
 * Handles adding image to table `album`.
 */
class m161107_074410_add_image_column_to_album_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(Album::tableName(), 'image', $this->string(255)->defaultValue(null)->after('album_aliase'));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn(Album::tableName(), 'image');
    }
}
