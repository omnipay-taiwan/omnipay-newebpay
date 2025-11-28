<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\CreatePeriodRequest;
use Omnipay\NewebPay\Message\CreatePeriodResponse;
use Omnipay\Tests\TestCase;

class CreatePeriodRequestTest extends TestCase
{
    private function getBaseOptions(): array
    {
        return [
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ];
    }

    public function testGetData()
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

        $request = new CreatePeriodRequest($this->getHttpClient(), $this->getHttpRequest());
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
     * @depends testGetData
     */
    public function testSendData($result)
    {
        /** @var CreatePeriodResponse $response */
        [$response] = $result;

        self::assertFalse($response->isSuccessful());
        self::assertTrue($response->isRedirect());
        self::assertEquals('POST', $response->getRedirectMethod());
        self::assertEquals('https://ccore.newebpay.com/MPG/period', $response->getRedirectUrl());

        $redirectData = $response->getRedirectData();
        self::assertEquals('MS127874575', $redirectData['MerchantID_']);
        self::assertNotEmpty($redirectData['PostData_']);
    }

    public function testWithAllParameters()
    {
        $timestamp = 1700033460;

        $request = new CreatePeriodRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testVersionDefault()
    {
        $request = new CreatePeriodRequest($this->getHttpClient(), $this->getHttpRequest());
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

    public function testEndpoint()
    {
        $request = new CreatePeriodRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize(array_merge($this->getBaseOptions(), ['testMode' => true]));
        self::assertEquals('https://ccore.newebpay.com/MPG/period', $request->getEndpoint());

        $request->initialize(array_merge($this->getBaseOptions(), ['testMode' => false]));
        self::assertEquals('https://core.newebpay.com/MPG/period', $request->getEndpoint());
    }
}
