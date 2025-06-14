<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>

    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">

    <!-- ✅ Bootstrap 4 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Bootstrap Icons (по желанию) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <!-- ✅ Стили -->
    <?php $this->registerCssFile('/css/site.css'); ?>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@600&display=swap" rel="stylesheet">

    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
<?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/logo.jpg', ['alt' => 'Степь', 'height' => '40']),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-lg navbar-dark bg-dark custom-navbar d-flex justify-content-between align-items-center',
        ],
    ]);

$menuItems = [];

if (Yii::$app->user->isGuest) {
    $menuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
        ['label' => 'Каталог', 'url' => ['/products/catalog']],
        ['label' => 'Нейросеть', 'url' => ['/kandinsky']],
        ['label' => 'Контакты', 'url' => ['/site/contact']],
        ['label' => 'Войти', 'url' => ['/site/login']],
    ];
} else {
    $roleId = Yii::$app->user->identity->role_id;
    if ($roleId == 1) {
        $menuItems = [
            ['label' => 'Админка', 'url' => ['/admin/index']],
            ['label' => 'Выйти (' . Yii::$app->user->identity->username . ')', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
        ];
    } else {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Каталог', 'url' => ['/products/catalog']],
            ['label' => 'Нейросеть', 'url' => ['/kandinsky']],
            ['label' => 'Контакты', 'url' => ['/site/contact']],
            '<li>' . Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline']) .
                Html::submitButton('Выйти (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link logout text-white']) .
                Html::endForm() . '</li>',
        ];
    }
}

echo '<div class="d-flex w-100 justify-content-between align-items-center">';

echo Nav::widget([
    'options' => ['class' => 'navbar-nav flex-row gap-3'],
    'items' => $menuItems,
]);

if (Yii::$app->user->isGuest) {
    echo Html::tag('div',
        '<div class="contact-info text-end">
            <div>📧 kovka@example.com</div>
            <div>📞 +7 (900) 123-45-67</div>
        </div>',
        ['class' => 'd-none d-lg-block']
    );
} else {
    echo Html::tag('div',
        Html::a(
            Html::img('@web/images/user.png', [
                'alt' => 'Профиль',
                'style' => 'height:24px; width:auto; margin-right:8px; display:inline-block; vertical-align:middle;'
            ]) . 'Профиль',
            ['/site/profile'],
            ['class' => 'nav-link text-white', 'style' => 'display:inline-block;']
        ),
        ['class' => 'text-end d-none d-lg-block']
    );
}

NavBar::end();
?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => $this->params['breadcrumbs'] ?? [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer w-100">
    <div class="w-100" style="background-color: black;">
        <div class="row align-items-center text-center text-md-center" style="padding: 10px;">
            <div class="col-md-4 mb-3 mb-md-0 align-items-center justify-content-center">
                <h5 class="text-uppercase">О компании</h5>
                <p class="small" style="color: white;">
                    Мастерская «Степь» — это художественная ковка ручной работы. Мы создаём уникальные изделия: от ворот и ограждений до мебели и элементов декора.
                </p>
            </div>

            <div class="col-md-4 mb-3">
                <h5 class="text-uppercase">Навигация</h5>
                <ul class="list-unstyled">
                    <li><a href="/site/index" class="text-light">Главная</a></li>
                    <li><a href="/products/catalog" class="text-light">Каталог</a></li>
                    <li><a href="/site/pricing" class="text-light">Цены</a></li>
                    <li><a href="/site/contact" class="text-light">Контакты</a></li>
                    <?php if (Yii::$app->user->isGuest): ?>
                        <li><a href="/site/login" class="text-light">Войти</a></li>
                    <?php else: ?>
                        <li>
                            <?= Html::beginForm(['/site/logout'], 'post') .
                                Html::submitButton('Выйти (' . Yii::$app->user->identity->username . ')', ['class' => 'btn btn-link text p-0']) .
                                Html::endForm(); ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-md-4 mb-3" style="color: white;">
                <h5 class="text-uppercase">Контакты</h5>
                <p class="small mb-1"><strong>Телефон:</strong> +7 (900) 123-45-67</p>
                <p class="small mb-1"><strong>Email:</strong> kovka@example.com</p>
                <p class="small mb-0"><strong>Время работы:</strong> Пн–Сб: 9:00–18:00</p>
            </div>
        </div>

        <div class="text-center">
            <p class="small mb-0">&copy; <?= date('Y') ?> Мастерская «Степь». Все права защищены. <?= Yii::powered() ?></p>
        </div>
    </div>
</footer>

<!-- ✅ JS-зависимости Bootstrap 4 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
