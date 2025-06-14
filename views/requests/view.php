<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Request $model */

$this->title = 'Заявка #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin): ?>
        <?= Html::a('Ответить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?php endif; ?>

    <div class="mt-3">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'phone',
                'message:ntext',
                'created_at',
                [
                    'attribute' => 'admin_reply',
                    'format' => 'ntext',
                    'label' => 'Ответ администратора',
                    'value' => $model->admin_reply ?: 'Ответа нет',
                ],
            ],
        ]) ?>
    </div>

</div>
