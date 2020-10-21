<?php
/**
 * Created by PhpStorm.
 * User: Taras
 * Date: 21.10.2020
 * Time: 20:46
 *
 * @author Taras Shkodenko <taras@shkodenko.com>
 */
namespace console\models;


use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;


/**
 * Password reset form
 */
class NewPasswordForm extends Model
{
    public $password;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($login, $config = [])
    {
        if (empty($login) || !is_string($login)) {
            throw new InvalidParamException('Username cannot be blank.');
        }
        $this->_user = User::findByUsername($login);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong user login.');
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function setNewPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }
}
