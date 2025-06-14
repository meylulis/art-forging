<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Products $model */

$this->title = $model->name;

\yii\web\YiiAsset::register($this);
?>

<div class="products-view container py-5">

    <h1 class="mb-4"><?= Html::encode($model->name) ?></h1>

    <div class="row">
        <!-- Левая колонка: изображение -->
        <div class="col-md-6 mb-4">
    <?php
    $images = $model->images;
    $hasExtraImages = !empty($images);
    ?>

    <div id="productCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">

            <!-- Главное изображение -->
            <div class="carousel-item active">
                <img src="<?= Yii::getAlias('@web/' . $model->main_image) ?>" class="d-block w-100 rounded border shadow-sm product-carousel-img" alt="Главное изображение">
            </div>

            <!-- Дополнительные изображения -->
            <?php foreach ($images as $image): ?>
                <div class="carousel-item">
                    <img src="<?= Yii::getAlias('@web/' . $image->image_path) ?>" class="d-block w-100 rounded border shadow-sm" alt="Доп. изображение">
                </div>
            <?php endforeach; ?>

        </div>

        <?php if ($hasExtraImages): ?>
            <a class="carousel-control-prev" href="#productCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Назад</span>
            </a>
            <a class="carousel-control-next" href="#productCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Вперёд</span>
            </a>
        <?php endif; ?>
    </div>


        </div>

        <!-- Правая колонка: информация -->
        <div class="col-md-6 d-flex flex-column justify-content-between">
            <div>
                <h2 class="h4 mb-3"><?= Html::encode($model->name) ?></h2>
                <p class="text-muted mb-4"><?= nl2br(Html::encode($model->description)) ?></p>
                <p class="h5 text-success mb-3"><?= Html::encode($model->price) ?> ₽</p>
                <p class="text-secondary">Добавлено: <?= Yii::$app->formatter->asDate($model->created_at, 'long') ?></p>
            </div>

            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == 2): ?>
    <div class="mt-4">
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                'method' => 'post',
            ],
        ]) ?>
    </div>
<?php endif; ?>

    <div class="mt-4 d-flex justify-content-center">
        <?= Html::a('← Вернуться к товарам', ['products/catalog-products', 'category' => $model->category_id], ['class' => 'btn btn-outline-dark']) ?>
    </div>

    </div>
</div>

