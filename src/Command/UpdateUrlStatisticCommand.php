<?php

namespace App\Command;

use App\Entity\Url;
use Doctrine\ORM\EntityManagerInterface;

class UpdateUrlStatisticCommand
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function execute(Url $url): void
    {
        $url->setClicksCount($url->getClicksCount() + 1);
        $this->entityManager->persist($url);
        $this->entityManager->flush();
    }
}