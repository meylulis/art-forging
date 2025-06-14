<?php

use app\models\Categories;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Products $model */
/** @var yii\widgets\ActiveForm $form */

$form = ActiveForm::begin([
    'options' => ['enctype' => 'multipart/form-data'],
    'enableAjaxValidation' => false, // отключим на время
]);
?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Название') ?>

<?= $form->field($model, 'slug')->textInput(['maxlength' => true])->label('Другое название') ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6])->label('Описание') ?>

<?= $form->field($model, 'price')->textInput(['maxlength' => true])->label('Цена') ?>

<?= $form->field($model, 'main_image_file')->fileInput()->label('Главное изображение') ?>

<?= $form->field($model, 'secondary_images')->fileInput(['multiple' => true])->label('Дополнительные изображения') ?>

<?= $form->field($model, 'category_id')->dropDownList(
    ArrayHelper::map(Categories::find()->all(), 'id', 'name'),
    ['prompt' => 'Выберите категорию']
)->label('Категория') ?>

<?php if ($model->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= Html::errorSummary($model) ?>
    </div>
<?php endif; ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
