<?php

use App\Core\Router;
use App\Controller\RankingController;

/** @var Router $router */

$router->get('/ranking', [RankingController::class, 'index']);