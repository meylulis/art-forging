<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requests-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать заявку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="row mt-4">
        <?php foreach ($dataProvider->getModels() as $model): ?>
            <?php
                // Есть ли ответ администратора?
                $hasReply = !empty($model->admin_reply);
                $cardClass = $hasReply ? 'card border-success' : 'card border-secondary';
            ?>
            <div class="col-md-4 mb-4">
                <div class="<?= $cardClass ?>" style="border-width: 2px;">
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($model->name) ?></h5>
                        <p><strong>Телефон:</strong> <?= Html::encode($model->phone) ?></p>
                        <p><strong>Сообщение:</strong><br><?= nl2br(Html::encode($model->message)) ?></p>
                        
                        <?php if ($hasReply): ?>
                            <hr>
                            <p><strong>Ответ администратора:</strong><br>
                                <?= Html::encode($model->admin_reply) ?>
                            </p>
                        <?php else: ?>
                            <p class="text-muted"><em>Ответ еще не дан</em></p>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between">
                            <?= Html::a('Ответить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить эту заявку?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    /* Стили для карточек */
    .card.border-success {
        background-color: #e6ffea; /* светло-зеленый фон */
    }
    .card.border-secondary {
        background-color: #f9f9f9; /* светло-серый фон */
    }
</style>
