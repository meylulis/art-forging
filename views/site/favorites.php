<?php
use yii\helpers\Html;

/** @var $favorites \app\models\Favorite[] */

$this->title = 'Избранные изделия';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php if (empty($favorites)): ?>
    <p>Вы ещё не добавили ни одного изделия в избранное.</p>
<?php else: ?>
    <div class="row">
        <?php foreach ($favorites as $fav): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if ($fav->product && $fav->product->main_image): ?>
                        <img src="<?= Yii::getAlias('@web') . '/' . $fav->product->main_image ?>" class="card-img-top" alt="...">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= Html::encode($fav->product->name) ?></h5>
                        <p class="card-text"><?= Html::encode($fav->product->description) ?></p>
                        <a href="<?= \yii\helpers\Url::to(['/products/view', 'id' => $fav->product->id]) ?>" class="btn btn-primary">Подробнее</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
