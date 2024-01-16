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
        $this->cipher->enablePadding();

        return bin2hex($this->cipher->encrypt(http_build_query($data)));
    }

    public function decrypt(string $plainText): string
    {
        $this->cipher->disablePadding();

        return $this->stripPadding($this->cipher->decrypt(hex2bin($plainText)));
    }

    public function tradeSha($data)
    {
        return $this->makeHash([
            'HashKey='.$this->hashKey,
            $this->toPlainText($data),
            'HashIV='.$this->hashIv,
        ]);
    }

    public function checkValue($data)
    {
        if (is_array($data)) {
            $data = self::only($data, ['MerchantID', 'Amt', 'MerchantOrderNo']);
        }

        return $this->makeHash([
            'IV='.$this->hashIv,
            $this->toPlainText($data),
            'Key='.$this->hashKey,
        ]);
    }

    public function checkCode($data)
    {
        if (is_array($data)) {
            $data = self::only($data, ['MerchantID', 'Amt', 'MerchantOrderNo', 'TradeNo']);
        }

        return $this->makeHash([
            'HashIV='.$this->hashIv,
            $this->toPlainText($data),
            'HashKey='.$this->hashKey,
        ]);
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

    /**
     * @param  string|array  $data
     * @return string
     */
    private function toPlainText($data)
    {
        if (! is_array($data)) {
            $plainText = $data;
        } else {
            ksort($data);
            $plainText = http_build_query($data);
        }

        return $plainText;
    }

    private function makeHash(array $data)
    {
        return strtoupper(hash('sha256', implode('&', $data)));
    }

    private static function only(array $array, array $keys = [])
    {
        $result = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }

        return $result;
    }
}
