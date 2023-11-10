<?php

namespace Omnipay\NewebPay;

use phpseclib3\Crypt\AES;

class Encryptor
{
    private $cipher;
    private $key;
    private $iv;

    public function __construct($key, $iv)
    {
        $this->key = $key;
        $this->iv = $iv;

        $this->cipher = new AES('cbc');
        $this->cipher->setKey($this->key);
        $this->cipher->setIv($this->iv);
    }

    public function encrypt($data): string
    {
        return strtoupper(
            hash(
                "sha256",
                sprintf(
                    'HashKey=%s&%s&HashIV=%s',
                    $this->key,
                    bin2hex($this->cipher->encrypt(http_build_query($data))),
                    $this->iv
                )
            )
        );
    }

    public function decrypt($plainText): string
    {
        return $this->cipher->decrypt(hex2bin($plainText));
    }
}
