<?php
use yii\helpers\Html;

/** @var \app\models\Favorite[] $favorites */

$this->title = 'Избранные изделия';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (empty($favorites)): ?>
    <p>У вас пока нет избранных изделий.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($favorites as $favorite): ?>
            <?php $product = $favorite->product; ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <?php if ($product->main_image): ?>
                        <img src="/<?= $product->main_image ?>" class="card-img-top" alt="<?= Html::encode($product->name) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($product->name) ?></h5>
                        <p class="card-text"><?= Html::encode($product->description) ?></p>
                        <p><strong><?= $product->price ?> руб.</strong></p>
                        <?= Html::a('Удалить из избранного', ['favorite/remove', 'product_id' => $product->id], [
                            'class' => 'btn btn-danger btn-sm'
                        ]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
