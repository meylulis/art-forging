<?php

use app\models\Products;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = 'Изделия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="products-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создание новых записей', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

   <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    // 'options' => ['class' => 'table-responsive'],
    // 'tableOptions' => ['class' => 'table table-bordered table-hover'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'id',
            'label' => 'ID',
        ],
        [
            'attribute' => 'name',
            'label' => 'Название',
        ],
        [
            'attribute' => 'slug',
            'label' => 'Слаг',
        ],
        [
            'attribute' => 'description',
            'label' => 'Описание',
            'format' => 'ntext',
        ],
        [
            'attribute' => 'price',
            'label' => 'Цена (₽)',
        ],
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, Products $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->id]);
            }
        ],
    ],
]); ?>


    <!-- Карточки с товарами и сменой фото -->
   <div class="row mt-5">
    <?php foreach ($dataProvider->getModels() as $model): ?>
        <?php
            $images = [];

            if ($model->main_image) {
                $images[] = Url::to('@web/' . $model->main_image);
            }

            if (!empty($model->productImages)) {
                foreach ($model->productImages as $img) {
                    $images[] = Url::to('@web/' . $img->image_path);
                }
            }

            $carouselId = 'carousel-' . $model->id;
        ?>
        <div class="col-md-3 mb-4">
            <div class="card">
                <div id="<?= $carouselId ?>" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $src): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= $src ?>" class="d-block w-100" alt="Изображение">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($images) > 1): ?>
                        <a class="carousel-control-prev" href="#<?= $carouselId ?>" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Назад</span>
                        </a>
                        <a class="carousel-control-next" href="#<?= $carouselId ?>" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Вперёд</span>
                        </a>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <h5 class="card-title"><?= Html::encode($model->name) ?></h5>
                    <p class="card-text"><?= Html::encode($model->price) ?> ₽</p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
</div>
