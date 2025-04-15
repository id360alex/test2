<?php

declare(strict_types=1);

namespace App\Infrastructure\Guzzle;

use GuzzleHttp\RequestOptions;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;

class Client extends \GuzzleHttp\Client // @phpstan-ignore-line
{
    protected array $config;
    protected LoggerInterface $logger;

    public function __construct(array $config, LoggerInterface $logger)
    {
        if (isset($config[RequestOptions::PROXY]) && $config[RequestOptions::PROXY] === '') {
            unset($config[RequestOptions::PROXY]);
        }

        if (isset($config[RequestOptions::AUTH]) && count(array_filter($config[RequestOptions::AUTH])) === 0) {
            unset($config[RequestOptions::AUTH]);
        }

        $config[RequestOptions::ON_STATS] = static function (TransferStats $stats) use ($logger, $config) {
            $scheme = $stats->getEffectiveUri()->getScheme();
            $host = $stats->getEffectiveUri()->getHost();
            $stat = $stats->getHandlerStats();
            $request = $stats->getRequest();
            $response = $stats->getResponse();

            $totalTime = $stat['total_time'] ?? $config[RequestOptions::TIMEOUT];

            $logger->notice(
                'Guzzle request: ' . $request->getMethod() . ' ' . $scheme . '://' . $host . $request->getRequestTarget(),
                [
                    'guzzle_request_method' => $request->getMethod(),
                    'guzzle_request_host' => $host,
                    'guzzle_request_target' => $request->getRequestTarget(),
                    'guzzle_request_headers' => self::getRequestHeaders($request),
                    'guzzle_request_body' => (string)$request->getBody(),
                    'guzzle_response_headers' => $response?->getHeaders(),
                    'guzzle_response_body' => $response ? (string)$response->getBody() : null,
                    'guzzle_response_status' => $response?->getStatusCode(),
                    'guzzle_total_time' => $totalTime,
                ],
            );
        };

        $this->config = $config;
        $this->logger = $logger;
        parent::__construct($config);
    }

    private static function getRequestHeaders(RequestInterface $request): array
    {
        $headers = $request->getHeaders();

        if (isset($headers['Authorization'])) {
            $headers['Authorization'] = ['***'];
        }

        return $headers;
    }
}
