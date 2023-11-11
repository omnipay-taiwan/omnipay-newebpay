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
        $this->cipher->setKey($this->hashKey);
        $this->cipher->setIv($this->hashIv);
    }

    public function encrypt(array $data): string
    {
        return bin2hex($this->cipher->encrypt(http_build_query($data)));
    }

    public function decrypt(string $plainText): string
    {
        return $this->cipher->decrypt(hex2bin($plainText));
    }

    public function makeHash($data, $swap = false): string
    {
        if (is_array($data)) {
            ksort($data);
            $plainText = http_build_query($data);
        } else {
            $plainText = $data;
        }

        $prefix = 'HashIV='.$this->hashIv;
        $suffix = 'HashKey='.$this->hashKey;

        if ($swap === true) {
            $temp = $suffix;
            $suffix = $prefix;
            $prefix = $temp;
        }

        return strtoupper(hash("sha256", implode('&', [$prefix, $plainText, $suffix])));
    }

    public function check(string $data, string $hashedValue): bool
    {
        return hash_equals($hashedValue, $this->makeHash($data, true));
    }
}
