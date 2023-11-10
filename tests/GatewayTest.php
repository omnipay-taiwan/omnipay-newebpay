<?php

namespace Omnipay\NewebPay\Tests;

use Omnipay\NewebPay\NewebPayGateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var NewebPayGateway */
    protected $gateway;
    protected $options;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new NewebPayGateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = [
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        ];
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1234', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
        if (PHP_VERSION_ID <= 70200) {
            throw new \RuntimeException('error');
        }
    }
}
