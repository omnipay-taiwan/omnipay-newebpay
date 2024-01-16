<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\VoidRequest;
use Omnipay\Tests\TestCase;

class VoidRequestTest extends TestCase
{
    public function testSendData(): void
    {
        $timestamp = 1641348593;
        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->setMockHttpResponse('VoidSuccess.txt');

        $request->initialize(array_merge([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ], [
            'RespondType' => 'String',
            'Version' => '1.0',
            'TimeStamp' => $timestamp,
            'Amt' => '30',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'IndexType' => '1',
            'PayerEmail' => 'tek.chen@ezpay.com.tw',
        ]));

        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('放棄授權成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_1641348593', $response->getTransactionId());
        self::assertEquals('23111221191660146', $response->getTransactionReference());

        parse_str((string) $this->getMockClient()->getLastRequest()->getBody(), $postData);
        self::assertEquals(
            '61d27f528031d936b29c87802479e4e51e9cc72935abba1cade58c7524504e72a86f00fe167dca60eefc3f9c17917154a7c626641829b6bac38e3863b97c1b11a91399194a674a8fc2820c2247954fc5b16a2094e89a3fa79b15b3bf0c8dbf0677b7420af3e5c528426e1e0e6c41206b',
            $postData['PostData_']
        );
        self::assertEquals(
            'https://ccore.newebpay.com/API/CreditCard/Cancel',
            (string) $this->getMockClient()->getLastRequest()->getUri()
        );
    }

    public function testSendDataForJSON(): void
    {
        $timestamp = 1641348593;
        $request = new VoidRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->setMockHttpResponse('VoidSuccessJSON.txt');

        $request->initialize(array_merge([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ], [
            'RespondType' => 'JSON',
            'Version' => '1.0',
            'TimeStamp' => $timestamp,
            'Amt' => '30',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'IndexType' => '1',
            'PayerEmail' => 'tek.chen@ezpay.com.tw',
        ]));

        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('放棄授權成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_1641348593', $response->getTransactionId());
        self::assertEquals('23111221191660146', $response->getTransactionReference());
    }
}
