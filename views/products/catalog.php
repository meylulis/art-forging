<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<h2 class="text-center mb-5">Каталог изделий по категориям</h2>

<div class="row justify-content-center">
    <?php if (!empty($categoryData)): ?>
        <?php foreach ($categoryData as $cat): ?>
            <div class="col-md-6 col-lg-5 mb-4 d-flex justify-content-center">
                <div class="card h-100 shadow-sm custom-card">
                    <img src="<?= $cat['image'] ?>" class="card-img-top" alt="<?= Html::encode($cat['name']) ?>">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?= Html::encode($cat['name']) ?></h5>

                        <?php if ($cat['productCount'] > 0): ?>
                            <p class="card-text">
                                Товаров: <?= $cat['productCount'] ?><br>
                                от <?= Html::encode($cat['minPrice']) ?> ₽
                            </p>
                        <?php else: ?>
                            <p class="card-text text-muted">Нет товаров</p>
                        <?php endif; ?>

                        <a href="<?= Url::to(['products/catalog', 'category' => $cat['id']]) ?>" class="btn btn-outline-dark btn-sm">Наши работы</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Категории не найдены.</p>
    <?php endif; ?>
</div>
