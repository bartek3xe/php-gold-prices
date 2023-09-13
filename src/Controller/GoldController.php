<?php

namespace App\Controller;

use App\NBP\Processor\GoldProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GoldController extends AbstractController
{
    public function __construct(private readonly GoldProcessor $processor)
    {
    }

    #[Route('/api/gold', name: 'app_gold')]
    public function index(): JsonResponse
    {
        $from = new \DateTime('yesterday');
        $to   = new \DateTime('today');

        $response = $this->processor->processAverageGoldCost($from, $to);

        return new JsonResponse($response);
    }
}
