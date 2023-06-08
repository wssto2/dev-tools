<?php

namespace Sto2\DevTools;

use GuzzleHttp\Client;

class Console
{
    public static function reportException(string $projectUuid, \Throwable $e, ?array $meta = null): void
    {
        try {

            $payload = [
                'project_uuid' => $projectUuid,
                'environment' => $_SERVER ['APP_ENV'] ?? null,
                'exception_class' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'code' => $e->getCode(),
                'code_snippet' => self::getCodeSnippet($e->getFile(), $e->getLine()),
                'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
                'request_method' => $_SERVER['REQUEST_METHOD'] ?? null,
                'request_session' => $_SESSION ?? null,
                'request_server' => $_SERVER ?? null,
                'request_get' => $_GET ?? null,
                'request_post' => $_POST ?? null,
                'request_files' => $_FILES ?? null,
                'request_env' => $_ENV ?? null,
                'request_cookie' => $_COOKIE ?? null,
                'meta' => $meta,
            ];

            $client = new Client();
            $client->post('https://dev.sto2.hr/api/report-exception', [
                'json' => $payload,
            ]);
        } catch (\Throwable $e) {
            // do nothing
        }
    }

    private static function getCodeSnippet(string $file, int $line): string
    {
        $lines = file($file);
        $start = max(0, $line - 15);
        $end = min(count($lines), $line + 15);

        $snippet = '';
        for ($i = $start; $i < $end; $i++) {
            $snippet .= $lines[$i];
        }

        return $snippet;
    }
}