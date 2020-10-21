<?php

use yii\db\Migration;


/**
 * Class m201021_170956_user_group_column
 */
class m201021_170956_user_group_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%user}}', 'group_id', $this->integer()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'group_id');
    }
}
