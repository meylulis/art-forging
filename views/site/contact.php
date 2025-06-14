<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use app\models\Requests;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\captcha\Captcha;

?>
<div class="site-contact">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <!-- Рамки с контактной информацией -->
    <div class="container my-3">
    <h2 class="text-center mb-1">Наши контакты</h2>

    <div class="row g-4 justify-content-center contact-block">
        <div class="col-md-4">
            <div class="contact-card text-center p-4 h-100 shadow-sm rounded">
                <img src="/web/images/phone-ikon.jpg" alt="Телефон" class="contact-icon mb-3">
                <h5>Телефон</h5>
                <p class="mb-0">+7 (900) 123-45-67</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="contact-card text-center p-4 h-100 shadow-sm rounded">
                <img src="/web/images/mail-icon.jpg" alt="Почта" class="contact-icon mb-3">
                <h5>Email</h5>
                <p class="mb-0">Здесь почта</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="contact-card text-center p-4 h-100 shadow-sm rounded">
                <img src="/web/images/time-icon.jpg" alt="График работы" class="contact-icon mb-3">
                <h5>График работы</h5>
                <p class="mb-0">График работы</p>
            </div>
        </div>
    </div>
</div>

    <hr>
<!-- Форма обратной связи -->
<div class="contact-form">
    <h2 class="text-center">Обратная связь</h2>
    <div class="site-contact">
        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= Yii::$app->session->getFlash('contactFormSubmitted') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Закрыть"></button>
            </div>
        <?php endif; ?>

        <p class="text-center">Если у вас есть вопросы или предложения, заполните форму ниже.</p>

        <?php if (Yii::$app->user->isGuest): ?>
            <div class="alert alert-warning text-center">
                Пожалуйста, <a href="<?= \yii\helpers\Url::to(['site/login']) ?>">войдите</a> или 
                <a href="<?= \yii\helpers\Url::to(['users/create']) ?>">зарегистрируйтесь</a>, чтобы отправить сообщение.
            </div>
        <?php else: ?>

            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

            <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label('Имя') ?>
            <?= $form->field($model, 'phone')->textInput()->label('Телефон') ?>
            <?= $form->field($model, 'message')->textarea(['rows' => 6])->label('Сообщение') ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

        <?php endif; ?>
    </div>
</div>



<?php $this->registerCss("
  
 
    /* Отступы и стили для блоков с контактами */
    .contact-block {
        margin-top: 50px;
    }


    /* Стили для формы обратной связи */
    .contact-form {
    background-color: #fff;
    border-radius: 15px;
    padding: 40px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: 30px auto;
    font-family: 'Roboto', sans-serif;
}

/* Заголовок формы */
.contact-form h2 {
    text-align: center;
    font-size: 36px;
    color: #4B3F23; /* Темно-коричневый оттенок */
    margin-bottom: 30px;
    font-weight: bold;
    letter-spacing: 1.5px;
    text-transform: uppercase;
}

/* Стиль для меток формы */
.contact-form .form-group label {
    font-size: 16px;
    color: #5a5a5a;
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

/* Стиль для полей ввода */
.contact-form .form-control {
    border: 2px solid #ddd;
    border-radius: 5px;
    padding: 12px 15px;
    font-size: 16px;
    background-color: #fff;
    transition: all 0.3s ease-in-out;
    margin-bottom: 20px;
    width: 100%;
}

.contact-form .form-control:focus {
    border-color: #8a5b42; /* Цвет, напоминающий металл */
    box-shadow: 0 0 5px rgba(138, 91, 66, 0.5);
}

/* Стиль для текстовой области */
.contact-form .form-control[rows] {
    height: 150px;
}

/* Стиль для кнопки отправки */
.contact-form .btn {
    background-color: #8a5b42; /* Цвет, напоминающий металл */
    border-color: #8a5b42;
    color: #fff;
    padding: 12px 20px;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    width: 100%;
    border-radius: 5px;
    transition: all 0.3s ease;
    cursor: pointer;
}

/* Эффект на кнопке */
.contact-form .btn:hover {
    background-color: #704220; /* Более темный оттенок металла */
    border-color: #704220;
}

/* Стиль успешного сообщения */
.contact-form .alert {
    background-color: #e0f7e0;
    color: #2e7d32;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
    text-align: center;
}

/* Стиль для текста подсказки */
.contact-form p {
    font-size: 16px;
    color: #555;
}

/* Поля ввода в одну строку (без переноса) */
.contact-form .form-group .form-control {
    margin-top: 10px;
}

/* Отступы для всего блока */
.contact-form .form-group {
    margin-bottom: 20px;
}
    /* Мобильная версия */
    @media (max-width: 767px) {
        .contact-card {
            text-align: center;
        }

        .contact-form {
            padding: 20px;
        }

        .contact-form h2 {
            font-size: 22px;
        }
    }
") ?>

