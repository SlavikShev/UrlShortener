<?php

namespace App\Controller;

use App\Entity\Url;
use App\Requests\ShortUrlRequest;
use App\Service\TransformUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShortUrlController extends AbstractController
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {}

    #[Route('/short-url', name: 'app_short_url', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, TransformUrlService $service): JsonResponse
    {
        $shortUrlRequest = $this->serializer->deserialize(
            $request->getContent(),
            ShortUrlRequest::class,
            'json'
        );

        $violations = $this->validator->validate($shortUrlRequest);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return $this->json(['errors' => $errors], 400);
        }

        $urlEntity = new Url();
        $urlEntity->setValue($shortUrlRequest->getUrl());
        $urlEntity->setExpiresAt($shortUrlRequest->getDaysToLive());
        $entityManager->persist($urlEntity);
        $entityManager->flush();

        $encodedString = $service->encodeNumberToString($urlEntity->getId());
        $shortUrl = $request->getSchemeAndHttpHost() . '/' . $encodedString;

        return $this->json(['shortUrl' => $shortUrl]);
    }
}
