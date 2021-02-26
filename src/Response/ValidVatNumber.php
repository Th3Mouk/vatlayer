<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer\Response;

use Psr\Http\Message\ResponseInterface;
use Th3Mouk\VatLayer\Exception\BadResponse;
use Th3Mouk\VatLayer\Exception\InvalidVatNumber;
use Webmozart\Assert\Assert;

/** @psalm-immutable  */
final class ValidVatNumber
{
    public string $query;
    public string $country_code;
    public string $vat_vumber;
    public string $company_name;
    public string $company_address;

    private function __construct(
        string $query,
        string $country_code,
        string $vat_vumber,
        string $company_name,
        string $company_address
    ) {
        $this->query           = $query;
        $this->country_code    = $country_code;
        $this->vat_vumber      = $vat_vumber;
        $this->company_name    = $company_name;
        $this->company_address = $company_address;
    }

    public static function create(
        string $query,
        string $country_code,
        string $vat_vumber,
        string $company_name,
        string $company_address
    ): self {
        return new self($query, $country_code, $vat_vumber, $company_name, $company_address);
    }

    /**
     * @throws BadResponse
     * @throws InvalidVatNumber
     *
     * @psalm-pure
     */
    public static function createFromResponse(ResponseInterface $response): self
    {
        try {
            /** @var array<string, scalar> $data */
            $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            Assert::keyExists($data, 'valid');
            $is_valid = (bool) $data['valid'];

            if (!$is_valid) {
                throw new InvalidVatNumber();
            }

            $query           = $data['query'] ?? '';
            $country_code    = $data['country_code'] ?? '';
            $vat_vumber      = $data['vat_number'] ?? '';
            $company_name    = $data['company_name'] ?? '';
            $company_address = $data['company_address'] ?? '';

            Assert::stringNotEmpty($query);
            Assert::stringNotEmpty($country_code);
            Assert::stringNotEmpty($vat_vumber);
            Assert::string($company_name);
            Assert::string($company_address);

            return new self($query, $country_code, $vat_vumber, $company_name, $company_address);
        } catch (\JsonException | \InvalidArgumentException $exception) {
            throw BadResponse::dueToInvalidResponseFormat($response, $exception);
        }
    }
}
