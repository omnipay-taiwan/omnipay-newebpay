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
            'testMode' => true,
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
        $timestamp = 1699638290;
        $orderNo = "test0315001";

        $response = $this->gateway->purchase([
            'transactionId' => $orderNo,
            'amount' => '30',
            'description' => 'test',
            'notifyUrl' => 'https://webhook.site/97c6899f-077b-4025-9948-9ee96a38dfb7',
            'TimeStamp' => $timestamp,
            'VACC' => '1',
            'ALIPAY' => '0',
            'WEBATM' => '1',
            'CVS' => '1',
            'CREDIT' => '1',
            'LoginType' => '0',
            'InstFlag' => '0',
        ])->send();

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('POST', $response->getRedirectMethod());
        self::assertEquals('https://ccore.newebpay.com/MPG/mpg_gateway', $response->getRedirectUrl());
        self::assertEquals([
            'MerchantID' => 'MS127874575',
            'TradeInfo' => 'f79eac33c4f3245d58f17b544c5d38b09457a6d77e77bae6f10fcc7236fe153c8c64bb8a18d78f883bafa7589824e927e47b9316cec473d5e360111c720bded6a27e67c124f16dfdacf31e36a4827ade3f743892ac45552a8f3db856151680b4ad501297db228097a0c5f2e7d0fc549c46d307351330864b415a28cadaf43dcd3124040779e4b77bddb0c903a2c26c8ad1bf9d6ede68f995a7cc3560c8cb6392642ace61cb1e4265edf93e8a8269530fa6e086febf301a6dd7f2489a12ae6b85bacd22cb246bd714cdd47bc029592bd95f2cf43855446b9059619b24eb0c66b6a0d57af751286710845c614724eaef22948b981c23622911617d024982ec9402f57025c6100330fcbf7d7612b17f9feb',
            'TradeSha' => 'EF16E08855A885FE8F7D0594C1DA99178F4E4004B042A9CF3FF77CDE66B3F030',
            'Version' => '2.0',
        ], $response->getRedirectData());
    }
}
