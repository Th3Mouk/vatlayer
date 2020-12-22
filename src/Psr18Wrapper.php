<?php

declare(strict_types=1);

namespace Th3Mouk\VatLayer;

use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Th3Mouk\VatLayer\Exception\Api;
use Th3Mouk\VatLayer\Exception\BadResponse;
use Th3Mouk\VatLayer\Response\ValidVatNumber;
use Webmozart\Assert\Assert;

final class Psr18Wrapper implements VatLayer
{
    private const BASE_URL = 'http://apilayer.net/api/';

    private string $access_key;
    private ClientInterface $client;
    private RequestFactoryInterface $request_factory;

    public function __construct(string $access_key, ClientInterface $client, RequestFactoryInterface $request_factory)
    {
        $this->access_key      = $access_key;
        $this->client          = $client;
        $this->request_factory = $request_factory;
    }

    /**
     * @inheritDoc
     * @psalm-mutation-free
     */
    public function validate(string $vat_number): ValidVatNumber
    {
        $request  = $this->request_factory->createRequest(RequestMethodInterface::METHOD_GET, self::BASE_URL . "validate?access_key={$this->access_key}&vat_number={$vat_number}");
        $response = $this->client->sendRequest($request);

        $this->handleResponse($response);

        return ValidVatNumber::createFromResponse($response);
    }

    /**
     * @throws \Th3Mouk\VatLayer\Exception\VatLayer
     *
     * @psalm-mutation-free
     */
    private function handleResponse(ResponseInterface $response): void
    {
        if ($response->getStatusCode() !== StatusCodeInterface::STATUS_OK) {
            throw BadResponse::dueToStatusCode($response);
        }

        try {
            /** @var array<string, scalar|array{type: string, info: string, code: int}> $data */
            $data = json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);

            if (isset($data['success']) && $data['success'] === false) {
                Assert::keyExists($data, 'error');
                Assert::isArray($data['error']);
                Assert::keyExists($data['error'], 'type');
                Assert::keyExists($data['error'], 'info');
                Assert::keyExists($data['error'], 'code');

                throw new Api($data['error']['type'] . ': ' . $data['error']['info'], $data['error']['code']);
            }
        } catch (\JsonException | \InvalidArgumentException $exception) {
            throw BadResponse::dueToInvalidResponseFormat($response, $exception);
        }
    }
}
