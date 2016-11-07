<?php

use rokorolov\parus\menu\models\MenuType;
use yii\db\Migration;

class m161107_073924_rename_menu_type_aliase_column_to_menu_type_alias_menu_type_table extends Migration
{
    public function up()
    {
        $this->renameColumn(MenuType::tableName(), 'menu_type_aliase', 'menu_type_alias');
    }

    public function down()
    {
        $this->renameColumn(MenuType::tableName(), 'menu_type_alias', 'menu_type_aliase');
    }
}
