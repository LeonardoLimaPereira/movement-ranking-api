<?php

namespace App\Core;

use ReflectionClass;

class Container
{
    private array $bindings = [];

    public function bind(string $key, callable $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function get(string $key)
    {
        // Se tiver binding manual
        if (isset($this->bindings[$key])) {
            return $this->bindings[$key]($this);
        }

        // Senão tenta resolver automaticamente
        return $this->resolve($key);
    }

    private function resolve(string $class)
    {
        $reflection = new \ReflectionClass($class);

        if (!$reflection->isInstantiable()) {
            throw new \RuntimeException("Classe não instanciável: {$class}");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor) {
            return new $class;
        }

        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if (!$type) {
                throw new \RuntimeException("Dependência sem tipo em {$class}");
            }

            if ($type->isBuiltin()) {

                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                    continue;
                }

                throw new \RuntimeException("Não é possível resolver tipo primitivo: {$param->getName()}");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflection->newInstanceArgs($dependencies);
    }
}