<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\CompletePurchaseRequest;
use Omnipay\NewebPay\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function testGetData(): array
    {
        $this->getHttpRequest()->request->add([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS127874575',
            'Version' => '2.0',
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449ebe644d2075fdfbc10150b1c40e7d24cb215febefdb85b16a5cde449f6b06c58a5510d31e8d34c95284d459ae4b52afc1509c2800976a5c0b99ef24cfd28a2dfc8004215a0c98a1d3c77707773c2f2132f9a9a4ce3475cb888c2ad372485971876f8e2fec0589927544c3463d30c785c2d3bd947c06c8c33cf43e131f57939e1f7e3b3d8c3f08a84f34ef1a67a08efe177f1e663ecc6bedc7f82640a1ced807b548633cfa72d060864271ec79854ee2f5a170aa902000e7c61d1269165de330fce7d10663d1668c711571776365bfdcd7ddc915dcb90d31a9f27af9b79a443ca8302e508b0dbaac817d44cfc44247ae613075dde4ac960f1bdff4173b915e4344bc4567bd32e86be7d796e6d9b9cf20476e4996e98ccc315f1ed03a34139f936797d971f2a3f90bc18f8a155a290bcbcf04f4277171c305bf554f5cba243154b30082748a81f2e5aa432ef9950cc9668cd4330ef7c37537a6dcb5e6ef01b4eca9705e4b097cf6913ee96e81d0389e5f775',
            'TradeSha' => 'C80876AEBAC0036268C0E240E5BFF69C0470DE9606EEE083C5C8DD64FDB3347A',
        ]);
        $request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ]);

        $data = $request->getData();

        self::assertEquals([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS127874575',
            'Version' => '2.0',
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449ebe644d2075fdfbc10150b1c40e7d24cb215febefdb85b16a5cde449f6b06c58a5510d31e8d34c95284d459ae4b52afc1509c2800976a5c0b99ef24cfd28a2dfc8004215a0c98a1d3c77707773c2f2132f9a9a4ce3475cb888c2ad372485971876f8e2fec0589927544c3463d30c785c2d3bd947c06c8c33cf43e131f57939e1f7e3b3d8c3f08a84f34ef1a67a08efe177f1e663ecc6bedc7f82640a1ced807b548633cfa72d060864271ec79854ee2f5a170aa902000e7c61d1269165de330fce7d10663d1668c711571776365bfdcd7ddc915dcb90d31a9f27af9b79a443ca8302e508b0dbaac817d44cfc44247ae613075dde4ac960f1bdff4173b915e4344bc4567bd32e86be7d796e6d9b9cf20476e4996e98ccc315f1ed03a34139f936797d971f2a3f90bc18f8a155a290bcbcf04f4277171c305bf554f5cba243154b30082748a81f2e5aa432ef9950cc9668cd4330ef7c37537a6dcb5e6ef01b4eca9705e4b097cf6913ee96e81d0389e5f775',
            'TradeSha' => 'C80876AEBAC0036268C0E240E5BFF69C0470DE9606EEE083C5C8DD64FDB3347A',
            'Result' => [
                'Status' => 'SUCCESS',
                'Message' => '授權成功',
                'MerchantID' => 'MS127874575',
                'Amt' => '30',
                'TradeNo' => '23092714215835071',
                'MerchantOrderNo' => 'Vanespl_ec_1695795668',
                'RespondType' => 'String',
                'IP' => '123.51.237.115',
                'EscrowBank' => 'HNCB',
                'PaymentType' => 'CREDIT',
                'RespondCode' => '00',
                'Auth' => '115468',
                'Card6No' => '400022',
                'Card4No' => '1111',
                'Exp' => '2609',
                'AuthBank' => 'KGI',
                'TokenUseStatus' => '0',
                'InstFirst' => '0',
                'InstEach' => '0',
                'Inst' => '0',
                'ECI' => '',
                'PayTime' => '2023-09-27 14:21:59',
                'PaymentMethod' => 'CREDIT',
            ],
        ], $data);

        return [$request->send(), $data];
    }

    /**
     * @depends testGetData
     */
    public function testSendData($result)
    {
        /** @var PurchaseResponse $response */
        [$response] = $result;

        self::assertTrue($response->isSuccessful());
        self::assertEquals('授權成功', $response->getMessage());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('23092714215835071', $response->getTransactionReference());
        self::assertEquals('Vanespl_ec_1695795668', $response->getTransactionId());
    }

    public function testGetDataForJSON()
    {
        $this->getHttpRequest()->request->add([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS350766787',
            'Version' => '2.0',
            'TradeInfo' => '1c666a338762a97c40f28d5d7fe5dfa52bb5a65f7c460fe5970ac7a993529669a9e11cb3a6ffb7e3447d0482f263eab8fbb4a2e9024a074a1b28e5b07a3cb19a03b493114841831ad6fec058b5b1d9d43bf256b42007f53336ced1687d58984fcf6b54cbafbdb4b31070c2434f00467135f0d60c91dc55d588661b47151842bec5698938743e1dce75de3f26b0ef3bbe69730ef7f15c979cfa9f394e7830d46b318fd97036d4116e59108cf3d67ce1b69db1ce531061a81b91ed6a10ec797fd6f84242dab56a60ce16d02a03af70b0d0746fbbfb04bda214c1d676ee0aeedb01ea9d818e0b06b318af974e93bd4fdffea536f367b52962cd04951dbe805a0f5465e203c924258cc5a2b4d8f742ad60f2a0649c5f00ca39b267d6ee8f21ab0a29ad47abf7f5ade2b4b8b6717b647f9952ead032b9c3fdb0a82d5f9611950f852639d8891f3652761b4004f9c13ad346550b9126fdc3e21e8ca4f94909093cbe746be38df5a1b7d4aad321cb8fea0b1c2f08c8836f7ec32153d6a553724dfc41b06425304af71b91dd4b014f837bc3a028dd626901e324b16be9b9d845bb3b2bc0bf6b089161fa5dd8b786dfa96ca58aa0f37c6361cbe81f33ff888208d6ea60c998ddedc15266ddfd95b7b6b1f293ec4740ac8f56d95b3c1063ed7e7bd9d81c238bc4fb4105862b903d6e6f216dc38a6a0039b6b32fef3a8cf7f51b3eb954bbd3',
            'TradeSha' => 'E70392EF67FADF8D08C60E0F1B3AE0E5510476FD17DFAF18221A1B200A9A4A13',
        ]);
        $request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ]);

        $data = $request->getData();

        self::assertEquals([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS350766787',
            'Version' => '2.0',
            'TradeInfo' => '1c666a338762a97c40f28d5d7fe5dfa52bb5a65f7c460fe5970ac7a993529669a9e11cb3a6ffb7e3447d0482f263eab8fbb4a2e9024a074a1b28e5b07a3cb19a03b493114841831ad6fec058b5b1d9d43bf256b42007f53336ced1687d58984fcf6b54cbafbdb4b31070c2434f00467135f0d60c91dc55d588661b47151842bec5698938743e1dce75de3f26b0ef3bbe69730ef7f15c979cfa9f394e7830d46b318fd97036d4116e59108cf3d67ce1b69db1ce531061a81b91ed6a10ec797fd6f84242dab56a60ce16d02a03af70b0d0746fbbfb04bda214c1d676ee0aeedb01ea9d818e0b06b318af974e93bd4fdffea536f367b52962cd04951dbe805a0f5465e203c924258cc5a2b4d8f742ad60f2a0649c5f00ca39b267d6ee8f21ab0a29ad47abf7f5ade2b4b8b6717b647f9952ead032b9c3fdb0a82d5f9611950f852639d8891f3652761b4004f9c13ad346550b9126fdc3e21e8ca4f94909093cbe746be38df5a1b7d4aad321cb8fea0b1c2f08c8836f7ec32153d6a553724dfc41b06425304af71b91dd4b014f837bc3a028dd626901e324b16be9b9d845bb3b2bc0bf6b089161fa5dd8b786dfa96ca58aa0f37c6361cbe81f33ff888208d6ea60c998ddedc15266ddfd95b7b6b1f293ec4740ac8f56d95b3c1063ed7e7bd9d81c238bc4fb4105862b903d6e6f216dc38a6a0039b6b32fef3a8cf7f51b3eb954bbd3',
            'TradeSha' => 'E70392EF67FADF8D08C60E0F1B3AE0E5510476FD17DFAF18221A1B200A9A4A13',
            'Result' => [
                'Status' => 'SUCCESS',
                'Message' => '授權成功',
                'Result' => [
                    'MerchantID' => 'MS127874575',
                    'Amt' => 30,
                    'TradeNo' => '23092714215835071',
                    'MerchantOrderNo' => 'Vanespl_ec_1695795668',
                    'RespondType' => 'JSON',
                    'IP' => '123.51.237.115',
                    'EscrowBank' => 'HNCB',
                    'ItemDesc' => 'test',
                    'PaymentType' => 'CREDIT',
                    'PayTime' => '2023-09-27 14:21:59',
                    'RespondCode' => '00',
                    'Auth' => '115468',
                    'Card6No' => '400022',
                    'Card4No' => '1111',
                    'Exp' => '2609',
                    'TokenUseStatus' => 0,
                    'InstFirst' => 0,
                    'InstEach' => 0,
                    'Inst' => 0,
                    'ECI' => '',
                    'PaymentMethod' => 'CREDIT',
                    'AuthBank' => 'KGI',
                ],
            ],
        ], $data);

        return [$request->send(), $data];
    }

    /**
     * @depends testGetDataForJSON
     */
    public function testSendDataForJSON($result)
    {
        /** @var PurchaseResponse $response */
        [$response] = $result;

        self::assertTrue($response->isSuccessful());
        self::assertEquals('授權成功', $response->getMessage());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('23092714215835071', $response->getTransactionReference());
        self::assertEquals('Vanespl_ec_1695795668', $response->getTransactionId());
    }
}
