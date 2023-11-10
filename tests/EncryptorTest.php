<?php

namespace Omnipay\NewebPay\Tests;

use Omnipay\NewebPay\Encryptor;
use PHPUnit\Framework\TestCase;

class EncryptorTest extends TestCase
{
    private $key = "Fs5cX1TGqYM2PpdbE14a9H83YQSQF5jn";
    private $iv = "C6AcmfqJILwgnhIP";
    private $mid = "MS127874575";
    private $timestamp = 1695795410;

    public function testEncrypt(): void
    {
        $encryptor = $this->givenEncryptor();

        self::assertEquals(
            $this->openssl(),
            $encryptor->encrypt([
                'MerchantID' => $this->mid,
                'RespondType' => 'String',
                'TimeStamp' => $this->timestamp,
                'Version' => '2.0',
                'MerchantOrderNo' => 'Vanespl_ec_'.$this->timestamp,
                'Amt' => '30',
                'ItemDesc' => 'test',
                'NotifyURL' => 'https://webhook.site/d4db5ad1-2278-466a-9d66-78585c0dbadb',
            ])
        );
    }

    private function openssl(): string
    {
        $data = http_build_query([
            'MerchantID' => $this->mid,
            'RespondType' => 'String',
            'TimeStamp' => $this->timestamp,
            'Version' => '2.0',
            'MerchantOrderNo' => 'Vanespl_ec_'.$this->timestamp,
            'Amt' => '30',
            'ItemDesc' => 'test',
            'NotifyURL' => 'https://webhook.site/d4db5ad1-2278-466a-9d66-78585c0dbadb',
        ]);

        $eData = bin2hex(openssl_encrypt($data, "AES-256-CBC", $this->key, OPENSSL_RAW_DATA, $this->iv));

        self::assertEquals(
            'f79eac33c4f3245d58f17b544c5d38b09457a6d77e77bae6f10fcc7236fe153ccef1a80001c0746afc063a7570f80ad970d8a32c72332c9ec5547410188007876bdca2bafa52d07d31b6b183f2204d6e4feee6d245e286ab198cf95422ad5843c7696fc943cbb65979ad207607d4b5d97dac4a90ccd5e7a37adb7d7062e838be09d94e8c5dfa145c048e17feabe58c2e310792f0f50f5af32961ffb07ff6649ae1021ad558242551de5f09316e3182e198775e5d1ad5b66a70be290004de750fa85d86b0c2f087b40005d89e048be2ab6fd83f1c522494c093426a10a1f73fe4',
            $eData
        );

        $hashed = strtoupper(hash("sha256", "HashKey=".$this->key."&".$eData."&HashIV=".$this->iv));
        self::assertEquals('84E4D9F96537E029F8450BE1E759080F9AF6995921B7F6F9AAFDDD2C36E7B287', $hashed);

        return $hashed;
    }

    /**
     * @return Encryptor
     */
    private function givenEncryptor(): Encryptor
    {
        $encryptor = new Encryptor($this->key, $this->iv);

        return $encryptor;
    }
}
