<?php

namespace Omnipay\NewebPay\Tests;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\NewebPay\Gateway;
use Omnipay\NewebPay\Message\FetchTransactionRequest;
use Omnipay\NewebPay\Message\GetPaymentInfoRequest;
use Omnipay\NewebPay\Message\RefundRequest;
use Omnipay\NewebPay\Message\VoidRequest;
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

    public function testPurchase(): void
    {
        $timestamp = 1699638290;
        $orderNo = 'test0315001';

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

    public function testCompletePurchase()
    {
        $httpRequest = $this->getHttpRequest();
        $httpRequest->request->add([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS127874575',
            'Version' => '2.0',
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449ebe644d2075fdfbc10150b1c40e7d24cb215febefdb85b16a5cde449f6b06c58a5510d31e8d34c95284d459ae4b52afc1509c2800976a5c0b99ef24cfd28a2dfc8004215a0c98a1d3c77707773c2f2132f9a9a4ce3475cb888c2ad372485971876f8e2fec0589927544c3463d30c785c2d3bd947c06c8c33cf43e131f57939e1f7e3b3d8c3f08a84f34ef1a67a08efe177f1e663ecc6bedc7f82640a1ced807b548633cfa72d060864271ec79854ee2f5a170aa902000e7c61d1269165de330fce7d10663d1668c711571776365bfdcd7ddc915dcb90d31a9f27af9b79a443ca8302e508b0dbaac817d44cfc44247ae613075dde4ac960f1bdff4173b915e4344bc4567bd32e86be7d796e6d9b9cf20476e4996e98ccc315f1ed03a34139f936797d971f2a3f90bc18f8a155a290bcbcf04f4277171c305bf554f5cba243154b30082748a81f2e5aa432ef9950cc9668cd4330ef7c37537a6dcb5e6ef01b4eca9705e4b097cf6913ee96e81d0389e5f775',
            'TradeSha' => 'C80876AEBAC0036268C0E240E5BFF69C0470DE9606EEE083C5C8DD64FDB3347A',
        ]);

        $response = $this->gateway->completePurchase()->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('Vanespl_ec_1695795668', $response->getTransactionId());
        self::assertEquals('23092714215835071', $response->getTransactionReference());
        self::assertEquals('授權成功', $response->getMessage());
    }

    public function testAcceptNotification()
    {
        $httpRequest = $this->getHttpRequest();
        $httpRequest->request->add([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS127874575',
            'Version' => '2.0',
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449ebe644d2075fdfbc10150b1c40e7d24cb215febefdb85b16a5cde449f6b06c58a5510d31e8d34c95284d459ae4b52afc1509c2800976a5c0b99ef24cfd28a2dfc8004215a0c98a1d3c77707773c2f2132f9a9a4ce3475cb888c2ad372485971876f8e2fec0589927544c3463d30c785c2d3bd947c06c8c33cf43e131f57939e1f7e3b3d8c3f08a84f34ef1a67a08efe177f1e663ecc6bedc7f82640a1ced807b548633cfa72d060864271ec79854ee2f5a170aa902000e7c61d1269165de330fce7d10663d1668c711571776365bfdcd7ddc915dcb90d31a9f27af9b79a443ca8302e508b0dbaac817d44cfc44247ae613075dde4ac960f1bdff4173b915e4344bc4567bd32e86be7d796e6d9b9cf20476e4996e98ccc315f1ed03a34139f936797d971f2a3f90bc18f8a155a290bcbcf04f4277171c305bf554f5cba243154b30082748a81f2e5aa432ef9950cc9668cd4330ef7c37537a6dcb5e6ef01b4eca9705e4b097cf6913ee96e81d0389e5f775',
            'TradeSha' => 'C80876AEBAC0036268C0E240E5BFF69C0470DE9606EEE083C5C8DD64FDB3347A',
        ]);

        $request = $this->gateway->acceptNotification();

        self::assertEquals('23092714215835071', $request->getTransactionReference());
        self::assertEquals(NotificationInterface::STATUS_COMPLETED, $request->getTransactionStatus());
        self::assertEquals('授權成功', $request->getMessage());
    }

    public function testFetchTransaction()
    {
        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        $timestamp = 1695795668;
        $request = $this->gateway->fetchTransaction([
            'TimeStamp' => $timestamp,
            'transactionId' => 'Vanespl_ec_'.$timestamp,
            'amount' => 30,
        ]);

        self::assertInstanceOf(FetchTransactionRequest::class, $request);

        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals(1, $response->getCode());
        self::assertEquals('付款成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_1695795668', $response->getTransactionId());
        self::assertEquals('23092714215835071', $response->getTransactionReference());
    }

    public function testGetPaymentInfo()
    {
        $request = $this->gateway->getPaymentInfo([]);

        self::assertInstanceOf(GetPaymentInfoRequest::class, $request);
    }

    public function testVoid()
    {
        $this->setMockHttpResponse('VoidSuccess.txt');

        $timestamp = 1641348593;
        $request = $this->gateway->void([
            'RespondType' => 'String',
            'Version' => '1.0',
            'TimeStamp' => $timestamp,
            'Amt' => '30',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'IndexType' => '1',
            'PayerEmail' => 'tek.chen@ezpay.com.tw',
        ]);

        self::assertInstanceOf(VoidRequest::class, $request);
    }

    public function testRefund()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $timestamp = 1641348593;
        $request = $this->gateway->refund([
            'RespondType' => 'String',
            'Version' => '1.0',
            'TimeStamp' => $timestamp,
            'Amt' => '100',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'TradeNo' => '23111221191660146',
        ]);

        self::assertInstanceOf(RefundRequest::class, $request);
    }
}
