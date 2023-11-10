<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\PurchaseRequest;
use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    public function testGetData()
    {
        $timestamp = 1699638290;
        $orderNo = 'test0315001';

        $options = ([
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
        ]);

        $request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($options, [
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => false,
        ]));

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
        ], $request->getData());
    }
}
