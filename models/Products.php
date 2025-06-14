<?php

namespace app\models;

use Yii;
use app\models\ProductImage;

/**
 * This is the model class for table "products".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property string $main_image
 * @property string $created_at
 * @property int $category_id
 *
 * @property Categories $category
 * @property ProductImage[] $productImages
 * @property Requests[] $requests
 * @property ProductImage[] $productImages
 */

class Products extends \yii\db\ActiveRecord
{

    public $main_image_file;
    /**
     * @var UploadedFile[] дополнительные изображения
     */
    public $secondary_images = [];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
{
    return [
        [['main_image_file'], 'required', 'message' => 'Загрузите главное изображение.'],
        [['main_image_file'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => false],
        [['secondary_images'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10, 'skipOnEmpty' => true],
        [['name', 'slug', 'description', 'price', 'category_id'], 'required'],
        [['description'], 'string'],
        [['price'], 'number'],
        [['created_at'], 'safe'],
        [['category_id'], 'integer'],
        [['name', 'slug', 'main_image'], 'string', 'max' => 255],
        [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Categories::class, 'targetAttribute' => ['category_id' => 'id']],
    ];
}

 public function getImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id']);
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'price' => 'Price',
            'main_image' => 'Main Image',
            'created_at' => 'Created At',
            'category_id' => 'Category ID',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::class, ['id' => 'category_id']);
    }

    /**
     * Gets query for [[ProductImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(\app\models\ProductImage::class, ['product_id' => 'id']);
    }
    

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::class, ['product_id' => 'id']);
    }

}
