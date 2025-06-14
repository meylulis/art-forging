<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class KandinskyController extends Controller
{
    public $enableCsrfValidation = false;

    private $apiKey = 'Key 05A254D9B04AA0826DE676D6B513101C';
    private $secretKey = 'Secret 606794F236D7DD7F495F27F9A3B1D3C1';
    private $apiBaseUrl = 'https://api-key.fusionbrain.ai';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGenerate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $prompt = Yii::$app->request->post('prompt');
        if (!$prompt) {
            return ['status' => 'error', 'message' => 'Введите описание (prompt).'];
        }

        $pipelineId = $this->getPipelineId();
        if (!$pipelineId) {
            return ['status' => 'error', 'message' => 'Не удалось получить ID пайплайна.'];
        }

        $taskId = $this->createGenerationTask($pipelineId, $prompt);
        if (!$taskId) {
            return ['status' => 'error', 'message' => 'Не удалось создать задачу генерации.'];
        }

        // Ожидаем пока задача завершится, максимум 30 секунд
        $maxWait = 30;
        $interval = 3;
        $elapsed = 0;

        $image = null;
        while ($elapsed < $maxWait) {
            $image = $this->getGenerationResult($taskId);
            if ($image !== null) {
                break;
            }
            sleep($interval);
            $elapsed += $interval;
        }

        if (!$image) {
            return ['status' => 'error', 'message' => 'Не удалось получить изображение.'];
        }

        return ['status' => 'ok', 'url' => $image];
    }


    private function getPipelineId()
    {
        $url = 'https://api-key.fusionbrain.ai/key/api/v1/pipelines';
        $headers = [
            'X-Key: ' . $this->apiKey,
            'X-Secret: ' . $this->secretKey,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        if (!$response) return null;

        $data = json_decode($response, true);
        foreach ($data as $pipeline) {
            if ($pipeline['name'] === 'Kandinsky') {
                return $pipeline['id'];
            }
        }
        return null;
    }

    private function createGenerationTask($pipelineId, $prompt)
    {
        $url = 'https://api-key.fusionbrain.ai/key/api/v1/pipeline/run';

        $headers = [
            'X-Key: ' . $this->apiKey,
            'X-Secret: ' . $this->secretKey,
            // Не указываем Content-Type вручную!
        ];

        // Создаем временный файл с JSON-параметрами
        $paramsArray = [
            'type' => 'GENERATE',
            'numImages' => 1,
            'width' => 1024,
            'height' => 1024,
            'generateParams' => [
                'query' => $prompt,
            ],
        ];

        $tmpFilePath = tempnam(sys_get_temp_dir(), 'kandinsky_');
        file_put_contents($tmpFilePath, json_encode($paramsArray, JSON_UNESCAPED_UNICODE));

        // Оборачиваем в CURLFile, имитируя загрузку файла
        $postData = [
            'pipeline_id' => $pipelineId,
            'params' => new \CURLFile($tmpFilePath, 'application/json', 'params.json'),
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $response = curl_exec($ch);
        curl_close($ch);

        // Удаляем временный файл
        unlink($tmpFilePath);

        file_put_contents(Yii::getAlias('@runtime/logs/kandinsky-task.log'), $response);

        $result = json_decode($response, true);
        return $result['uuid'] ?? null;
    }

    public function getGenerationResult($taskId)
    {
        $url = 'https://api-key.fusionbrain.ai/key/api/v1/pipeline/status/' . $taskId;

        $headers = [
            'accept: application/json',
            'Content-Type: application/json',
            'X-Key: ' . $this->apiKey,
            'X-Secret: ' . $this->secretKey,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        $data = json_decode($response, true);

    if ($data) {
        // Логируем весь ответ для отладки (опционально)
        file_put_contents(Yii::getAlias('@runtime/logs/kandinsky-full-response.log'), json_encode($data) . PHP_EOL, FILE_APPEND);

        if (isset($data['result']['files'][0]) && !empty($data['result']['files'][0])) {
            file_put_contents(Yii::getAlias('@runtime/logs/kandinsky-image-base64.log'), $data['result']['files'][0] . PHP_EOL, FILE_APPEND);
        }
    }

        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return null;
        }

        $data = json_decode($response, true);
        if (isset($data['status']) && $data['status'] === 'DONE') {
            if (!empty($data['result']['files'][0])) {
                $imageBase64 = trim($data['result']['files'][0]);
                // Перекодировка для чистоты данных (по желанию)
                $imageBase64 = base64_encode(base64_decode($imageBase64));
                return 'data:image/jpeg;base64,' . $imageBase64;
            }
        }

        return null;
    }
}
