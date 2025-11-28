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
}
