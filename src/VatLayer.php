<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer;

use Psr\Http\Client\ClientExceptionInterface;
use Th3Mouk\VatLayer\Response\ValidVatNumber;

interface VatLayer
{
    /**
     * @throws \Th3Mouk\VatLayer\Exception\VatLayer
     * @throws ClientExceptionInterface
     *
     * @psalm-mutation-free
     */
    public function validate(string $vat_number): ValidVatNumber;
}
