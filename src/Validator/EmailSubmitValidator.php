<?php

declare(strict_types=1);

namespace App\Validator;

use App\Enum\RequiredFormFields;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class EmailSubmitValidator
{
    private ValidatorInterface $validator;

    public function __construct()
    {
        $this->validator = Validation::createValidator();
    }

    public function validate(array $data): array
    {
        $constraints = new Assert\Collection([
            RequiredFormFields::ACCESS_KEY => [new Assert\NotBlank()],
            RequiredFormFields::NAME => [new Assert\NotBlank()],
            RequiredFormFields::EMAIL => [new Assert\Email()],
            RequiredFormFields::SUBJECT => [new Assert\NotBlank()],
            RequiredFormFields::BODY => [new Assert\NotBlank()],
        ]);

        $violations = $this->validator->validate($data, $constraints);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }

}