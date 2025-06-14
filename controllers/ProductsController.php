<?php

namespace app\controllers;

use app\models\Categories;
use Yii;
use yii\web\UploadedFile;
use app\models\ProductImage;
use app\models\Products;
use app\models\ProductsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductsController implements the CRUD actions for Products model.
 */
class ProductsController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Products models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

        public function actionCatalog()
    {
        $categoryId = Yii::$app->request->get('category');
        $sort = Yii::$app->request->get('sort');

        if ($categoryId) {
            // Фильтрация товаров по категории
            $query = Products::find()->where(['category_id' => $categoryId]);

            if ($sort === 'price_asc') {
                $query->orderBy(['price' => SORT_ASC]);
            } elseif ($sort === 'price_desc') {
                $query->orderBy(['price' => SORT_DESC]);
            } elseif ($sort === 'random') {
                $query->orderBy(new \yii\db\Expression('RAND()'));
            }

            $products = $query->all();
            $categories = Categories::find()->all();

            return $this->render('catalog-products', [
                'products' => $products,
                'categories' => $categories,
            ]);
        }

        // Если нет category_id, выводим карточки по категориям
        $categories = Categories::find()->all();
        $categoryData = [];

        foreach ($categories as $cat) {
            $products = $cat->products;
        
            $image = Yii::getAlias('@web/images/placeholder.jpg'); // Заглушка
            $minPrice = 0;
        
            if (!empty($products)) {
                $prices = array_filter(array_map(function($p) {
                    return $p->price;
                }, $products));
        
                if (!empty($prices)) {
                    $minPrice = min($prices);
                }
        
                foreach ($products as $p) {
                    if (!empty($p->main_image) && file_exists(Yii::getAlias('@webroot/' . $p->main_image))) {
                        $image = Yii::getAlias('@web/' . $p->main_image);
                        break;
                    }
                }
            }
        
            $categoryData[] = [
                'name' => $cat->name,
                'id' => $cat->id,
                'image' => $image,
                'minPrice' => $minPrice,
                'productCount' => count($products),
            ];
        }
        
        
        return $this->render('catalog', [
            'categoryData' => $categoryData,
        ]);
    }

    public function actionCatalogProducts($category = null)
    {
        $query = Products::find();

        if ($category !== null) {
            $query->andWhere(['category_id' => $category]);
        }

        $products = $query->all();

        return $this->render('catalog-products', [
            'products' => $products,
        ]);
    }



    /**
     * Displays a single Products model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {

        $product = \app\models\Products::findOne($id);

    if (!$product) {
        throw new NotFoundHttpException('Товар не найден');
    }

    return $this->render('view', [
        'model' => $product,
    ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Products model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

public function actionCreate()
    {
        $model = new Products();

        if ($model->load(Yii::$app->request->post())) {
            $model->main_image_file = UploadedFile::getInstance($model, 'main_image_file');
            $model->secondary_images = UploadedFile::getInstances($model, 'secondary_images');

            if ($model->validate()) {
                // Главное изображение
                if ($model->main_image_file) {
                    $filename = uniqid('main_') . '.' . $model->main_image_file->extension;
                    $savePath = Yii::getAlias('@webroot/uploads/products/' . $filename);
                    if ($model->main_image_file->saveAs($savePath)) {
                        $model->main_image = 'uploads/products/' . $filename;
                    }
                }

                $model->created_at = date('Y-m-d H:i:s');

                if ($model->save(false)) {
                    // Сохраняем дополнительные изображения
                    foreach ($model->secondary_images as $file) {
                        if ($file && $file->tempName && file_exists($file->tempName)) {
                            $imgName = uniqid('img_') . '.' . $file->extension;
                            $imgPath = Yii::getAlias('@webroot/uploads/products/' . $imgName);

                            if ($file->saveAs($imgPath)) {
                                $productImage = new ProductImage();
                                $productImage->product_id = $model->id;
                                $productImage->image_path = 'uploads/products/' . $imgName;
                                $productImage->save();
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Товар успешно добавлен.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка валидации.');
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Products::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('Товар не найден.');
        }

        $productImages = ProductImage::find()->where(['product_id' => $model->id])->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->main_image_file = UploadedFile::getInstance($model, 'main_image_file');
            $model->secondary_images = UploadedFile::getInstances($model, 'secondary_images');

            if ($model->validate()) {
                // Обновление главного изображения
                if ($model->main_image_file && file_exists($model->main_image_file->tempName)) {
                    if ($model->main_image && file_exists(Yii::getAlias('@webroot/') . $model->main_image)) {
                        unlink(Yii::getAlias('@webroot/') . $model->main_image);
                    }

                    $filename = uniqid('main_') . '.' . $model->main_image_file->extension;
                    $savePath = Yii::getAlias('@webroot/uploads/products/' . $filename);

                    if ($model->main_image_file->saveAs($savePath)) {
                        $model->main_image = 'uploads/products/' . $filename;
                    }
                }

                if ($model->save(false)) {
                    // Добавление новых дополнительных изображений
                    foreach ($model->secondary_images as $file) {
                        if ($file && $file->tempName && file_exists($file->tempName)) {
                            $imgName = uniqid('img_') . '.' . $file->extension;
                            $imgPath = Yii::getAlias('@webroot/uploads/products/' . $imgName);

                            if ($file->saveAs($imgPath)) {
                                $productImage = new ProductImage();
                                $productImage->product_id = $model->id;
                                $productImage->image_path = 'uploads/products/' . $imgName;
                                $productImage->save();
                            }
                        }
                    }

                    Yii::$app->session->setFlash('success', 'Товар обновлён.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'productImages' => $productImages,
        ]);
    }


    /**
     * Deletes an existing Products model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Products model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Products the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Products::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
