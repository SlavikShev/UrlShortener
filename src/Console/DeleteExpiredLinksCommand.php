<?php

namespace App\Console;

use App\Entity\Url;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteExpiredLinksCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:delete-expired-links')
            ->setDescription('Deletes expired URL records');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $currentDateTime = new DateTimeImmutable();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->delete(Url::class, 'url')
            ->where('url.expiresAt < :currentDateTime')
            ->setParameter('currentDateTime', $currentDateTime);

        $query = $queryBuilder->getQuery();
        $affectedRows = $query->execute();

        $output->writeln("Expired links deleted successfully. Total affected rows: $affectedRows");

        return Command::SUCCESS;
    }
}
