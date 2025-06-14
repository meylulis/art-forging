<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Products[] $products */
/** @var app\models\Categories[] $categories */

?>

<h2 class="text-center mb-5">Товары категории</h2>

<!-- Форма фильтрации внутри категории -->
<div class="mb-4">
<?php $form = ActiveForm::begin([
    'method' => 'get',
    'action' => ['products/catalog'],
    'options' => ['class' => 'row align-items-end']
]); ?>

    <input type="hidden" name="category" value="<?= Html::encode(Yii::$app->request->get('category')) ?>">

    <div class="col-md-4">
        <?= Html::label('Сортировка по цене', 'sort', ['class' => 'form-label']) ?>
        <?= Html::dropDownList('sort', Yii::$app->request->get('sort'), [
            'price_asc' => 'От дешевых к дорогим',
            'price_desc' => 'От дорогих к дешевым',
            'random' => 'Рандом',
        ], ['class' => 'form-control', 'prompt' => 'Без сортировки']) ?>
    </div>

    <div class="col-md-4">
        <button type="submit" class="btn btn-dark btn-block">Применить</button>
    </div>

<?php ActiveForm::end(); ?>
</div>

<!-- Список товаров -->
<?php if (!empty($products)): ?>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3 mb-5">
                <div class="card h-100">
                    <div id="carousel-<?= $product->id ?>" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                                $images = [];
                                if ($product->main_image) {
                                    $images[] = Yii::getAlias('@web/' . $product->main_image);
                                }

                                foreach ($product->productImages as $img) {
                                    $images[] = Yii::getAlias('@web/' . $img->image_path);
                                }

                                foreach ($images as $i => $src):
                            ?>
                                <div class="carousel-item <?= $i === 0 ? 'active' : '' ?> position-relative">
                                    <img src="<?= Html::encode($src) ?>" class="d-block w-100" alt="Изображение">

                                    <?php if (!Yii::$app->user->isGuest): ?>
                                        <?php
                                            $isFavorite = \app\models\Favorites::find()
                                                ->where(['user_id' => Yii::$app->user->id, 'product_id' => $product->id])
                                                ->exists();

                                            $action = $isFavorite ? ['favorites/delete', 'product_id' => $product->id] : ['favorites/create', 'product_id' => $product->id];
                                            $heartIcon = $isFavorite
                                                ? '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc3545" class="bi bi-heart-fill" viewBox="0 0 16 16">
                                                    <path d="M8 2C5.8-1.3 0 1.3 0 5.6c0 2.3 2.1 4.3 5.3 7.2.7.6 1.3 1.2 1.7 1.5.4-.3 1-.9 1.7-1.5C13.9 9.9 16 7.9 16 5.6 16 1.3 10.2-1.3 8 2z"/>
                                                </svg>'
                                                : '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="#dc3545" stroke-width="1.5" class="bi bi-heart" viewBox="0 0 16 16">
                                                    <path d="M8 2C5.8-1.3 0 1.3 0 5.6c0 2.3 2.1 4.3 5.3 7.2.7.6 1.3 1.2 1.7 1.5.4-.3 1-.9 1.7-1.5C13.9 9.9 16 7.9 16 5.6 16 1.3 10.2-1.3 8 2z"/>
                                                </svg>';
                                        ?>

                                        <?= Html::beginForm($action, 'post', ['class' => 'favorite-form']) ?>
                                            <button type="submit" class="btn btn-light position-absolute" style="top: 10px; right: 10px;" title="<?= $isFavorite ? 'Удалить из избранного' : 'Добавить в избранное' ?>">
                                                <?= $heartIcon ?>
                                            </button>
                                        <?= Html::endForm() ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($images) > 1): ?>
                            <a class="carousel-control-prev" href="#carousel-<?= $product->id ?>" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Предыдущий</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel-<?= $product->id ?>" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Следующий</span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="card-body text-center">
                        <h5 class="card-title"><?= Html::encode($product->name) ?></h5>
                        <p class="card-text"><?= Html::encode($product->price) ?> ₽</p>
                        <a href="<?= Url::to(['products/view', 'id' => $product->id]) ?>" class="btn btn-outline-dark btn-sm">Подробнее</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-4 text-center">
        <a href="<?= Url::to(['products/catalog']) ?>" class="btn btn-outline-dark">
            ← Вернуться в каталог по категориям
        </a>
    </div>

<?php else: ?>
    <p>В данной категории пока нет товаров.</p>
<?php endif; ?>
