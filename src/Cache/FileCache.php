<?php

namespace App\Cache;

class FileCache
{
    private string $path;

    public function __construct(string $path = __DIR__ . '/../../cache')
    {
        $this->path = $path;

        if (!is_dir($this->path)) {
            mkdir($this->path, 0755, true);
        }
    }

    public function get(string $key): mixed
    {
        $file = $this->getFileName($key);

        if (!file_exists($file)) {
            return null;
        }

        $data = json_decode(file_get_contents($file), true);

        if ($data['expires_at'] < time()) {
            unlink($file);
            return null;
        }

        return $data['value'];
    }

    public function set(string $key, mixed $value, int $ttl = 60): void
    {
        $file = $this->getFileName($key);

        $payload = [
            'expires_at' => time() + $ttl,
            'value' => $value
        ];

        file_put_contents($file, json_encode($payload), LOCK_EX);
    }

    private function getFileName(string $key): string
    {
        return $this->path . '/' . md5($key) . '.json';
    }
}