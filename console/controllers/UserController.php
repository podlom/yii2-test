<?php
/**
 * Created by PhpStorm.
 * User: Taras
 * Date: 21.10.2020
 * Time: 20:46
 *
 * @author Taras Shkodenko <taras@shkodenko.com>
 */
namespace console\controllers;


use Yii;
use yii\base\InvalidParamException;
use yii\console\Controller;
use common\models\User;
use console\models\SignupForm;
use console\models\NewPasswordForm;


/**
 * User commands
 */
class UserController extends Controller
{
    /**
     * Register user
     * @param $login String Username
     * @param $password String Password
     * @param $email String Email
     */
    public function actionRegisterUser($login, $password, $email)
    {
        if (empty($login) || empty($password) || empty($email)) {
            echo 'Command usage: yii cron/register-user login password email' . PHP_EOL;
            return 1;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Wrong email format for: " . var_export($email, 1) . PHP_EOL;
            return 1;
        }

        $model = new SignupForm();
        $modelData = ['username' => $login, 'password' => $password, 'email' => $email];
        // $model->load($modelData, 'SignupForm');
        // $model->load($modelData);
        //
        $model->username   = $login;
        $model->password   = $password;
        $model->email      = $email;
        //
        if ($model->validate()) {
            // all inputs are valid
            echo 'All inputs are valid' . PHP_EOL;
            if ($user = $model->signup()) {
                echo 'Registered user with login: ' . $login . ' and email: ' . $email . PHP_EOL;
                return 0;
            }
        } else {
            // validation failed: $errors is an array containing error messages
            $errors = $model->errors;
            echo 'Model Validation Errors: ' . var_export($errors, 1) . PHP_EOL;
            return 1;
        }
    }

    /**
     * Change user password
     * @param $login String Username
     * @param $password String New password
     */
    public function actionChangeUserPassword($login, $password)
    {
        if (empty($login) || empty($password)) {
            echo 'Command usage: yii cron/register-user login password email' . PHP_EOL;
            return 1;
        }

        $model = new NewPasswordForm($login);
        $model->password = $password;

        if ($model->validate()) {
            // all inputs are valid
            echo 'All inputs are valid' . PHP_EOL;
            if ($user = $model->setNewPassword()) {
                echo 'Set new password for user: ' . $login . PHP_EOL;
                return 0;
            }
        } else {
            // validation failed: $errors is an array containing error messages
            $errors = $model->errors;
            echo 'Model Validation Errors: ' . var_export($errors, 1) . PHP_EOL;
            return 1;
        }
    }
}
