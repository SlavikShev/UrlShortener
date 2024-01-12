<?php

namespace App\Requests;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Url;

readonly class ShortUrlRequest
{
    public function __construct(
        private string $url,
        private int $daysToLive
    ) {}

    #[NotBlank]
    #[Url]
    public function getUrl(): string
    {
        return trim($this->url);
    }

    #[NotBlank]
    #[Range(min: 1, max: 30)]
    public function getDaysToLive(): int
    {
        return $this->daysToLive;
    }
}