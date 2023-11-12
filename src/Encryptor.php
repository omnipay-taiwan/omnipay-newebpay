<?php

namespace Omnipay\NewebPay;

use phpseclib3\Crypt\AES;

class Encryptor
{
    private $cipher;
    private $hashKey;
    private $hashIv;

    public function __construct(string $hashKey, string $hashIv)
    {
        $this->hashKey = $hashKey;
        $this->hashIv = $hashIv;

        $this->cipher = new AES('cbc');
//        $this->cipher->disablePadding();
        $this->cipher->setKey($this->hashKey);
        $this->cipher->setIv($this->hashIv);
    }

    public function encrypt(array $data): string
    {
        return bin2hex($this->cipher->encrypt(http_build_query($data)));
    }

    public function decrypt(string $plainText): string
    {
        return self::stripPadding($this->cipher->decrypt(hex2bin($plainText)));
    }

    public function tradeSha($data)
    {
        if (is_array($data)) {
            ksort($data);
            $plainText = http_build_query($data);
        } else {
            $plainText = $data;
        }

        return strtoupper(hash(
            "sha256",
            implode('&', ['HashKey='.$this->hashKey, $plainText, 'HashIV='.$this->hashIv])
        ));
    }

    public function checkValue($data)
    {
        if (is_array($data)) {
            ksort($data);
            $plainText = http_build_query($data);
        } else {
            $plainText = $data;
        }

        return strtoupper(hash(
            "sha256",
            implode('&', ['IV='.$this->hashIv, $plainText, 'Key='.$this->hashKey])
        ));
    }

    public function checkCode($data)
    {
        if (is_array($data)) {
            ksort($data);
            $plainText = http_build_query($data);
        } else {
            $plainText = $data;
        }

        return strtoupper(hash(
            "sha256",
            implode('&', ['HashIV='.$this->hashIv, $plainText, 'HashKey='.$this->hashKey])
        ));
    }

    private function stripPadding($value)
    {
        $pad = ord($value[($len = strlen($value)) - 1]);

        return $this->paddingIsValid($pad, $value) ? substr($value, 0, $len - $pad) : $value;
    }

    private function paddingIsValid($pad, $value)
    {
        $beforePad = strlen($value) - $pad;

        return substr($value, $beforePad) === str_repeat(substr($value, -1), $pad);
    }
}
