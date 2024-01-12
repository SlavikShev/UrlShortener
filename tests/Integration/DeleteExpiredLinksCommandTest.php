<?php

use App\Console\DeleteExpiredLinksCommand;
use App\Entity\Url;
use App\Tests\Application\BaseWebTestClass;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class DeleteExpiredLinksCommandTest extends BaseWebTestClass
{
    public function testDeleteExpiredLinks(): void
    {
        $this->entityManager->persist((new Url())->setValue('test')->setExpiresAt((new DateTimeImmutable())->modify('-1 day')));
        $this->entityManager->persist((new Url())->setValue('test')->setExpiresAt((new DateTimeImmutable())->modify('1 day')));
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(Url::class);
        $this->assertEquals(2, $repository->count([]));

        $command = new DeleteExpiredLinksCommand($this->entityManager);
        $command->execute(new ArrayInput([]), new NullOutput());

        $this->assertEquals(1, $repository->count([]));
    }
}