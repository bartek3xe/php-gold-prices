<?php

namespace App\Controller;

use App\NBP\Processor\GoldProcessor;
use App\NBP\Service\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GoldController extends AbstractController
{
    private readonly FilesystemAdapter $cache;

    public function __construct(
        private readonly GoldProcessor $processor,
        private readonly Validator $validator,
    ) {
        $this->cache = new FilesystemAdapter();
    }

    #[Route('/api/gold', name: 'app_gold', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!$requestData || !isset($requestData['from']) || !isset($requestData['to'])) {
            return $this->validator->createErrorJsonResponse('Invalid JSON data');
        }

        $from = $requestData['from'];
        $to   = $requestData['to'];

        if (!Validator::isValidGoldDateFormat($from) || !Validator::isValidGoldDateFormat($to)) {
            return $this->validator->createErrorJsonResponse(
                'Invalid date format. Please use the ISO 8601 date and time format'
            );
        }

        try {
            $fromDate = new \DateTime($from);
            $toDate   = new \DateTime($to);
        } catch (\Exception $exception) {
            return $this->validator->createErrorJsonResponse('Invalid date format');
        }

        if ($fromDate < new \DateTime(Validator::GOLD_HISTORICAL_DATA_START_DATE)) {
            return $this->validator->createErrorJsonResponse(
                'Requested data is not available before ' . Validator::GOLD_HISTORICAL_DATA_START_DATE
            );
        }

        if (!$this->validator->isValidGoldDateRangeDuration($fromDate, $toDate)) {
            return $this->validator->createErrorJsonResponse(
                'The requested date range exceeds the maximum allowed duration of 93 days'
            );
        }

        $cachedResult = $this->getCacheResult($fromDate, $toDate);

        if (!$cachedResult->isHit()) {
            $result = $this->processor->processAverageGoldCost($fromDate, $toDate);

            $cachedResult->set($result);
            $this->cache->save($cachedResult);
        } else {
            $result = $cachedResult->get();
        }

        return $this->json($result);
    }

    private function getCacheResult(\DateTime $fromDate, \DateTime $toDate): CacheItem
    {
        $cacheKey = 'gold_price_' . $fromDate->format('Y-m-d') . '_' . $toDate->format('Y-m-d');
        return $this->cache->getItem($cacheKey);
    }
}
