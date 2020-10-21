<?php

use yii\db\Migration;


/**
 * Class m201021_181450_create_first_admin_user
 */
class m201021_181450_create_first_admin_user extends Migration
{
    public function up()
    {
        $this->execute("INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`, `verification_token`, `first_name`, `last_name`, `birthday`, `last_login`, `user_notes`, `avatar_url`, `group_id`) VALUES (1, 'admin', 'CWlF1DdsVhHGNoXg0MMPtngJ8X-99Ebl', '$2y$13$8dqgtVQPOycSZs.YZYLxhO5oU8DvpMd1IGTU9NSbT9BELIMgEHxtu', NULL, 'podlom@gmail.com', 10, 1603303576, 1603303576, NULL, 'Admin', 'Last', '2020-10-21', '2020-10-21 18:07:02', 'First admin user', '', 1)");
    }

    public function down()
    {
        $this->execute("DELETE FROM `user` WHERE `id` = '1' LIMIT 1");
    }
}
