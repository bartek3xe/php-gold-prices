<?php

namespace App\Controller;

use App\NBP\Processor\GoldProcessor;
use App\NBP\Service\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoldController extends AbstractController
{
    public function __construct(
        private readonly GoldProcessor $processor,
        private readonly Validator $validator,
    ) {
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

        try {
            $fromDate = new \DateTime($from);
            $toDate   = new \DateTime($to);
        } catch (\Exception $exception) {
            return $this->validator->createErrorJsonResponse('Invalid date format');
        }

        return $this->json($this->processor->processAverageGoldCost($fromDate, $toDate));
    }
}
