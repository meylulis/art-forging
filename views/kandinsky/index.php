<?php
$this->title = 'Генерация узора';
?>

<div class="container-my5">
<h2 class="text-center mb-4">Создай узор для кованого изделия</h2>

<p>Введите описание (например: "классический орнамент с завитками и листьями")</p>

<input type="text" id="prompt" placeholder="Описание узора" style="width: 400px;">
<button id="generate-btn">Сгенерировать</button>

<div id="loading" style="margin-top: 15px; display: none;">⏳ Генерация изображения...</div>
<div id="result" style="margin-top: 20px;"></div>
</div>

<script>
document.getElementById('generate-btn').addEventListener('click', function () {
    const prompt = document.getElementById('prompt').value.trim();
    const resultDiv = document.getElementById('result');
    const loadingDiv = document.getElementById('loading');

    resultDiv.innerHTML = '';
    if (!prompt) {
        resultDiv.innerHTML = '<span style="color:red;">Введите описание.</span>';
        return;
    }

    loadingDiv.style.display = 'block';

    fetch(window.location.pathname + '/generate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({ prompt: prompt })
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.style.display = 'none';
        if (data.status === 'ok') {
            resultDiv.innerHTML = '<img src="' + data.url + '" width="512"><br><br>' +
                                  '<a href="' + data.url + '" target="_blank">Открыть изображение</a>';
        } else {
            resultDiv.innerHTML = '<span style="color:red;">Ошибка: ' + data.message + '</span>';
        }
    })
    .catch(() => {
        loadingDiv.style.display = 'none';
        resultDiv.innerHTML = '<span style="color:red;">Ошибка при соединении с сервером.</span>';
    });
});
</script>
