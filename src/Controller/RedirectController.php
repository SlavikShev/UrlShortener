<?php

namespace App\Controller;

use App\Command\UpdateUrlStatisticCommand;
use App\Repository\UrlRepository;
use App\Service\TransformUrlService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RedirectController extends AbstractController
{
    public function __construct(
        private readonly UrlRepository $urlRepository,
        private readonly TransformUrlService $transformUrlService,
        private readonly UpdateUrlStatisticCommand $updateUrlStatisticCommand
    ) {}

    #[Route('/{slug}', name: 'short_link', methods: ['GET'])]
    public function redirectToFullUrl(string $slug): Response
    {
        $urlId = $this->transformUrlService->decodeStringToNumber($slug);

        $urlEntity = $this->urlRepository->find($urlId);

        if ($urlEntity) {
            $this->updateUrlStatisticCommand->execute($urlEntity);
            return $this->redirect($urlEntity->getValue());
        }

        return $this->render('linkNotFound/linkNotFound.html.twig');
    }
}
