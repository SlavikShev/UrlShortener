<?php

namespace App\Controller;

use App\Repository\UrlRepository;
use App\Service\TransformUrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    public function __construct(
        private readonly UrlRepository $urlRepository,
        private readonly TransformUrlService $transformUrlService
    ) {}

    #[Route('/{slug}', name: 'short_link', methods: ['GET'])]
    public function redirectToFullUrl(string $slug): RedirectResponse
    {
        $urlId = $this->transformUrlService->decodeStringToNumber($slug);

        $urlEntity = $this->urlRepository->find($urlId);

        if ($urlEntity) {
            return $this->redirect($urlEntity->getValue());
        }

        throw $this->createNotFoundException('Short link not found');
    }
}