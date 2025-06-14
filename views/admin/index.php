<?php 

use yii\helpers\Html;

$this->title = 'Админ-панель';
?>
<div class="admin-index">
        <h1><?= Html::encode($this->title) ?></h1>

        <div class="list-group">
        <?= Html::a('Управление изделиями', ['products/index'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>

                <div class="list-group">
        <?= Html::a('Управление заявками', ['requests/index'], ['class' => 'list-group-item list-group-item-action']) ?>
        </div>
</div>