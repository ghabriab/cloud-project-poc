<?php

namespace App\Controller;

use Prometheus\CollectorRegistry;
use Prometheus\RenderTextFormat;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/metrics', name: 'prometheus_metrics_', methods: ['GET'])]
final class PrometheusMetricsController extends AbstractController
{
    public function __construct(
        private readonly CollectorRegistry $registry,
    ) {
    }

    public function __invoke(): Response
    {
        $renderer = new RenderTextFormat();

        return new Response(
            $renderer->render($this->registry->getMetricFamilySamples()),
            Response::HTTP_OK,
            ['Content-Type' => RenderTextFormat::MIME_TYPE]
        );
    }
}
