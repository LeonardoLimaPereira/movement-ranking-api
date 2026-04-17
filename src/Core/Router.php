<?php

declare(strict_types=1);

namespace App\Core;

use InvalidArgumentException;
use BadMethodCallException;
use App\Core\Response;

class Router
{
    private array $routes = [];
    private string $prefix = '';

    public function prefix(string $prefix, callable $callback): void
    {
        $oldPrefix = $this->prefix;
        $this->prefix .= $prefix;

        $callback($this);

        $this->prefix = $oldPrefix;
    }

    public function get(string $uri, $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute(string $method, string $uri, $action): void
    {
        if (!is_callable($action) && !is_array($action)) {
            throw new InvalidArgumentException('Ação inválida para rota');
        }

        $this->routes[$method][$this->prefix . $uri] = $action;
    }

    public function dispatch(string $uri, string $httpMethod, Container $container)
    {
        $action = $this->routes[$httpMethod][$uri] ?? null;

        if (!$action) {
            Response::json(['error' => 'Não encontrado'], 404);
            return;
        }

        // Controller@method
        if (is_array($action)) {
            [$controller, $method] = $action;

            $instance = $container->get($controller);

            if (!method_exists($instance, $method)) {
                throw new BadMethodCallException("Método {$method} não existe no controller");
            }

            return $instance->$method(new Request());
        }

        // Closure
        return $action($container);
    }
}