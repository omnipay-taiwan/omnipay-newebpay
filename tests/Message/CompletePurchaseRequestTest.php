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
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449eb6a4a2e8871d4ce1374807c7a37547efeb6f3fc6a0a89308bd3eeec2e4146c34dbe40397b2e84a47eba6c4660f95fa2414dc31ba332bea8236bf3f8778fd9f875661598b30a7598c89b6642d3ab9a882a97992c48550a5313bc1f94104385885d2329d2780b6fc8f8cc1323577f3cf2bb9f02c00fee30d72cda74aeff4b5087f949fcc62bf9e963dbfb85214410ac0c9f5167310c3a13dcfac0e28cebb7162ec9900d93d67985525a92ae21d36bb06ed505f903296389500753b26cc197a39afcd2e38b21ba68469d23001f58685c4797197930b0e2744a0997da276adaf15abf01e10f5096d0b36ba098ccfbd33ce4f29f8655b02bf48c3e64894bdb50781dd945708a0d21d17449f1a659f0fdcfd3fab9a423ee43787f3ada33eb6b642e01ed6db8dd64a15ae4358c1a53a2ec58986d080a4b4aaf54811c90ed367363972bf3e346de9984411e6c0d13605dad8996041fa5c5d138daa1c0cb0b6b986ca88b65cb2d7964c01d35a5d9c247467a53dde39dd255776ad2f81f202849da86aef12a1515679ea50f4394b704ed3950ca3a7c138a9128eb48c3ec3501eb28c9e49be588253d25ae3df6a1dc9ddce93758268159ac4dc7e36f840836984cdeac2efd1c353eb0caa4cbc0b7a8f4381101b4125d90e732c27cf7b74ee85dcbce5c0ebfd27974f2f2edd7fc3c6b81e47645fe8ba582d9789a6df0105fbb2ad1073026cbbfa5f8ce3d831856201751903fa40a2d22d248d8d44f57a697d2c26f04faf386a3558ac3ac280690d117ff73f4f8c59cd96f8bc458bf5c7fea2064716aa2c61c4b73fd5cd7e1c57b3ec485264f274abaf8781558020ba13abc8ac4169e4ce7dd280b1fd3eb313831153ca3dac42312672351a782ab7124d5b4ec626533d0d1f7d9',
            'TradeSha' => '0F7D321B19179C553054BF981AB3DBDE327329AEE73A27B7B94D05A0D525830E',
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
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449eb6a4a2e8871d4ce1374807c7a37547efeb6f3fc6a0a89308bd3eeec2e4146c34dbe40397b2e84a47eba6c4660f95fa2414dc31ba332bea8236bf3f8778fd9f875661598b30a7598c89b6642d3ab9a882a97992c48550a5313bc1f94104385885d2329d2780b6fc8f8cc1323577f3cf2bb9f02c00fee30d72cda74aeff4b5087f949fcc62bf9e963dbfb85214410ac0c9f5167310c3a13dcfac0e28cebb7162ec9900d93d67985525a92ae21d36bb06ed505f903296389500753b26cc197a39afcd2e38b21ba68469d23001f58685c4797197930b0e2744a0997da276adaf15abf01e10f5096d0b36ba098ccfbd33ce4f29f8655b02bf48c3e64894bdb50781dd945708a0d21d17449f1a659f0fdcfd3fab9a423ee43787f3ada33eb6b642e01ed6db8dd64a15ae4358c1a53a2ec58986d080a4b4aaf54811c90ed367363972bf3e346de9984411e6c0d13605dad8996041fa5c5d138daa1c0cb0b6b986ca88b65cb2d7964c01d35a5d9c247467a53dde39dd255776ad2f81f202849da86aef12a1515679ea50f4394b704ed3950ca3a7c138a9128eb48c3ec3501eb28c9e49be588253d25ae3df6a1dc9ddce93758268159ac4dc7e36f840836984cdeac2efd1c353eb0caa4cbc0b7a8f4381101b4125d90e732c27cf7b74ee85dcbce5c0ebfd27974f2f2edd7fc3c6b81e47645fe8ba582d9789a6df0105fbb2ad1073026cbbfa5f8ce3d831856201751903fa40a2d22d248d8d44f57a697d2c26f04faf386a3558ac3ac280690d117ff73f4f8c59cd96f8bc458bf5c7fea2064716aa2c61c4b73fd5cd7e1c57b3ec485264f274abaf8781558020ba13abc8ac4169e4ce7dd280b1fd3eb313831153ca3dac42312672351a782ab7124d5b4ec626533d0d1f7d9',
            'TradeSha' => '0F7D321B19179C553054BF981AB3DBDE327329AEE73A27B7B94D05A0D525830E',
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
