<?php

declare(strict_types=1);

namespace App\Service;

use App\Cache\FileCache;
use App\Repository\MovementRepository;
use App\Repository\PersonalRecordRepository;

class RankingService
{
    private const CACHE_TTL = 60;

    private MovementRepository $movementRepository;
    private PersonalRecordRepository $recordRepository;
    private FileCache $cache;

    public function __construct(
        MovementRepository $movementRepository,
        PersonalRecordRepository $recordRepository,
        FileCache $cache
    ) {
        $this->movementRepository = $movementRepository;
        $this->recordRepository = $recordRepository;
        $this->cache = $cache;
    }

    public function getRanking(string $movementInput): array
    {
        $movement = $this->movementRepository->findByNameOrId($movementInput);

        if (!$movement) {
            throw new \RuntimeException("Movement não encontrado");
        }

        $cacheKey = "ranking_" . $movement['id'];
        $cachedRanking = $this->cache->get($cacheKey);

        if ($cachedRanking !== null && $cachedRanking !== false) {
            return $cachedRanking;
        }

        $response = [
            'movement' => $movement['name'],
            'ranking' => $this->recordRepository->getBestRecordsByMovementId($movement['id'])
        ];

        $this->cache->set($cacheKey, $response, self::CACHE_TTL);

        return $response;
    }
}