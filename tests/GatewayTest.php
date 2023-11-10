<?php

namespace Omnipay\NewebPay\Tests;

use Omnipay\NewebPay\Gateway;
use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var Gateway */
    protected $gateway;
    protected $options;

    public function setUp(): void
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->initialize([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
        ]);
    }

    public function testAuthorize()
    {
        $this->setMockHttpResponse('AuthorizeSuccess.txt');

        $response = $this->gateway->authorize([
            'amount' => '10.00',
            'card' => $this->getValidCard(),
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('1234', $response->getTransactionReference());
        $this->assertNull($response->getMessage());
    }

    public function testPurchase(): void
    {
        $timestamp = time();
        $orderNo = "test0315001_12345678";

        $response = $this->gateway->purchase([
            'TimeStamp' => $timestamp,
            'MerchantOrderNo' => $orderNo,
            'Amt' => '30',
            'VACC' => '1',
            'ALIPAY' => '0',
            'WEBATM' => '1',
            'CVS' => '1',
            'CREDIT' => '1',
            'NotifyURL' => 'https://webhook.site/97c6899f-077b-4025-9948-9ee96a38dfb7',
            'LoginType' => '0',
            'InstFlag' => '0',
            'ItemDesc' => 'test',
        ])->send();

        self::assertEquals([
            'MerchantID' => 'MS127874575',
            'TimeStamp' => $timestamp,
            'Version' => '2.0',
            'RespondType' => 'JSON',
            'MerchantOrderNo' => $orderNo,
            'Amt' => '30',
            'VACC' => '1',
            'ALIPAY' => '0',
            'WEBATM' => '1',
            'CVS' => '1',
            'CREDIT' => '1',
            'NotifyURL' => 'https://webhook.site/97c6899f-077b-4025-9948-9ee96a38dfb7',
            'LoginType' => '0',
            'InstFlag' => '0',
            'ItemDesc' => 'test',
        ], $response->getData());
    }
}
