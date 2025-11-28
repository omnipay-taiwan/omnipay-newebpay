<?php

namespace Omnipay\NewebPay\Tests\Message;

use Omnipay\Common\Message\NotificationInterface;
use Omnipay\NewebPay\Message\AcceptNotificationRequest;
use Omnipay\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;

class AcceptNotificationRequestTest extends TestCase
{
    private $hashKey = 'Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn';
    private $hashIV = 'C6AcmfqJILwgnhIP';
    private $merchantID = 'MS127874575';

    /**
     * Encrypted period notification data for successful authorization.
     * Decrypted JSON: {"Status":"SUCCESS","Message":"每期授權成功","Result":{"MerchantID":"MS127874575","MerchantOrderNo":"myorder1700033460","OrderNo":"myorder1700033460_2","TradeNo":"23111515321368339","PeriodNo":"P231115153213aMDNWZ","TotalTimes":12,"AlreadyTimes":2,"AuthAmt":100,"AuthDate":"2023-12-15","NextAuthDate":"2024-01-15","AuthCode":"230297","RespondCode":"00","CardNo":"400022******1111","EscrowBank":"HNCB","AuthBank":"KGI"}}
     */
    private $encryptedPeriod = '1c666a338762a97c40f28d5d7fe5dfa5040a0bdb8759c385421e808c79b3685bfb1133b1182a5ccb4833733ae70362b32402dc595f0d74c94a95f3ad735374e51e5c059c6361157b3540928ad55ba94f1715eb7a1316ca1aba47db6475b8214d92bcf2099020110b0a64b1f91ba662ef51fc3d869b3318a72a0f2f0aff63d525795baead177e51322949e6d9d9bc71c4f57c5eff9fc8f0479f6d0acf3d05401bd4c496399e80e4cb78583d78850f727ccf9372fd4517cf4edcb716ba3d733c52c70085455dfb822268b05b4a69fce53fd7a972258396c7c5468b87792a2f66992f153a87ab9e239a13e1cc1ac06d56106f8197101d9ff2bd5e2028ce92cb270614b11e597986c0a6ff5772885d9bc202499a11de14345de58236d17902cb149f1af206ba51280829470625294cb4b9e1550f795f64dc5976cd48553eb69fea006e89d181e080d74b739b2c9ee962dcd08ea8086fc104be4fa42d676a250b5002d03cb55172b955f882c5a9d2430e274cb3404099f8f4baf62cbed47f0464f31e90b227952309aad5a1e0530df2ccaae5a0364d25651697892ac9369a18f2553d1017515b9c0405e50eea2b30edfd1b80';

    // 一般交易測試
    public function testGetData(): void
    {
        $httpRequest = $this->getHttpRequest();
        $httpRequest->request->add([
            'Status' => 'SUCCESS',
            'MerchantID' => 'MS127874575',
            'Version' => '2.0',
            'TradeInfo' => 'ee11d1501e6dc8433c75988258f2343d11f4d0a423be672e8e02aaf373c53c2363aeffdb4992579693277359b3e449ebe644d2075fdfbc10150b1c40e7d24cb215febefdb85b16a5cde449f6b06c58a5510d31e8d34c95284d459ae4b52afc1509c2800976a5c0b99ef24cfd28a2dfc8004215a0c98a1d3c77707773c2f2132f9a9a4ce3475cb888c2ad372485971876f8e2fec0589927544c3463d30c785c2d3bd947c06c8c33cf43e131f57939e1f7e3b3d8c3f08a84f34ef1a67a08efe177f1e663ecc6bedc7f82640a1ced807b548633cfa72d060864271ec79854ee2f5a170aa902000e7c61d1269165de330fce7d10663d1668c711571776365bfdcd7ddc915dcb90d31a9f27af9b79a443ca8302e508b0dbaac817d44cfc44247ae613075dde4ac960f1bdff4173b915e4344bc4567bd32e86be7d796e6d9b9cf20476e4996e98ccc315f1ed03a34139f936797d971f2a3f90bc18f8a155a290bcbcf04f4277171c305bf554f5cba243154b30082748a81f2e5aa432ef9950cc9668cd4330ef7c37537a6dcb5e6ef01b4eca9705e4b097cf6913ee96e81d0389e5f775',
            'TradeSha' => 'C80876AEBAC0036268C0E240E5BFF69C0470DE9606EEE083C5C8DD64FDB3347A',
        ]);
        $request = new AcceptNotificationRequest($this->getHttpClient(), $httpRequest);
        $request->initialize([
            'HashKey' => $this->hashKey,
            'HashIV' => $this->hashIV,
            'MerchantID' => $this->merchantID,
            'testMode' => false,
        ]);

        self::assertEquals('23092714215835071', $request->getTransactionReference());
        self::assertEquals(NotificationInterface::STATUS_COMPLETED, $request->getTransactionStatus());
        self::assertEquals('授權成功', $request->getMessage());
    }

    // 定期定額測試
    public function testPeriodGetTransactionStatus(): void
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

    public function testPeriodGetTransactionReference(): void
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

    public function testPeriodGetTransactionId(): void
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

    public function testPeriodGetMessage(): void
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

    public function testPeriodSendReturnsAcceptNotificationResponse(): void
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

    public function testPeriodFailedNotification(): void
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

    private function encryptData(array $data)
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        return bin2hex(openssl_encrypt($json, 'AES-256-CBC', $this->hashKey, OPENSSL_RAW_DATA, $this->hashIV));
    }
}
