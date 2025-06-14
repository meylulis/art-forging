<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "requests".
 *
 * @property int $id
 * @property int $product_id
 * @property string $name
 * @property string $phone
 * @property string $message
 * @property string $created_at
 *
 * @property Products $product
 */
class Requests extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'requests';
    }

    /**
     * {@inheritdoc}
     */
public function rules()
{
    return [
        [['name', 'phone', 'message'], 'required'],
        [['product_id', 'user_id'], 'integer'],
        [['message', 'admin_reply'], 'string'],
        [['created_at'], 'safe'],
        [['name', 'phone'], 'string', 'max' => 255],
        [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Products::class, 'targetAttribute' => ['product_id' => 'id']],
        [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => \app\models\Users::class, 'targetAttribute' => ['user_id' => 'id']],
    ];
}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
{
    return [
        'id' => 'ID',
        'product_id' => 'Product ID',
        'user_id' => 'User ID',
        'name' => 'Name',
        'phone' => 'Phone',
        'message' => 'Message',
        'created_at' => 'Created At',
        'admin_reply' => 'Admin Reply'
    ];
}


    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Products::class, ['id' => 'product_id']);
    }

}
