<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\NewebPay\Message\RefundRequest;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    public function testSendDataFailed(): void
    {
        $timestamp = 1641348593;
        $request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());

        $this->setMockHttpResponse('RefundFailed.txt');

        $request->initialize(array_merge([
            'HashKey' => 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn',
            'HashIV' => 'C6AcmfqJILwgnhIP',
            'MerchantID' => 'MS127874575',
            'testMode' => true,
        ], [
            'RespondType' => 'String',
            'Version' => '1.0',
            'TimeStamp' => $timestamp,
            'Amt' => '100',
            'MerchantOrderNo' => 'Vanespl_ec_'.$timestamp,
            'TradeNo' => '23111221191660146',
        ]));

        $response = $request->send();

        self::assertFalse($response->isSuccessful());
        self::assertEquals('TRA10035', $response->getCode());
        self::assertEquals('該交易非授權成功或已請款完成狀態，請確認', $response->getMessage());
        self::assertEquals('Vanespl_ec_1641348593', $response->getTransactionId());
        self::assertEquals('23111221191660146', $response->getTransactionReference());

        parse_str((string) $this->getMockClient()->getLastRequest()->getBody(), $postData);
        self::assertEquals(
            '61d27f528031d936b29c87802479e4e51e9cc72935abba1cade58c7524504e727e53d7209593175899023a68200d18e9cc998119e760a29cc76a5d1de88fc8da93367ffc9c50f09b9b6a43f42c716a10327734c40bd8b02139601ac6c0674a407930b2bf615bb13c7d5b383ad4c8e879d92298dcbb0be3022a5c8a0143a8c4447e528710993fc41041c299c895b405ed5187ca6f4c3d1a85130c8e83a742e6d1',
            $postData['PostData_']
        );
    }
}
