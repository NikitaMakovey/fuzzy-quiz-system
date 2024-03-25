<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class AnswerQuestionRequest extends AbstractJsonRequest
{
    #[NotBlank(message: 'Question requires at least one answer.')]
    #[Type('array')]
    #[All([
        new Regex([
            'pattern' => '/^\d+$/',
            'message' => 'Each item must be formatted as an integer.',
        ]),
    ])]
    public readonly array $answers;
}
