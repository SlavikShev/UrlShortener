<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use DateInterval;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Monolog\DateTimeImmutable;

#[ORM\Entity(repositoryClass: UrlRepository::class)]
class Url
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $clicksCount = 0;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $expiresAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getClicksCount(): int
    {
        return $this->clicksCount;
    }

    public function setClicksCount(int $clicksCount): void
    {
        $this->clicksCount = $clicksCount;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(int $daysToLive): void
    {
        $this->expiresAt = (new DateTimeImmutable(true, new DateTimeZone('UTC')))
            ->add(new DateInterval("P{$daysToLive}D"));
    }
}
