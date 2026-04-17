<?php

namespace App\Controller;

use App\Service\RankingService;
use App\Core\Request;
use App\Core\Response;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class RankingController
{
    public function __construct(private RankingService $service) {}

    public function index(Request $request): void
    {
        try {
            $movement = $request->input('movement');

            if (!$movement || trim($movement) === '') {
                throw new InvalidArgumentException("Parâmetro 'movement' é obrigatório");
            }

            $data = $this->service->getRanking($movement);

            Response::json($data);

        } catch (InvalidArgumentException $e) {
            Response::json(['error' => $e->getMessage()], 400);
        } catch (RuntimeException $e) {
            Response::json(['error' => $e->getMessage()], 404);
        } catch (Throwable $e) {
            Response::json(['error' => 'Erro interno do servidor'], 500);
        }
    }
}