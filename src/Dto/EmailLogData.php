<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\OptionalFormFields;
use App\Enum\RequiredFormFields;

final readonly class EmailLogData
{
    public function __construct(
        public ?string $senderName = null,
        public ?string $senderEmail = null,
        public ?string $subject = null,
        public ?string $body = null,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data[RequiredFormFields::NAME],
            $data[RequiredFormFields::EMAIL],
            $data[OptionalFormFields::SUBJECT],
            $data[RequiredFormFields::MESSAGE]
        );
    }
}