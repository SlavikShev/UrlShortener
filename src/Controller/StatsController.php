<?php

namespace App\Controller;

use App\Repository\UrlRepository;
use App\Service\TransformUrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatsController extends AbstractController
{
    public function __construct(
        private readonly UrlRepository $urlRepository,
        private readonly TransformUrlService $transformUrlService,
    ) {}

    #[Route('/{slug}/stats', name: 'stats', methods: ['GET'])]
    public function stats(Request $request, string $slug): Response
    {
        $urlId = $this->transformUrlService->decodeStringToNumber($slug);
        $urlEntity = $this->urlRepository->find($urlId);

        if ($urlEntity) {
            return $this->render('stats/stats.html.twig', [
                'fullLink' => $urlEntity->getValue(),
                'clicks' => $urlEntity->getClicksCount(),
                'shortLink' => $request->getSchemeAndHttpHost() . '/' . $slug,
            ]);
        }

        throw $this->createNotFoundException('Short link not found');
    }
}