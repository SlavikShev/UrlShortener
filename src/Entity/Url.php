<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

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

    public function setClicksCount(int $clicksCount): static
    {
        $this->clicksCount = $clicksCount;

        return $this;
    }

    public function getExpiresAt(): DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function setExpiresAtByDays(int $daysToLive): static
    {
        if ($daysToLive < 1) {
            throw new InvalidArgumentException('Days to live must be greater than 0');
        }

        $now = new DateTimeImmutable();
        $interval = new DateInterval("P{$daysToLive}D");
        $this->expiresAt = $now->add($interval);

        return $this;
    }
}
