<?php

declare(strict_types=1);

namespace App\Dto;

use App\Enum\OptionalFormFields;
use App\Enum\RequiredFormFields;
use App\Exception\ValidationException;
use App\Validator\EmailSubmitValidator;

final class EmailSubmitDto
{
    public function __construct(
        private string $accessKey,
        private string $fullName,
        private string $emailAddress,
        private string $messageSubject,
        private string $messageBody,
        private array $receivers = [],
    )
    {
    }

    public function getAccessKey(): string
    {
        return $this->accessKey;
    }

    public function setAccessKey(string $accessKey): EmailSubmitDto
    {
        $this->accessKey = $accessKey;
        return $this;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): EmailSubmitDto
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): EmailSubmitDto
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    public function getMessageSubject(): string
    {
        return $this->messageSubject;
    }

    public function setMessageSubject(string $messageSubject): EmailSubmitDto
    {
        $this->messageSubject = $messageSubject;
        return $this;
    }

    public function getMessageBody(): string
    {
        return $this->messageBody;
    }

    public function setMessageBody(string $messageBody): EmailSubmitDto
    {
        $this->messageBody = $messageBody;
        return $this;
    }

    public function getReceivers(): array
    {
        return $this->receivers;
    }

    /** @param array<int, array<string, string>> $receivers */
    public function setReceivers(array $receivers): EmailSubmitDto
    {
        $this->receivers = $receivers;
        return $this;
    }

    /** @param array<string, string> $receivers */
    public function addReceiver(array $receivers): EmailSubmitDto
    {
        $this->receivers[] = $receivers;
        return $this;
    }

    /** @throws ValidationException */
    public static function fromArray(array $data): self
    {
        $validator = new EmailSubmitValidator();
        $errors = $validator->validate($data);

        if (!empty($errors)) {
            throw new ValidationException(
                'Submitted data missing required fields',
                'Invalid data: ' . json_encode($errors)
            );
        }

        if (false === self::isOptionalSubjectProvided($data)) {
            $data[OptionalFormFields::SUBJECT] = sprintf('New message from %s', $data[RequiredFormFields::EMAIL]);
        }

        return new self(
            $data[RequiredFormFields::ACCESS_KEY],
            $data[RequiredFormFields::NAME],
            $data[RequiredFormFields::EMAIL],
            $data[OptionalFormFields::SUBJECT],
            $data[RequiredFormFields::MESSAGE]
        );
    }

    private static function isOptionalSubjectProvided(array $data): bool
    {
        return array_key_exists(OptionalFormFields::SUBJECT, $data);
    }
}