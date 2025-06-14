<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Request $model */

$this->title = 'Ответ на заявку #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
