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
            'ExpireTime' => '20200101',
            'returnUrl' => 'https://foo.bar/return',
            'notifyUrl' => 'https://foo.bar/notify',
            'customerUrl' => 'https://foo.bar/receive',
            'clientBackUrl' => 'https://foo.bar/client_back',
            'email' => 'foo@bar.com',
            'emailModify' => 1,
            'OrderComment' => 'order_comment',

            // 信用卡相關
            'CREDIT' => 1,
            'InstFlag' => 0,
            'CreditRed' => 1,
            'UNIONPAY' => 1,
            'CREDITAE' => 1,
            'TokenTerm' => 'foo',
            'TokenTermDemand' => 1,
            'NTCB' => 1,
            'NTCBLocate' => '003',
            'NTCBStartDate' => '2020-01-01',
            'NTCBEndDate' => '2020-12-31',

            // ATM 相關
            'WEBATM' => 1,
            'VACC' => 1,
            'BankType' => 'BOT',
            'SourceType' => 1,
            'SourceBankId' => '004',
            'SourceAccountNo' => '1234567890',

            // 超商相關
            'CVS' => 1,
            'BARCODE' => 1,

            // 行動支付
            'ANDROIDPAY' => 1,
            'SAMSUNGPAY' => 1,
            'LINEPAY' => 0,
            'ImageUrl' => 'https://foo.bar/img.jpg',
            'APPLEPAY' => 1,

            // 電子錢包
            'ESUNWALLET' => 1,
            'TAIWANPAY' => 1,
            'TWQR' => 1,
            'TWQR_LifeTime' => 300,
            'EZPWECHAT' => 1,
            'EZPALIPAY' => 1,
            'BITOPAY' => 1,
            'WalletDisplayMode' => 1,

            // 物流
            'CVSCOM' => 1,
            'LgsType' => 'B2C',
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
            'Version' => '2.3',
            'LangType' => 'zh-tw',
            'MerchantOrderNo' => $orderNo,
            'Amt' => 30,
            'ItemDesc' => 'test',
            'TradeLimit' => 3,
            'ExpireDate' => '2020-01-01',
            'ExpireTime' => '20200101',
            'ReturnURL' => 'https://foo.bar/return',
            'NotifyURL' => 'https://foo.bar/notify',
            'CustomerURL' => 'https://foo.bar/receive',
            'ClientBackURL' => 'https://foo.bar/client_back',
            'Email' => 'foo@bar.com',
            'EmailModify' => 1,
            'OrderComment' => 'order_comment',

            // 信用卡相關
            'CREDIT' => 1,
            'InstFlag' => 0,
            'CreditRed' => 1,
            'UNIONPAY' => 1,
            'CREDITAE' => 1,
            'TokenTerm' => 'foo',
            'TokenTermDemand' => 1,
            'NTCB' => 1,
            'NTCBLocate' => '003',
            'NTCBStartDate' => '2020-01-01',
            'NTCBEndDate' => '2020-12-31',

            // ATM 相關
            'WEBATM' => 1,
            'VACC' => 1,
            'BankType' => 'BOT',
            'SourceType' => 1,
            'SourceBankId' => '004',
            'SourceAccountNo' => '1234567890',

            // 超商相關
            'CVS' => 1,
            'BARCODE' => 1,

            // 行動支付
            'ANDROIDPAY' => 1,
            'SAMSUNGPAY' => 1,
            'LINEPAY' => 0,
            'ImageUrl' => 'https://foo.bar/img.jpg',
            'APPLEPAY' => 1,

            // 電子錢包
            'ESUNWALLET' => 1,
            'TAIWANPAY' => 1,
            'TWQR' => 1,
            'TWQR_LifeTime' => 300,
            'EZPWECHAT' => 1,
            'EZPALIPAY' => 1,
            'BITOPAY' => 1,
            'WalletDisplayMode' => 1,

            // 物流
            'CVSCOM' => 1,
            'LgsType' => 'B2C',
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

        $redirectData = $response->getRedirectData();
        self::assertEquals('MS127874575', $redirectData['MerchantID']);
        self::assertEquals('2.3', $redirectData['Version']);
        self::assertNotEmpty($redirectData['TradeInfo']);
        self::assertNotEmpty($redirectData['TradeSha']);
    }

    public function testNewParameters()
    {
        $timestamp = 1699638290;
        $orderNo = 'test_new_params';

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
            'TimeStamp' => $timestamp,
            'transactionId' => $orderNo,
            'amount' => '100',
            'description' => 'test new params',

            // 新增的參數
            'TWQR' => 1,
            'TWQR_LifeTime' => 300,
            'SourceType' => 1,
            'SourceBankId' => '004',
            'SourceAccountNo' => '1234567890',
            'WalletDisplayMode' => 2,
            'ExpireTime' => '20240101',
            'APPLEPAY' => 1,
            'BITOPAY' => 1,
            'OrderDetail' => [
                ['name' => '商品A', 'count' => 1, 'unit' => '個', 'price' => 100],
            ],
        ]);

        $data = $request->getData();

        self::assertEquals(1, $data['TWQR']);
        self::assertEquals(300, $data['TWQR_LifeTime']);
        self::assertEquals(1, $data['SourceType']);
        self::assertEquals('004', $data['SourceBankId']);
        self::assertEquals('1234567890', $data['SourceAccountNo']);
        self::assertEquals(2, $data['WalletDisplayMode']);
        self::assertEquals('20240101', $data['ExpireTime']);
        self::assertEquals(1, $data['APPLEPAY']);
        self::assertEquals(1, $data['BITOPAY']);
        self::assertJson($data['OrderDetail']);
    }

    public function testVersionDefault()
    {
        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
            'TimeStamp' => 1699638290,
            'transactionId' => 'test_version',
            'amount' => '100',
            'description' => 'test version',
        ]);

        $data = $request->getData();
        self::assertEquals('2.3', $data['Version']);
    }

    // === 定期定額測試 ===

    private function getBaseOptions(): array
    {
        return [
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ];
    }

    public function testPeriodGetData()
    {
        $timestamp = 1700033460;
        $orderNo = 'myorder1700033460';

        $options = [
            'TimeStamp' => $timestamp,
            'transactionId' => $orderNo,
            'description' => 'Test commission',
            'amount' => 10,
            'PeriodType' => 'M',
            'PeriodPoint' => '05',
            'PeriodStartType' => 2,
            'PeriodTimes' => 12,
            'PayerEmail' => 'test@neweb.com.tw',
            'PaymentInfo' => 'Y',
            'OrderInfo' => 'N',
            'EmailModify' => 1,
            'notifyUrl' => 'https://webhook.site/test',
        ];

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), $options));

        $data = $request->getData();

        self::assertEquals('MS127874575', $data['MerchantID']);
        self::assertEquals('JSON', $data['RespondType']);
        self::assertEquals($timestamp, $data['TimeStamp']);
        self::assertEquals('1.5', $data['Version']);
        self::assertEquals($orderNo, $data['MerOrderNo']);
        self::assertEquals('Test commission', $data['ProdDesc']);
        self::assertEquals(10, $data['PeriodAmt']);
        self::assertEquals('M', $data['PeriodType']);
        self::assertEquals('05', $data['PeriodPoint']);
        self::assertEquals(2, $data['PeriodStartType']);
        self::assertEquals(12, $data['PeriodTimes']);
        self::assertEquals('test@neweb.com.tw', $data['PayerEmail']);
        self::assertEquals('Y', $data['PaymentInfo']);
        self::assertEquals('N', $data['OrderInfo']);
        self::assertEquals(1, $data['EmailModify']);
        self::assertEquals('https://webhook.site/test', $data['NotifyURL']);

        return [$request->send(), $data];
    }

    /**
     * @depends testPeriodGetData
     */
    public function testPeriodSendData($result)
    {
        /** @var PurchaseResponse $response */
        [$response] = $result;

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('POST', $response->getRedirectMethod());
        self::assertEquals('https://ccore.newebpay.com/MPG/period', $response->getRedirectUrl());

        $redirectData = $response->getRedirectData();
        self::assertEquals('MS127874575', $redirectData['MerchantID_']);
        self::assertNotEmpty($redirectData['PostData_']);
    }

    public function testPeriodWithAllParameters()
    {
        $timestamp = 1700033460;

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => $timestamp,
            'transactionId' => 'test_all_params',
            'description' => 'Test all params',
            'amount' => 100,
            'PeriodType' => 'D',
            'PeriodPoint' => '30',
            'PeriodStartType' => 3,
            'PeriodTimes' => 6,
            'PeriodFirstdate' => '2024/01/15',
            'PayerEmail' => 'test@example.com',
            'LangType' => 'en',
            'returnUrl' => 'https://example.com/return',
            'notifyUrl' => 'https://example.com/notify',
            'cancelUrl' => 'https://example.com/back',
            'PeriodMemo' => 'Test memo',
            'PaymentInfo' => 'N',
            'OrderInfo' => 'Y',
            'EmailModify' => 0,
            'UNIONPAY' => 1,
        ]));

        $data = $request->getData();

        self::assertEquals('D', $data['PeriodType']);
        self::assertEquals('30', $data['PeriodPoint']);
        self::assertEquals(3, $data['PeriodStartType']);
        self::assertEquals('2024/01/15', $data['PeriodFirstdate']);
        self::assertEquals('en', $data['LangType']);
        self::assertEquals('https://example.com/return', $data['ReturnURL']);
        self::assertEquals('https://example.com/back', $data['BackURL']);
        self::assertEquals('Test memo', $data['PeriodMemo']);
        self::assertEquals(1, $data['UNIONPAY']);
    }

    public function testPeriodVersionDefault()
    {
        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'test_version',
            'description' => 'Test version',
            'amount' => 100,
            'PeriodType' => 'M',
            'PeriodPoint' => '01',
            'PeriodStartType' => 1,
            'PeriodTimes' => 12,
            'PayerEmail' => 'test@example.com',
        ]));

        $data = $request->getData();
        self::assertEquals('1.5', $data['Version']);
    }

    public function testPeriodEndpoint()
    {
        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize(array_merge($this->getBaseOptions(), [
            'testMode' => true,
            'PeriodType' => 'M',
        ]));
        self::assertEquals('https://ccore.newebpay.com/MPG/period', $request->getEndpoint());

        $request->initialize(array_merge($this->getBaseOptions(), [
            'testMode' => false,
            'PeriodType' => 'M',
        ]));
        self::assertEquals('https://core.newebpay.com/MPG/period', $request->getEndpoint());
    }
}
