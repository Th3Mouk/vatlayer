<?php

declare(strict_types=1);

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Th3Mouk\VatLayer\Exception\Api;
use Th3Mouk\VatLayer\Exception\InvalidVatNumber;
use Th3Mouk\VatLayer\Psr18Wrapper;
use Th3Mouk\VatLayer\Tests\MockClient;

test('validate() returning response object', function () {
    $response = new Response(
        200,
        ['Content-Type' => 'application/json'],
        file_get_contents(__DIR__ . '/responses/validate-success.json')
    );

    $client = new MockClient($response);

    $response = (new Psr18Wrapper('', $client, new Psr17Factory()))->validate('vat-number');

    $address = <<<TXT
301 BISCAYNE BLVD
FL 33132 MIAMI
TXT;

    expect($response->query)->toBe('FR14394151119');
    expect($response->country_code)->toBe('FR');
    expect($response->vat_vumber)->toBe('14394151119');
    expect($response->company_name)->toBe('SUNRISE LIVESET');
    expect($response->company_address)->toBe($address);
});

test('validate() throwing InvalidVatNumber', function () {
    $response = new Response(
        200,
        ['Content-Type' => 'application/json'],
        file_get_contents(__DIR__ . '/responses/validate-bad-vat.json')
    );

    $client = new MockClient($response);

    (new Psr18Wrapper('', $client, new Psr17Factory()))->validate('vat-number');
})->throws(InvalidVatNumber::class);

test('throwing exception if access key is invalid', function () {
    $response = new Response(
        200,
        ['Content-Type' => 'application/json'],
        file_get_contents(__DIR__ . '/responses/bad-access-key.json')
    );

    $client = new MockClient($response);

    (new Psr18Wrapper('', $client, new Psr17Factory()))->validate('vat-number');
})->throws(Api::class);
