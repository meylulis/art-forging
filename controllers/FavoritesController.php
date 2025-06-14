<?php

namespace app\controllers;

use Yii;
use app\models\Favorites;
use app\models\FavoritesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FavoritesController implements the CRUD actions for Favorites model.
 */
class FavoritesController extends Controller
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
     * Lists all Favorites models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FavoritesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Favorites model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Favorites model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
public function actionCreate($product_id)
{
    if (Yii::$app->user->isGuest) {
        Yii::$app->session->setFlash('error', 'Вы должны быть авторизованы.');
        return $this->goBack();
    }

    $exists = Favorites::findOne([
        'user_id' => Yii::$app->user->id,
        'product_id' => $product_id,
    ]);

    if (!$exists) {
        $model = new Favorites();
        $model->user_id = Yii::$app->user->id;
        $model->product_id = $product_id;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Товар добавлен в избранное.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при добавлении.');
        }
    } else {
        Yii::$app->session->setFlash('info', 'Товар уже в избранном.');
    }

    return $this->redirect(Yii::$app->request->referrer ?: ['products/catalog-products']);
}




    /**
     * Updates an existing Favorites model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Favorites model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
   public function actionDelete($product_id)
{
    if (Yii::$app->user->isGuest) {
        Yii::$app->session->setFlash('error', 'Вы должны быть авторизованы.');
        return $this->goBack();
    }

    $model = Favorites::findOne([
        'user_id' => Yii::$app->user->id,
        'product_id' => $product_id,
    ]);

    if ($model && $model->delete()) {
        Yii::$app->session->setFlash('success', 'Товар удалён из избранного.');
    } else {
        Yii::$app->session->setFlash('error', 'Не удалось удалить из избранного.');
    }

    return $this->redirect(Yii::$app->request->referrer ?: ['products/catalog-products']);
}

    /**
     * Finds the Favorites model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Favorites the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Favorites::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
