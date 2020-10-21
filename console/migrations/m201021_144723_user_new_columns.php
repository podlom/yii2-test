<?php

use yii\db\Migration;


/**
 * Class m201021_144723_user_new_columns
 */
class m201021_144723_user_new_columns extends Migration
{
    const TABLE = 'user';

    public function up()
    {
        $this->addColumn(self::TABLE, 'first_name', $this->string(32)->notNull()->defaultValue(''));
        $this->addColumn(self::TABLE, 'last_name', $this->string(32)->notNull()->defaultValue(''));
        $this->addColumn(self::TABLE, 'birthday', $this->date());
        $this->addColumn(self::TABLE, 'last_login', $this->timestamp());
        $this->addColumn(self::TABLE, 'user_notes', $this->text());
        $this->addColumn(self::TABLE, 'avatar_url', $this->string(255)->notNull()->defaultValue(''));
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->dropColumn(self::TABLE, 'first_name');
        $this->dropColumn(self::TABLE, 'last_name');
        $this->dropColumn(self::TABLE, 'birthday');
        $this->dropColumn(self::TABLE, 'last_login');
        $this->dropColumn(self::TABLE, 'user_notes');
        $this->dropColumn(self::TABLE, 'avatar_url');
        $this->execute('SET foreign_key_checks = 1;');
    }
}
