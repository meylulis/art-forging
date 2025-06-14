<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $full_name
 * @property string $email
 * @property string $phone
 * @property string $created_at
 * @property int $role_id
 *
 * @property Role $role
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $agree;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'full_name', 'email', 'phone'], 'required', 'message' => 'Обязательное поле'],
            [['created_at'], 'safe'],
            [['role_id'], 'integer'],
            [['username', 'password', 'full_name', 'email', 'phone'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
            ['username', 'unique', 'message' => 'Этот логин уже существует'],
            ['email', 'email', 'message' => 'Введите корректный email'],
            ['phone', 'match', 'pattern' => '/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', 'message' => 'Телефон должен быть в формате +7(XXX)-XXX-XX-XX'],
            ['password', 'string', 'tooShort' => 'Пароль должен содержать минимум 6 символов', 'min' => 6],
            ['full_name', 'match', 'pattern' => '/^[а-яА-ЯёЁ\s]+$/u', 'message' => 'ФИО должно содержать только кириллицу и пробелы.'],
        ];
    }
// В модели Users.php

public function getIsAdmin()
{
    return $this->role_id === 1;
}


    public function register()
    {
        if ($this->validate()) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
    
            if ($this->role_id === null) {
                $this->role_id = 2;
            }
    
            return $this->save(false);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'created_at' => 'Created At',
            'role_id' => 'Role ID',
            'agree' => 'Я даю согласие на обработку данных'
        ];
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // Не используем токены
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // Не используем authKey
    }

    public function validateAuthKey($authKey)
    {
        return false; // Не используем authKey
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

}
