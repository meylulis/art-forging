<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Requests;
use yii\bootstrap4\ActiveForm;

$this->title = 'Профиль';
?>

<div class="profile-wrapper mt-4">

    <?php if (Yii::$app->session->hasFlash('profileUpdated')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('profileUpdated') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <h1 class="profile-title mb-4"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <!-- Личная информация -->
            <div class="card-profile mb-4">
                <div class="card-body-profile">
                    <h5 class="card-title">Личная информация</h5>
                    <?php $form = ActiveForm::begin(); ?>
                        <?= $form->field($user, 'username')->textInput(['class' => 'form-control bg-white'])->label('Имя') ?>
                        <?= $form->field($user, 'email')->textInput(['class' => 'form-control bg-white'])->label('Email') ?>
                        <?= $form->field($user, 'phone')->textInput(['class' => 'form-control bg-white'])->label('Телефон') ?>
                        <div class="text-end mt-3">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-dark']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

            <!-- Избранное -->
            <div class="card-profile">
                <div class="card-body-profile">
                    <h5 class="card-title">Ваши избранные изделия</h5>
                    <p class="card-text text-muted">Быстрый доступ к понравившимся товарам.</p>
                    <div class="text-end">
                        <?= Html::a('Перейти к избранному', ['site/favorites'], ['class' => 'btn btn-dark text-white']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h3 class="mt-5">Отправленные заявки</h3>

    <?php
    $userRequests = Requests::find()
        ->where(['user_id' => Yii::$app->user->id])
        ->orderBy(['created_at' => SORT_DESC])
        ->all();
    ?>

    <?php if (empty($userRequests)): ?>
        <p class="text-muted">Вы ещё не отправляли заявок через форму обратной связи.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($userRequests as $index => $req): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1"><strong>Сообщение:</strong> <?= Html::encode($req->message) ?></p>
                            <p class="mb-1"><strong>Имя:</strong> <?= Html::encode($req->name) ?> | <strong>Телефон:</strong> <?= Html::encode($req->phone) ?></p>
                            <p class="mb-1 text-muted"><small>Отправлено: <?= Yii::$app->formatter->asDatetime($req->created_at) ?></small></p>
                            <?php if (!empty($req->admin_reply)): ?>
                                <span class="badge badge-success">Ответ получен</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Без ответа</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($req->admin_reply)): ?>
                            <button class="btn btn-outline-dark btn-sm toggle-reply-btn"
                                    type="button"
                                    data-toggle="collapse"
                                    data-target="#reply<?= $index ?>"
                                    aria-expanded="false"
                                    aria-controls="reply<?= $index ?>">
                                Показать ответ
                            </button>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($req->admin_reply)): ?>
                        <div class="collapse mt-3" id="reply<?= $index ?>">
                            <div class="alert alert-info">
                                <strong>Ответ администратора:</strong><br>
                                <?= nl2br(Html::encode($req->admin_reply)) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$js = <<<JS
$(document).ready(function () {
    $('.toggle-reply-btn').on('click', function () {
        var btn = $(this);
        var target = $(btn.data('target'));

        // Toggle через Bootstrap collapse 
        target.on('hidden.bs.collapse', function () {
            btn.text('Показать ответ');
        });

    });
});
JS;

$this->registerJs($js, \yii\web\View::POS_READY);
?>
