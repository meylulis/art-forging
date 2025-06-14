<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Requests;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
{
    $categories = \app\models\Categories::find()->all();
    $products = [];

    foreach ($categories as $category) {
        $products[$category->id] = \app\models\Products::find()
            ->where(['category_id' => $category->id])
            ->limit(4) // по 4 товара на категорию
            ->all();
    }

    return $this->render('index', [
        'categories' => $categories,
        'products' => $products,
    ]);
}



    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $user = Yii::$app->user->identity;

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('profileUpdated', 'Информация успешно изменена.');
            return $this->refresh();
        }

        return $this->render('profile', [
            'user' => $user,
        ]);
    }

    public function actionFavorites()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $favorites = \app\models\Favorites::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('product')
            ->all();

        return $this->render('favorites', [
            'favorites' => $favorites,
        ]);
    }



    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new \app\models\ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $request = new \app\models\Requests();
            $request->name = $model->name;
            $request->phone = $model->phone;
            $request->message = $model->message;
            $request->created_at = date('Y-m-d H:i:s');
            $request->product_id = null;

            if (!Yii::$app->user->isGuest) {
                $request->user_id = Yii::$app->user->id;
            }

            if ($request->save()) {
                Yii::$app->session->setFlash('contactFormSubmitted', 'Сообщение успешно отправлено.');
                return $this->refresh();
            } else {
                Yii::error($request->errors, 'request');
                Yii::$app->session->setFlash('contactFormError', 'Ошибка при сохранении сообщения.');
            }
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }




    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
