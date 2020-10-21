<?php

use yii\db\Migration;


/**
 * Class m201021_174856_init_rbac
 */
class m201021_174856_init_rbac extends Migration
{
    public function up()
    {
        $auth = Yii::$app->authManager;

        // добавляем разрешение "createUser" - может создавать пользователей в админке
        $createUser = $auth->createPermission('createUser');
        $createUser->description = 'Может создавать пользователей в админке';
        $auth->add($createUser);

        // добавляем разрешение "updateUser" - может редактировать пользователей в админке
        $updateUser = $auth->createPermission('updateUser');
        $updateUser->description = 'Может редактировать пользователей в админке';
        $auth->add($updateUser);

        // добавляем разрешение "moderateUser" - может модерировать пользователей в админке - менять только статус
        $moderateUser = $auth->createPermission('moderateUser');
        $moderateUser->description = 'Может модерировать пользователей в админке';
        $auth->add($moderateUser);

        // добавляем роль "moderator" или "модератор" и даём роли разрешения "moderateUser"
        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);
        $auth->addChild($moderator, $moderateUser);

        // добавляем роль "creator" или "создатель" и даём роли разрешения "createUser"
        $creator = $auth->createRole('creator');
        $auth->add($creator);
        $auth->addChild($creator, $createUser);

        // добавляем роль "admin" и даём роли разрешение "updateUser"
        // а также все разрешения роли "author"
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateUser);
        $auth->addChild($admin, $creator);
        $auth->addChild($admin, $moderateUser);
        $auth->addChild($admin, $moderator);

        // Назначение ролей пользователям. 1, 2 и 3 это IDs возвращаемые IdentityInterface::getId()
        // обычно реализуемый в модели User.
        $auth->assign($moderator, 3);
        $auth->assign($creator, 2);
        $auth->assign($admin, 1);
    }

    public function down()
    {
        $auth = Yii::$app->authManager;

        $auth->removeAll();
    }
}
