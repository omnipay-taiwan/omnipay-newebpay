<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\FetchTransactionRequest;
use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    public function testSendDataForJSON(): void
    {
        $timestamp = 1695795668;
        $request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->setMockHttpResponse('FetchTransactionSuccess.txt');

        $request->initialize(array_merge([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ], [
            'TimeStamp' => $timestamp,
            'transactionId' => 'Vanespl_ec_'.$timestamp,
            'amount' => 30,
        ]));

        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('00', $response->getCode());
        self::assertEquals('授權成功', $response->getMessage());
        self::assertEquals('Vanespl_ec_1695795668', $response->getTransactionId());
        self::assertEquals('23092714215835071', $response->getTransactionReference());

        parse_str((string) $this->getMockClient()->getLastRequest()->getBody(), $postData);
        self::assertEquals('CD326F689018E7862727547F85CECD7DD7AE0FDB7782DE2C1E46B4417245B51F', $postData['CheckValue']);
    }
}
