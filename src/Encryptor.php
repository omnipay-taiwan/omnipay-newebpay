<?php

namespace Omnipay\NewebPay;

use phpseclib3\Crypt\AES;

class Encryptor
{
    private $cipher;
    private $key;
    private $iv;

    public function __construct(string $key, string $iv)
    {
        $this->key = $key;
        $this->iv = $iv;

        $this->cipher = new AES('cbc');
        $this->cipher->setKey($this->key);
        $this->cipher->setIv($this->iv);
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

        $prefix = 'HashIV='.$this->iv;
        $suffix = 'HashKey='.$this->key;

        if ($swap === true) {
            $temp = $suffix;
            $suffix = $prefix;
            $prefix = $temp;
        }

        return strtoupper(hash("sha256", implode('&', [$prefix, $plainText, $suffix])));
    }
}
