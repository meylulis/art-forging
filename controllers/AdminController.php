<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class AdminController extends Controller
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

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if (Yii::$app->user->isGuest || Yii::$app->user->identity->role_id != 1) {
            Yii::$app->session->setFlash('error', 'У вас нет доступа к админке.');
            return $this->redirect(['site/index']);
        }
        return true;
    }
    

    /**
     * Lists all Role models.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
