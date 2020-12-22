<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer\Exception;

final class Api extends VatLayer
{
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}
