<?php

use App\Core\Container;
use App\Database;

$container = new Container();
$container->bind(PDO::class, fn() => Database::getConnection());

return $container;