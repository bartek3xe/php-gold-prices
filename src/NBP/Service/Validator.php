<?php

namespace App\NBP\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Validator
{
    const GOLD_HISTORICAL_DATA_START_DATE      = '2011-02-01';
    const GOLD_VALID_DATE_FORMAT_PATTERN       = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/';
    const GOLD_MAX_ALLOWED_DURATION_IN_SECONDS = 93 * 24 * 60 * 60;

    public static function isValidGoldDateFormat(string $date): bool
    {
        return !!preg_match(self::GOLD_VALID_DATE_FORMAT_PATTERN, $date);
    }

    public function isValidGoldDateRangeDuration(\DateTime $fromDate, \DateTime $toDate): bool
    {
        $fromTimestamp         = $fromDate->getTimestamp();
        $toTimestamp           = $toDate->getTimestamp();
        $dateDifferenceSeconds = abs($toTimestamp - $fromTimestamp);

        return $dateDifferenceSeconds <= self::GOLD_MAX_ALLOWED_DURATION_IN_SECONDS;
    }

    public function createErrorJsonResponse(string $message): JsonResponse
    {
        return new JsonResponse(['error' => $message], Response::HTTP_BAD_REQUEST);
    }
}
