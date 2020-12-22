<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer\Exception;

final class InvalidVatNumber extends VatLayer
{
    public function __construct()
    {
        parent::__construct('Submitted vat number is invalid');
    }
}
