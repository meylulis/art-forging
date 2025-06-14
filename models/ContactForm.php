<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $phone;
    public $message;
    public $email;  // Если у вас также есть поле для email
    public $subject;  // Если у вас есть поле для темы
    public $verifyCode;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'message'], 'required', 'message' => 'Обязательное поле'],
            ['phone', 'match', 'pattern' => '/^\+7\d{10}$/', 'message' => 'Телефон должен быть в формате +7XXXXXXXXXX.'],
            [['message'], 'string'],

        ];
    }


    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
