<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\AlterPeriodAmtRequest;
use Omnipay\NewebPay\Message\AlterPeriodAmtResponse;
use Omnipay\Tests\TestCase;

class AlterPeriodAmtRequestTest extends TestCase
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

        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => $timestamp,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterAmt' => 15,
        ]));

        $data = $request->getData();

        self::assertEquals('JSON', $data['RespondType']);
        self::assertEquals('1.2', $data['Version']);
        self::assertEquals('myorder1700033460', $data['MerOrderNo']);
        self::assertEquals('P231115153213aMDNWZ', $data['PeriodNo']);
        self::assertEquals(15, $data['AlterAmt']);
        self::assertEquals($timestamp, $data['TimeStamp']);
    }

    public function testGetDataWithPeriodType()
    {
        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterAmt' => 20,
            'PeriodType' => 'M',
            'PeriodPoint' => '10',
        ]));

        $data = $request->getData();
        self::assertEquals('M', $data['PeriodType']);
        self::assertEquals('10', $data['PeriodPoint']);
    }

    public function testGetDataWithPeriodTimes()
    {
        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterAmt' => 20,
            'PeriodTimes' => 24,
        ]));

        $data = $request->getData();
        self::assertEquals(24, $data['PeriodTimes']);
    }

    public function testGetDataWithExtday()
    {
        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterAmt' => 20,
            'Extday' => '2908',
        ]));

        $data = $request->getData();
        self::assertEquals('2908', $data['Extday']);
    }

    public function testGetDataWithNotifyURL()
    {
        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterAmt' => 20,
            'NotifyURL' => 'https://example.com/notify',
        ]));

        $data = $request->getData();
        self::assertEquals('https://example.com/notify', $data['NotifyURL']);
    }

    public function testEndpoint()
    {
        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize(array_merge($this->getBaseOptions(), ['testMode' => true]));
        self::assertEquals('https://ccore.newebpay.com/MPG/period/AlterAmt', $request->getEndpoint());

        $request->initialize(array_merge($this->getBaseOptions(), ['testMode' => false]));
        self::assertEquals('https://core.newebpay.com/MPG/period/AlterAmt', $request->getEndpoint());
    }

    public function testSendDataSuccess()
    {
        $timestamp = 1700033460;

        $request = new AlterPeriodAmtRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->setMockHttpResponse('AlterPeriodAmtSuccess.txt');

        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => $timestamp,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterAmt' => 15,
        ]));

        /** @var AlterPeriodAmtResponse $response */
        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('myorder1700033460', $response->getTransactionId());
        self::assertEquals('P231115153213aMDNWZ', $response->getPeriodNo());
        self::assertEquals(15, $response->getAlterAmt());
        self::assertEquals(15, $response->getNewNextAmt());
        self::assertEquals('2023-12-05', $response->getNewNextTime());
        self::assertEquals(12, $response->getPeriodTimes());
    }
}
