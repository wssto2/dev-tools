<?php

namespace Sto2\DevTools;

use GuzzleHttp\Client;

class Console
{
    public static function reportException(string $projectUuid, \Throwable $e): void
    {
        $payload = [
            'project_uuid' => $projectUuid,
            'environment' => $_SERVER ['APP_ENV'] ?? null,
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ];

        $client = new Client();
        $client->post('https://dev.sto2.hr/api/report-exception', [
            'json' => $payload,
        ]);
    }
}