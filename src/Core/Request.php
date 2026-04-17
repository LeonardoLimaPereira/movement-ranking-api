<?php

namespace App\Core;

class Request
{
    private array $data;

    public function __construct()
    {
        $this->data = $_REQUEST;

        $contentType = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
        
        if (str_contains(strtolower($contentType), 'application/json')) {
            $json = json_decode(file_get_contents('php://input'), true);
            if (is_array($json)) {
                $this->data = array_merge($this->data, $json);
            }
        }
    }

    public function all(): array
    {
        return $this->data;
    }

    public function input(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }
}