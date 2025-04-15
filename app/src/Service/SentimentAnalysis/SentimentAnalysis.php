<?php

declare(strict_types=1);

namespace App\Service\SentimentAnalysis;

use App\Service\SentimentAnalysis\Exception\SentimentAnalysisRuntimeException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

final class SentimentAnalysis
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly string          $apiKey,
    )
    {
    }

    public function analyze($comment)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ];

        $params = [
            "inputs" => $comment,
        ];

        try {
            $response = $this->client->request('POST', 'https://api-inference.huggingface.co/models/cardiffnlp/twitter-roberta-base-sentiment', [
                'json' => $params,
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            // Расшифровка меток
            $labelMap = [
                'LABEL_0' => 'Negative',
                'LABEL_1' => 'Neutral',
                'LABEL_2' => 'Positive'
            ];
            // Обработка и вывод результатов
            foreach ($result as $key => $item) {

                if (array_key_exists('LABEL_2', $labelMap)) {
                    return round($item[$key]["score"], 3);

                }
            }

        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $errorResponse = $e->getResponse()->getBody();
                echo "API Error: " . ($errorResponse['error'] ?? $e->getMessage());
            } else {
                echo "Request Error: " . $e->getMessage();
            }
        }
    }

    private function parseResponse($response): array
    {
        $body = (string)$response->getBody();

        if ($body === '') {
            return [];
        }

        try {
            $data = json_decode($body, true, 32, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new SentimentAnalysisRuntimeException('[SentimentAnalysis] Invalid JSON response', 0, $e);
        }

        return $data;
    }
}