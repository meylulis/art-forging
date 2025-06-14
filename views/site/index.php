<?php 
use yii\helpers\Html;
use yii\helpers\Url;  
 ?>

<div class="hero-section position-relative text-white">
  <div class="hero-background">
    
    <!-- Затемнение и центрированный текст -->
    <div class="hero-overlay">
      <div class="hero-content text-center">
        <h1 class="display-3 fw-bold mb-3">Мастерская «Степь»</h1>
        <p class="lead">Художественная ковка с душой и характером</p>
      </div>

      <!-- Слоганы внизу картинки -->
      <div class="hero-slogans">
        <span class="slogan-box"><img src="/web/images/metalworking.png" class="slogan-icon"> Быстро выполняем свою работу</span>
        <span class="slogan-box"><img src="/web/images/quality-control.png" class="slogan-icon"> Используем качественные материалы</span>
        <span class="slogan-box"><img src="/web/images/reliability.png" class="slogan-icon"> Гарантируем долговечность изделий</span>
        <span class="slogan-box"><img src="/web/images/individuality.png" class="slogan-icon"> Индивидуальный подход к каждому</span>
        <span class="slogan-box"><img src="/web/images/give-love.png" class="slogan-icon"> Делаем с любовью и вниманием</span>
      </div>

    </div>
  </div>
</div>

<div class="container my-5">
    <h2 class="text-center mb-5">Каталог художественной ковки</h2>

    <div class="row g-4">
        <?php 
        // Собираем все продукты из массива $products, который сейчас по категориям
        $allProducts = [];
        foreach ($products as $categoryId => $categoryProducts) {
            foreach ($categoryProducts as $product) {
                $allProducts[] = $product;
            }
        }

        // Ограничиваем количество товаров до 9 (можно 6)
        $allProducts = array_slice($allProducts, 0, 9);

        foreach ($allProducts as $product): ?>
            <div class="col-lg-4 col-md-6">
                <a href="<?= Url::to(['products/view', 'id' => $product->id]) ?>" class="catalog-card text-decoration-none d-block overflow-hidden shadow" style="border-radius: 20px;">
                    <div class="catalog-image-wrapper">
                        <img src="<?= Yii::getAlias('@web/' . $product->main_image) ?>" alt="<?= Html::encode($product->name) ?>">
                    </div>
                </a>
                <div class="catalog-title text-center mt-2">
                    <h5 class="product-name"><?= Html::encode($product->name) ?></h5>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<!-- Блок нейросети: слайдер сгенерированных изображений и отзывы -->
<div class="container my-5">
    <h2 class="text-center mb-4">Идеи от нейросети для вашего изделия</h2>
    
    <div id="kandinskyCarousel" class="carousel slide" data-ride="carousel" data-interval="2000">
      <div class="carousel-inner">

        <?php
        $generatedImages = [
            [
                'url' => Yii::getAlias('@web') . '/generated/gen_1.jpg',
                'review' => 'Очень понравилась идея! Отличное вдохновение.',
                'author' => 'Марина П.'
            ],
            [
                'url' => Yii::getAlias('@web') . '/generated/gen_4.jpg',
                'review' => 'Помогло определиться с дизайном моего ворот.',
                'author' => 'Игорь С.'
            ],
            [
                'url' => Yii::getAlias('@web') . '/generated/gen_3.jpg',
                'review' => 'Красивые и уникальные варианты, рекомендую!',
                'author' => 'Анастасия К.'
            ],
        ];

        foreach ($generatedImages as $index => $item): ?>
          <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
            <div class="d-flex flex-column align-items-center">
                <img src="<?= Html::encode($item['url']) ?>" alt="Generated Image <?= $index+1 ?>">
                <div class="carousel-caption">
                    <p class="mb-1 fst-italic">"<?= Html::encode($item['review']) ?>"</p>
                    <small>- <?= Html::encode($item['author']) ?></small>
                </div>
            </div>
          </div>
        <?php endforeach; ?>

      </div>

      <a class="carousel-control-prev" href="#kandinskyCarousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Предыдущий</span>
      </a>
      <a class="carousel-control-next" href="#kandinskyCarousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Следующий</span>
      </a>
    </div>

    <div class="text-center mt-4">
    <a href="/web/kandinsky" class="btn-dark-custom">Попробовать сгенерировать своё</a> 
    </div>
</div>


<!-- Блок с ходом работы -->
<div class="work-process-section py-5">
    <div class="container">
        <h2 class="text-center mb-5">Ход работы</h2>
        <div class="row justify-content-center text-center">
            <!-- Шаг 1 -->
            <div class="col-md-4">
                <div class="work-step-card p-4 mb-4">
                    <div class="icon-container mx-auto mb-3">
                        <img src="/web/images/pencil-ikon.png" alt="Проектирование" class="step-icon">
                    </div>
                    <div class="step-number bg-primary text-white mb-2">1</div>
                    <h5 class="step-title">Проектирование</h5>
                    <p class="step-description">Создаем индивидуальный проект с учетом всех ваших пожеланий.</p>
                </div>
            </div>

            <!-- Шаг 2 -->
            <div class="col-md-4">
                <div class="work-step-card p-4 mb-4">
                    <div class="icon-container mx-auto mb-3">
                        <img src="/web/images/file-ikon.png" alt="Документация" class="step-icon">
                    </div>
                    <div class="step-number bg-success text-white mb-2">2</div>
                    <h5 class="step-title">Документация</h5>
                    <p class="step-description">Оформляем технические документы и согласовываем детали.</p>
                </div>
            </div>

            <!-- Шаг 3 -->
            <div class="col-md-4">
                <div class="work-step-card p-4 mb-4">
                    <div class="icon-container mx-auto mb-3">
                        <img src="/web/images/calculator-ikon.png" alt="Расчет" class="step-icon">
                    </div>
                    <div class="step-number bg-warning text-white mb-2">3</div>
                    <h5 class="step-title">Расчет</h5>
                    <p class="step-description">Подсчитываем стоимость проекта и сроки реализации.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Блок информации -->
<div class="container my-5">
    <h2 class="text-center mb-4">Удивительные факты о художественной ковке</h2>
</div>

<div class="forging-info-block py-5">
    <div class="container">

        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="/web/images/forge-history.jpg" class="img-fluid rounded shadow info-photo" alt="История ковки">
            </div>
            <div class="col-md-6 text-white">
                <h4>История ремесла</h4>
                <p>Художественная ковка берет своё начало более 3000 лет назад. Изделия создавались вручную и служили не только утилитарными целями, но и декоративными.</p>
            </div>
        </div>

        <div class="row align-items-center flex-md-row-reverse mb-5">
            <div class="col-md-6">
                <img src="/web/images/forge-tools.jpg" class="img-fluid rounded shadow info-photo" alt="Инструменты">
            </div>
            <div class="col-md-6 text-white">
                <h4>Инструменты мастера</h4>
                <p>Молот, клещи и наковальня — символы кузнечного мастерства. Современные кузнецы используют их и по сей день, придавая металлу форму с невероятной точностью.</p>
            </div>
        </div>

        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <img src="/web/images/forge-style.jpg" class="img-fluid rounded shadow info-photo" alt="Декоративная ковка">
            </div>
            <div class="col-md-6 text-white">
                <h4>Эстетика и стиль</h4>
                <p>Современная ковка — это не просто технология. Это форма искусства, в которой прочный металл превращается в изящные узоры, вдохновленные природой и архитектурой.</p>
            </div>
        </div>
    </div>
</div>

<!-- Блок контакты -->
<div class="container my-5">
    <h2 class="text-center mb-4">Наши контакты</h2>

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