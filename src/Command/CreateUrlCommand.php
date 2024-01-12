<?php

namespace App\Command;

use App\Entity\Url;
use App\Requests\ShortUrlRequest;
use Doctrine\ORM\EntityManagerInterface;

class CreateUrlCommand
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function execute(ShortUrlRequest $shortUrlRequest): Url
    {
        $urlEntity = new Url();
        $urlEntity->setValue($shortUrlRequest->getUrl());
        $urlEntity->setExpiresAt($shortUrlRequest->getDaysToLive());
        $this->entityManager->persist($urlEntity);
        $this->entityManager->flush();

        return $urlEntity;
    }
}