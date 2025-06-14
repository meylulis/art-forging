<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Авторизация';
?>
<div class="form-wrapper site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Пожалуйста, заполните все поля</p>

    <?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'p-4 rounded', 'style' => 'background-color: #f9f6f2; max-width: 500px; margin: auto;'],
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'form-label', 'style' => 'color: #4B3F23; font-weight: 500;'],
        'inputOptions' => ['class' => 'form-control', 'style' => 'background-color: #fff; border: 1px solid #ccc; color: #4B3F23;'],
        'errorOptions' => ['class' => 'text-danger'],
    ],
    ]); ?>


        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Имя пользователя') ?>

        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>

        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"offset-lg-1 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ])->label('Запомнить меня') ?>

        <div class="form-group">
            <div>
            <?= Html::submitButton('Войти', ['class' => 'btn btn-outline-dark ml-3', 'style' => 'border-color: #6c5e4e; ']) ?>
            <?= Html::a('Зарегистрироваться', ['users/create'], ['class' => 'btn btn-outline-dark ml-3', 'style' => 'border-color: #6c5e4e; ']) ?>


            </div>
        </div>

    <?php ActiveForm::end(); ?>
