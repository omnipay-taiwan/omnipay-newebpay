<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\AlterPeriodStatusRequest;
use Omnipay\NewebPay\Message\AlterPeriodStatusResponse;
use Omnipay\Tests\TestCase;

class AlterPeriodStatusRequestTest extends TestCase
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

    public function testGetDataSuspend()
    {
        $timestamp = 1700033460;

        $request = new AlterPeriodStatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => $timestamp,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterType' => 'suspend',
        ]));

        $data = $request->getData();

        self::assertEquals('JSON', $data['RespondType']);
        self::assertEquals('1.0', $data['Version']);
        self::assertEquals('myorder1700033460', $data['MerOrderNo']);
        self::assertEquals('P231115153213aMDNWZ', $data['PeriodNo']);
        self::assertEquals('suspend', $data['AlterType']);
        self::assertEquals($timestamp, $data['TimeStamp']);
    }

    public function testGetDataTerminate()
    {
        $request = new AlterPeriodStatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterType' => 'terminate',
        ]));

        $data = $request->getData();
        self::assertEquals('terminate', $data['AlterType']);
    }

    public function testGetDataRestart()
    {
        $request = new AlterPeriodStatusRequest($this->getHttpClient(), $this->getHttpRequest());
        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => 1700033460,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterType' => 'restart',
        ]));

        $data = $request->getData();
        self::assertEquals('restart', $data['AlterType']);
    }

    public function testEndpoint()
    {
        $request = new AlterPeriodStatusRequest($this->getHttpClient(), $this->getHttpRequest());

        $request->initialize(array_merge($this->getBaseOptions(), ['testMode' => true]));
        self::assertEquals('https://ccore.newebpay.com/MPG/period/AlterStatus', $request->getEndpoint());

        $request->initialize(array_merge($this->getBaseOptions(), ['testMode' => false]));
        self::assertEquals('https://core.newebpay.com/MPG/period/AlterStatus', $request->getEndpoint());
    }

    public function testSendDataSuccess()
    {
        $timestamp = 1700033460;

        $request = new AlterPeriodStatusRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->setMockHttpResponse('AlterPeriodStatusSuccess.txt');

        $request->initialize(array_merge($this->getBaseOptions(), [
            'TimeStamp' => $timestamp,
            'transactionId' => 'myorder1700033460',
            'PeriodNo' => 'P231115153213aMDNWZ',
            'AlterType' => 'suspend',
        ]));

        /** @var AlterPeriodStatusResponse $response */
        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('myorder1700033460', $response->getTransactionId());
        self::assertEquals('P231115153213aMDNWZ', $response->getPeriodNo());
        self::assertEquals('suspend', $response->getAlterType());
    }
}
