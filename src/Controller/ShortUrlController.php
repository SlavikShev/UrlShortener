<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ShortUrlController extends AbstractController
{
    #[Route('/short-url', name: 'app_short_url')]
    public function index(): JsonResponse
    {
        return $this->json('Hello World!');
    }
}
