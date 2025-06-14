<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Users $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="form-wrapper users-form">
    <h1>Регистрация</h1>
    <p>Заполните все поля для создания аккаунта</p>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label('Логин') ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label('Пароль') ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true])->label('ФИО') ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true])->label('Номер телефона') ?>

<?= $form->field($model, 'agree')->checkbox([
    'id' => 'agree-checkbox',
    'template' => "<div class=\"offset-lg-1 col-lg-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
]) ?>

    <div class="form-group">
        <?= Html::submitButton('Регистрация', [
            'class' => 'btn btn-outline-dark ml-3',
            'style' => 'border-color: #6c5e4e;',
            'id' => 'submit-button',
            'disabled' => true
        ]) ?>
    </div>

<?php
$script = <<<JS
const checkbox = document.getElementById('agree-checkbox');
const submitButton = document.getElementById('submit-button');

checkbox.addEventListener('change', function() {
    submitButton.disabled = !this.checked;
});
JS;

$this->registerJs($script);
?>

    <?php ActiveForm::end(); ?>
</div>

