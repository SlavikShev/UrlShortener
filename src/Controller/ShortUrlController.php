<?php

namespace App\Controller;

use App\Command\CreateUrlCommand;
use App\Requests\ShortUrlRequest;
use App\Service\TransformUrlService;
use App\Service\ValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ShortUrlController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorService $validatorService,
        private readonly CreateUrlCommand $createUrlCommand,
        private readonly TransformUrlService $transformUrlService
    ) {}

    #[Route('/short-url', name: 'app_short_url', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $shortUrlRequest = $this->serializer->deserialize(
            $request->getContent(),
            ShortUrlRequest::class,
            'json'
        );

        $violations = $this->validatorService->validate($shortUrlRequest);
        if (count($violations) > 0) {
            return $this->json(['errors' => $violations], 400);
        }

        $urlEntity = $this->createUrlCommand->execute($shortUrlRequest);
        $encodedString = $this->transformUrlService->encodeNumberToString($urlEntity->getId());
        $shortUrl = $request->getSchemeAndHttpHost() . '/' . $encodedString;

        return $this->json(['shortUrl' => $shortUrl]);
    }
}
