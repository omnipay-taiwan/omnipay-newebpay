<?php

namespace Omnipay\NewebPay\Traits;

use Omnipay\NewebPay\Encryptor;

trait HasEncryptor
{
    public function encrypt(array $data)
    {
        return $this->getEncryptor()->encrypt($data);
    }

    public function decrypt(string $plainText)
    {
        return $this->getEncryptor()->decrypt($plainText);
    }

    public function tradeSha($plainText)
    {
        return $this->getEncryptor()->tradeSha($plainText);
    }

    public function checkValue($data)
    {
        return $this->getEncryptor()->checkValue($data);
    }

    public function checkCode($data)
    {
        return $this->getEncryptor()->checkCode($data);
    }

    private function getEncryptor()
    {
        return new Encryptor($this->getHashKey(), $this->getHashIv());
    }
}
