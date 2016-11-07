<?php

use rokorolov\parus\gallery\models\Album;
use yii\db\Migration;

class m161107_075508_rename_album_aliase_to_album_alias_album_table extends Migration
{
    public function up()
    {
        $this->renameColumn(Album::tableName(), 'album_aliase', 'album_alias');
    }

    public function down()
    {
        $this->renameColumn(Album::tableName(), 'album_alias', 'album_aliase');
    }
}
