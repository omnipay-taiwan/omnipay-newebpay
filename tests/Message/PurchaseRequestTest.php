<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\NewebPay\Message\PurchaseRequest;
use Omnipay\NewebPay\Message\PurchaseResponse;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    /**
     * @throws InvalidRequestException
     */
    public function testGetData()
    {
        $timestamp = 1699638290;
        $orderNo = 'test0315001';

        $options = [
            'TimeStamp' => $timestamp,
            'transactionId' => $orderNo,
            'LangType' => 'zh-tw',
            'amount' => '30',
            'description' => 'test',
            'TradeLimit' => 3,
            'ExpireDate' => '2020-01-01',
            'returnUrl' => 'https://foo.bar/return',
            'notifyUrl' => 'https://foo.bar/notify',
            'customerUrl' => 'https://foo.bar/receive',
            'clientBackUrl' => 'https://foo.bar/client_back',
            'email' => 'foo@bar.com',
            'emailModify' => 1,
            'LoginType' => 0,
            'OrderComment' => 'order_comment',
            'CREDIT' => 1,
            'ANDROIDPAY' => 1,
            'SAMSUNGPAY' => 1,
            'LINEPAY' => 0,
            'ImageUrl' => 'https://foo.bar/img.jpg',
            'InstFlag' => 0,
            'CreditRed' => 1,
            'UNIONPAY' => 1,
            'CREDITAE' => 1,
            'WEBATM' => 1,
            'VACC' => 1,
            'BankType' => 'BOT',
            'ALIPAY' => 1,
            'CVS' => 1,
            'BARCODE' => 1,
            'ESUNWALLET' => 1,
            'TAIWANPAY' => 1,
            'FULA' => 1,
            'CVSCOM' => 1,
            'EZPAY' => 1,
            'EZPWECHAT' => 1,
            'EZPALIPAY' => 1,
            'LgsType' => 'B2C',
            'NTCB' => 1,
            'NTCBLocate' => '003',
            'NTCBStartDate' => '2020-01-01',
            'NTCBEndDate' => '2020-12-31',
            'TokenTerm' => 'foo',
            'TokenTermDemand' => 1,
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($options, [
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ]));

        $data = $request->getData();
        self::assertEquals([
            'MerchantID' => 'MS127874575',
            'RespondType' => 'JSON',
            'TimeStamp' => $timestamp,
            'Version' => '2.0',
            'LangType' => 'zh-tw',
            'MerchantOrderNo' => $orderNo,
            'Amt' => '30',
            'ItemDesc' => 'test',
            'TradeLimit' => 3,
            'ExpireDate' => '2020-01-01',
            'ReturnURL' => 'https://foo.bar/return',
            'NotifyURL' => 'https://foo.bar/notify',
            'CustomerURL' => 'https://foo.bar/receive',
            'ClientBackURL' => 'https://foo.bar/client_back',
            'Email' => 'foo@bar.com',
            'EmailModify' => 1,
            'LoginType' => 0,
            'OrderComment' => 'order_comment',
            'CREDIT' => 1,
            'ANDROIDPAY' => 1,
            'SAMSUNGPAY' => 1,
            'LINEPAY' => 0,
            'ImageUrl' => 'https://foo.bar/img.jpg',
            'InstFlag' => 0,
            'CreditRed' => 1,
            'UNIONPAY' => 1,
            'CREDITAE' => 1,
            'WEBATM' => 1,
            'VACC' => 1,
            'BankType' => 'BOT',
            'ALIPAY' => 1,
            'CVS' => 1,
            'BARCODE' => 1,
            'ESUNWALLET' => 1,
            'TAIWANPAY' => 1,
            'FULA' => 1,
            'CVSCOM' => 1,
            'EZPAY' => 1,
            'EZPWECHAT' => 1,
            'EZPALIPAY' => 1,
            'LgsType' => 'B2C',
            'NTCB' => 1,
            'NTCBLocate' => '003',
            'NTCBStartDate' => '2020-01-01',
            'NTCBEndDate' => '2020-12-31',
            'TokenTerm' => 'foo',
            'TokenTermDemand' => 1,
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

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('POST', $response->getRedirectMethod());
        self::assertEquals('https://ccore.newebpay.com/MPG/mpg_gateway', $response->getRedirectUrl());

        self::assertEquals([
            'MerchantID' => 'MS127874575',
            'TradeInfo' => 'f79eac33c4f3245d58f17b544c5d38b09457a6d77e77bae6f10fcc7236fe153c8c64bb8a18d78f883bafa7589824e927e47b9316cec473d5e360111c720bded661257bc9e95a2063c17c4203e90983ecb46bc55097abb72801678f3a4c647fc958d68f5875ef5053b1f423b2d502eef1d6cfabc8bf8cf2f0e0270d39dc0fc524640e07308a85397f727924d1b905b1d78f02cc723fba42a5c617d4b586eb716f66a5ee7a7f468246e6b0537c4edc88024570107b6943d11adb4fc2fa1b9b57f5dec5447711c8ab203d5e8b4bc4f64f500d0d6e29ba5e59faf396007f10de7e6fb8a2adc51da1e7e3de634f5d9c83f10b4e74f34089ddcc16cdd0ab5a03e889559dcdf55f8932afc16eca7e13ed90b70b2b8155259413e6fe132fbf2984f758f80f078e78d596892bc64bf1cf0b5632440c5a15e08355ea9cc9915fb59423768a33c8f9a6e61f2446e4b99a1850c58041cffed358e4339163b7f92a872597b1baf8d7ddbd049a81a95a8bd2217fa71a7e0b659197b62142b83e06443d66cc2be6db08b0b141dfeb6931852c1342f03eb54b1c37569974d87e778d594d0818845bfeddee2ab4b41f6404cd5dae680c7e543ec4b5a9e4090d904b4f039939018b9186ddd5fd2088f078ebd3bbccd8c066ef798a0d763b9c207ccc2008ca1358bf9c80b4461cb2206c76150312ba1b6678baaf34254841f2a983a292784512fe52df5707da355af2bf9505fd16eada3294f57d87aec0290fb56cd513399f309ba7435e72a92155612d8e6d1fdd1139a3db65fa740604f1ab43dfb32101e650be82bb73d4a6f3226b31129c4dfa858cb1692f24779453a63c1f444303c65bd6c6a750deadf86e18d57455276473f92979aeaf324da0dc75184010eccb21f0ab155367226c53b49c503b48f013eba92ec3f496d3e868117cc91a6803fd50d6cfc7c3f6c2dbc29f68edf671cbcc754a9dbef8114b3f951d46c7ad55e75ed99220fda640c144528cbb038593a4a77f4992542e5e3ed42c69314fac1935cf67c2a586e24381456702429aa7dac25460d9f128cc57d8bc7e4e8240c21e03c94f2d49d90cff0e1b3a63d73cefc3d75dc2673f15d327bec3a118ccf72b6028538b7ca2bdbdde',
            'TradeSha' => '0151B630699784486996214B7D07B87813A099652A75AC8FE42685F5D292583D',
            'Version' => '2.0',
        ], $response->getRedirectData());
    }
}
