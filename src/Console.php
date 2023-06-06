<?php

namespace Sto2\DevTools;

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

        $ch = curl_init('https://dev.sto2.hr/api/report-exception');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_exec($ch);
        curl_close($ch);
    }
}