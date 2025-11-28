<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\NewebPay\Message\AcceptNotificationRequest;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AcceptPeriodNotificationRequestTest extends TestCase
{
    private string $hashKey = 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn';

    private string $hashIV = 'C6AcmfqJILwgnhIP';

    private string $merchantID = 'MS127874575';

    /**
     * Encrypted period notification data for successful authorization.
     * Decrypted JSON: {"Status":"SUCCESS","Message":"每期授權成功","Result":{"MerchantID":"MS127874575","MerchantOrderNo":"myorder1700033460","OrderNo":"myorder1700033460_2","TradeNo":"23111515321368339","PeriodNo":"P231115153213aMDNWZ","TotalTimes":12,"AlreadyTimes":2,"AuthAmt":100,"AuthDate":"2023-12-15","NextAuthDate":"2024-01-15","AuthCode":"230297","RespondCode":"00","CardNo":"400022******1111","EscrowBank":"HNCB","AuthBank":"KGI"}}
     */
    private string $encryptedPeriod = '1c666a338762a97c40f28d5d7fe5dfa5040a0bdb8759c385421e808c79b3685bfb1133b1182a5ccb4833733ae70362b32402dc595f0d74c94a95f3ad735374e51e5c059c6361157b3540928ad55ba94f1715eb7a1316ca1aba47db6475b8214d92bcf2099020110b0a64b1f91ba662ef51fc3d869b3318a72a0f2f0aff63d525795baead177e51322949e6d9d9bc71c4f57c5eff9fc8f0479f6d0acf3d05401bd4c496399e80e4cb78583d78850f727ccf9372fd4517cf4edcb716ba3d733c52c70085455dfb822268b05b4a69fce53fd7a972258396c7c5468b87792a2f66992f153a87ab9e239a13e1cc1ac06d56106f8197101d9ff2bd5e2028ce92cb270614b11e597986c0a6ff5772885d9bc202499a11de14345de58236d17902cb149f1af206ba51280829470625294cb4b9e1550f795f64dc5976cd48553eb69fea006e89d181e080d74b739b2c9ee962dcd08ea8086fc104be4fa42d676a250b5002d03cb55172b955f882c5a9d2430e274cb3404099f8f4baf62cbed47f0464f31e90b227952309aad5a1e0530df2ccaae5a0364d25651697892ac9369a18f2553d1017515b9c0405e50eea2b30edfd1b80';

    public function testGetTransactionStatus(): void
    {
        $httpRequest = Request::create('/', 'POST', ['Period' => $this->encryptedPeriod]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
        ]);

        self::assertEquals(NotificationInterface::STATUS_COMPLETED, $request->getTransactionStatus());
    }

    public function testGetTransactionReference(): void
    {
        $httpRequest = Request::create('/', 'POST', ['Period' => $this->encryptedPeriod]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
        ]);

        self::assertEquals('23111515321368339', $request->getTransactionReference());
    }

    public function testGetTransactionId(): void
    {
        $httpRequest = Request::create('/', 'POST', ['Period' => $this->encryptedPeriod]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
        ]);

        self::assertEquals('myorder1700033460', $request->getTransactionId());
    }

    public function testGetMessage(): void
    {
        $httpRequest = Request::create('/', 'POST', ['Period' => $this->encryptedPeriod]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
        ]);

        self::assertEquals('每期授權成功', $request->getMessage());
    }

    public function testSendReturnsAcceptPeriodNotificationResponse(): void
    {
        $httpRequest = Request::create('/', 'POST', ['Period' => $this->encryptedPeriod]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
        ]);

        $response = $request->send();

        self::assertTrue($response->isSuccessful());
        self::assertEquals('SUCCESS', $response->getCode());
        self::assertEquals('myorder1700033460_2', $response->getOrderNo());
        self::assertEquals('23111515321368339', $response->getTransactionReference());
        self::assertEquals(12, $response->getTotalTimes());
        self::assertEquals(2, $response->getAlreadyTimes());
        self::assertEquals(100, $response->getAuthAmt());
        self::assertEquals('2023-12-15', $response->getAuthDate());
        self::assertEquals('2024-01-15', $response->getNextAuthDate());
    }

    public function testFailedNotification(): void
    {
        // Encrypted failed notification data
        $failedData = $this->encryptData([
            'Status' => 'AUTH_FAILED',
            'Message' => '授權失敗',
            'Result' => [
                'MerchantID' => 'MS127874575',
                'MerchantOrderNo' => 'myorder1700033460',
                'OrderNo' => 'myorder1700033460_2',
                'TradeNo' => '23111515321368339',
            ],
        ]);

        $httpRequest = Request::create('/', 'POST', ['Period' => $failedData]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
        ]);

        self::assertEquals(NotificationInterface::STATUS_FAILED, $request->getTransactionStatus());
        self::assertEquals('授權失敗', $request->getMessage());
    }

    private function encryptData(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        return bin2hex(openssl_encrypt($json, 'AES-256-CBC', $this->hashKey, OPENSSL_RAW_DATA, $this->hashIV));
    }
}
