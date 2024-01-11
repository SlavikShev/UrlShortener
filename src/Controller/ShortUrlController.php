<?php

namespace App\Controller;

use App\Entity\Url;
use App\Service\TransformUrlService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ShortUrlController extends AbstractController
{
    #[Route('/short-url', name: 'app_short_url', methods: ['POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, TransformUrlService $service, ValidatorInterface $validator): JsonResponse
    {
        $url = $request->get('url');
        $url = trim($url);

        $constraints = [
            new NotBlank(),
            new \Symfony\Component\Validator\Constraints\Url()
        ];

        $violations = $validator->validate($url, $constraints);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return $this->json(['errors' => $errors], 400);
        }

        $urlEntity = new Url();
        $urlEntity->setValue($url);
        $entityManager->persist($urlEntity);
        $entityManager->flush();

        $encodedString = $service->encodeNumberToString($urlEntity->getId());
        $shortUrl = $request->getSchemeAndHttpHost() . '/' . $encodedString;

        return $this->json(['shortUrl' => $shortUrl]);
    }
}
